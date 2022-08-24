<?php


namespace App\Http\Controllers\Api;


use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\CallbackTransferRequest;
use App\Http\Requests\API\HandleTransfer;
use App\Http\Requests\API\ResetTransfer;
use App\Http\Requests\Auth\ApiRequest;
use App\Models\Bank;
use App\Models\HistoryTransfer;
use App\Services\HistoryTransferService;
use Illuminate\Http\JsonResponse;
use Telegram\Bot\Laravel\Facades\Telegram;

class HistoryAPI extends Controller
{
    protected $historyTransferService;
    protected $bank;

    public function __construct(ApiRequest $request, HistoryTransferService $historyTransferService)
    {
        $this->middleware('auth.bank_api');
        $params = $request->parameters();
        $this->bank = Bank::whereToken($params['token'])->first();
        $this->historyTransferService = $historyTransferService;
    }

    public function callbackTransfer(CallbackTransferRequest $request)
    {
        $history = $this->historyTransferService->findHistory($request->parameters());

        return ($history) ? response()->json(['status' => TransactionStatus::SUCCESS, 'message' => 'Thành Công !', 'data' => $history]) : response()->json(['status' => TransactionStatus::ERROR, 'message' => 'Không tìm thấy giao dịch !', 'data' => []]);
    }

    public function getTransfer()
    {
        return response()->json($this->historyTransferService->findHistory(['status' => TransactionStatus::PENDING, 'bank_id' => $this->bank->id]));
    }

    public function getListTransfer()
    {
        return response()->json($this->historyTransferService->getHistory(['status' => TransactionStatus::PENDING, 'bank_id' => $this->bank->id]));
    }


    public function handleTransfer(HandleTransfer $request)
    {
        $params = $request->parameters();
        $history = HistoryTransfer::whereBankId($this->bank->id)->whereTransId($params['trans_id'])->whereStatus(TransactionStatus::PENDING)->first();

        if ($history === null) {
            return response()->json(['status' => TransactionStatus::ERROR, 'message' => 'Giao dịch không tồn tại hoặc đã được xử lý']);
        }

        Telegram::sendMessage([
            'chat_id' => config('telegram.bots.mybot.chanel_id'),
            'parse_mode' => 'HTML',
            'text' => 'Bot đang xử lý giao dịch ' . $params['trans_id'] . ' ! Hãy chú ý!'
        ]);

        return response()->json($this->historyTransferService->updateHistory($history, ['status' => TransactionStatus::WAITOTP])->only(['trans_id', 'system_id', 'status']));

    }

    /**
     * @param HistoryTransfer $history
     * @return JsonResponse|void
     */
    public function reset(ResetTransfer $request)
    {
        $params = $request->parameters();

        $history = HistoryTransfer::whereBankId($this->bank->id)->whereTransId($params['trans_id'])->first();

        if (!empty($history) && $history->status !== TransactionStatus::SUCCESS && $history->status !== TransactionStatus::PENDING) {
            $params = [
                'status' => TransactionStatus::PENDING
            ];
            return response()->json(['status' => TransactionStatus::SUCCESS, 'message' => 'Thành công', 'data' => $this->historyTransferService->updateHistory($history, $params)->only(['trans_id', 'system_id', 'status'])]);
        }

        return response()->json(['status' => TransactionStatus::ERROR, 'message' => 'Giao dịch này không thể reset vào lúc này!']);
    }
}
