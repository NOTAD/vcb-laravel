<?php

namespace App\Http\Controllers\Api;

use App\Enums\TransactionStatus;
use App\Helpers\AutoTech;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\ConfirmOtpTransferRequest;
use App\Http\Requests\API\CreateTransferRequest;
use App\Http\Requests\Auth\ApiRequest;
use App\Jobs\ProcessCallback;
use App\Models\Bank;
use App\Models\HistoryTransfer;
use App\Services\HistoryTransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use Throwable;

class TransferApi extends Controller
{
    protected $api;
    protected $historyTransferService;
    protected $bank;

    public function __construct(ApiRequest $request, HistoryTransferService $historyTransferService)
    {
        $this->middleware('auth.bank_api');
        $params = $request->parameters();
        $this->historyTransferService = $historyTransferService;
        $this->bank = Bank::whereToken($params['token'])->first();
        $account_number = isset($params['account_number']) ? $params['account_number'] : $this->bank->account_number;
        $this->api = new AutoTech($this->bank->username, $this->bank->password, $this->bank->imei, $account_number,$this->bank->stkname);
        $this->api->login();
    }

    public function getBankList247()
    {
        $bankData = $this->api->getBankList247();
        return ($bankData['status']) ?
            response()->json(['status' => TransactionStatus::SUCCESS, 'message' => $bankData['message'], 'data' => $bankData['data']]) :
            response()->json(['status' => TransactionStatus::ERROR, 'message' => $bankData['message'], 'data' => $bankData['data']]);
    }

    public function checkBlance()
    {
        $result = $this->api->getInfo();
        if ($result['status']) {
            $data = [
                'alias' => $result['data']['bankAccount']['displayNumber'],
                'account_number' => $result['data']['bankAccount']['accountNumber'],
                'blance' => $result['data']['bankAccount']['spareFields']['spareInteger1'],
                'currency' => $result['data']['bankAccount']['currency'],
            ];
            return response()->json(['status' => TransactionStatus::SUCCESS, 'message' => $result['message'], 'data' => $data]);
        }
        return response()->json(['status' => TransactionStatus::ERROR, 'message' => $result['message'], 'data' => $result['data']]);
    }

    public function getAccountName(Request $request)
    {
        $result = $this->api->getAccountName($request->input('receiver_account_number'), $request->input('receiver_bank_id'));
        return ($result['status']) ?
            response()->json(['status' => TransactionStatus::SUCCESS, 'message' => $result['message'], 'data' => $result['data']]) :
            response()->json(['status' => TransactionStatus::ERROR, 'message' => $result['message'], 'data' => $result['data']]);
    }

    public function getTechAccountName(Request $request)
    {
        $result = $this->api->getTechAccountName((string)$request->input('receiver_account_number'));

        return ($result['status']) ?
            response()->json(['status' => TransactionStatus::SUCCESS, 'message' => $result['message'], 'data' => $result['data']]) :
            response()->json(['status' => TransactionStatus::ERROR, 'message' => $result['message'], 'data' => $result['data']]);
    }


    public function getTransactionHistories(Request $request)
    {
        $fromDate = $request->input('from_date') ?: date('Ymd', strtotime('yesterday'));
        $toDate = $request->input('to_date') ?: date('Ymd', strtotime('tomorrow'));
        $limit = $request->input('limit') ?: 10;
        $histories = $this->api->getHistories($fromDate, $toDate, $limit);
        return ($histories['status']) ?
            response()->json(['status' => TransactionStatus::SUCCESS, 'message' => $histories['message'], 'data' => $histories['data']]) :
            response()->json(['status' => TransactionStatus::ERROR, 'message' => $histories['message'], 'data' => $histories['data']]);
    }

    public function createTransfer(CreateTransferRequest $request)
    {
        $params = $request->parameters();

        try {
            if ($params['receiver_bank_id'] == '970407') {
                Log::debug('Create Tech Transfer', ['params' => $params]);
                $result = $this->api->createTechTranfer($params['receiver_account_number'], $params['amount'], $params['reason']);
            } else {
                Log::debug('Create 247 Transfer', ['params' => $params]);
                $result = $this->api->createTransfer247($params['receiver_bank_id'], $params['receiver_account_number'], $params['amount'], $params['reason']);
            }
            if ($result['status'] && $result['data']['Status']['code'] === 2521) {
                $attributes = [
                    'trans_id' => (int)$result['data']['UnstructuredData'][0]['Value'],
                    'system_id' => (int)$result['data']["Transaction"]["systemId"],
                    'amount' => (int)$params['amount'],
                    'receiver_account' => $params['receiver_account_number'],
                    'callback_url' => $params['callback_url'],
                    'account_number' => $this->api->account_number,
                    'receiver_bank_id' => (int)$params['receiver_bank_id'],
                    'reason' => $params['reason'],
                    'status' => TransactionStatus::PENDING,
                    'bank_id' => $this->bank->id
                ];

                $history = $this->historyTransferService->createHistory($attributes);

                $data_reponse = Arr::except($history, ['callback_url', 'status', 'bank_id', 'updated_at', 'created_at', 'id']);
                $data_reponse['sign'] = md5(
                    (int)$attributes['amount'] .
                    (int)$attributes['trans_id'] .
                    (int)$attributes['system_id'] .
                    $attributes['account_number'] .
                    $attributes['reason'] .
                    $attributes['receiver_account'] .
                    $attributes['receiver_bank_id']
                );

                return response()->json([
                    'status' => TransactionStatus::SUCCESS,
                    'message' => 'Đã gửi yêu cầu, vui lòng chờ hệ thống xử lý!',
                    'data' => $data_reponse]);
            }
            return response()->json(['status' => TransactionStatus::ERROR, 'message' => $result['message']]);
        } catch (Throwable $exception) {
            Log::error('Transfer Error', ['code' => $exception->getCode(), 'message' => $exception->getMessage()]);
            return response()->json(['status' => TransactionStatus::ERROR, 'message' => $exception->getMessage()]);
        }

    }

    public function confirmOtpTransfer(ConfirmOtpTransferRequest $request)
    {
        $params = $request->parameters();
        Log::info('Confirm Otp Transfer Request ', $params);
        $history = HistoryTransfer::where('trans_id', $params['trans_id'])->where('status', TransactionStatus::WAITOTP)->first();
        if ($history !== null) {
            $confirm = $this->api->confirmOtpTransfer($params['otp'], $params['system_id']);
            $data = ($confirm['status'] && $confirm['data']['Status']['code'] === 0) ? ['status' => TransactionStatus::SUCCESS, 'otp' => $params['otp'], 'message' => 'Giao dịch thành công'] : ['status' => TransactionStatus::ERROR, 'otp' => $params['otp'], 'message' => $confirm['data']['Status']['value']];
            $new_history = $this->historyTransferService->updateHistory($history, $data);
            ProcessCallback::dispatchAfterResponse($new_history);

            Telegram::sendMessage([
                'chat_id' => config('telegram.bots.mybot.chanel_id'),
                'parse_mode' => "HTML",
                'text' => "Giao dịch {$history->id} đã được xử lý. Trạng thái : {$new_history->message} !"
            ]);

            return response()->json($new_history->only(['trans_id', 'system_id', 'status', 'message']));
        }

        return response()->json(['status' => TransactionStatus::ERROR, 'message' => 'Giao dịch không tồn tại hoặc đã được xử lý']);
    }

}
