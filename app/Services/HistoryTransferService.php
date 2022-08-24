<?php

namespace App\Services;

use App\Models\HistoryTransfer;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Laravel\Facades\Telegram;

class HistoryTransferService
{
    /**
     * @param $params
     * @return mixed
     */
    public function searchHistory($params)
    {
        $query = HistoryTransfer::with('bank');
        if (!empty($params['id'])) {
            $query->where('id', $params['id']);
        }

        if (!empty($params['bank_id'])) {
            $query->where('bank_id', $params['bank_id']);
        }

        if (!empty($params['receiver_account'])) {
            $query->where('receiver_account', $params['receiver_account']);
        }
        if (!empty($params['trans_id'])) {
            $query->where('trans_id', $params['trans_id']);
        }
        if (!empty($params['system_id'])) {
            $query->where('system_id', $params['system_id']);
        }

        if (!empty($params['search'])) {
            $query->where(function ($query) use ($params) {
                $query->where('id', $params['search'])
                    ->orWhere('trans_id', 'like', '%' . $params['search'] . '%')
                    ->orWhere('receiver_account', 'like', '%' . $params['search'] . '%');
            });
        }
        if (!empty($params['except_id'])) {
            $query->where('id', '<>', $params['except_id']);
        }
        return $query->orderBy('created_at', 'desc')->paginate(config('app.pagination'));
    }

    public function getHistory($params)
    {
        $query = HistoryTransfer::select('trans_id', 'system_id', 'amount', 'status');

        if (!empty($params['bank_id'])) {
            $query->where('bank_id', $params['bank_id']);
        }

        if (!empty($params['status'])) {
            $query->where('status', $params['status']);
        }

        return $query->orderBy('created_at', 'asc')->get();
    }

    public function findHistory($params)
    {
        $query = HistoryTransfer::select('trans_id', 'system_id', 'amount', 'status');

        if (!empty($params['bank_id'])) {
            $query->where('bank_id', $params['bank_id']);
        }
        if (!empty($params['trans_id'])) {
            $query->where('trans_id', $params['trans_id']);
        }
        if (!empty($params['system_id'])) {
            $query->where('system_id', $params['system_id']);
        }
        if (!empty($params['status'])) {
            $query->where('status', $params['status']);
        }
        $history = $query->first();
        return $history;
    }

    /**
     * @param array $historyAttributes
     * @return mixed
     */
    public function createHistory(array $historyAttributes)
    {
        return DB::transaction(function () use ($historyAttributes) {
            $history = new HistoryTransfer($historyAttributes);
            $history->save();

            Telegram::sendMessage([
                'chat_id' => config('telegram.bots.mybot.chanel_id'),
                'parse_mode' => 'HTML',
                'text' => 'Có lệnh chuyển khoản mới. Transaction id :  ' . $historyAttributes['trans_id'] . ' ! Vui lòng vào kiểm tra !'
            ]);

            return $history;
        });
    }

    /**
     * @param HistoryTransfer $history
     * @param array $historyAttributes
     * @return mixed
     */
    public function updateHistory(HistoryTransfer $history, array $historyAttributes)
    {
        return DB::transaction(function () use ($history, $historyAttributes) {
            $history->update($historyAttributes);
            return $history;
        });
    }

    /**
     * @param HistoryTransfer $history
     * @return mixed
     */
    public function deleteHistory(HistoryTransfer $history)
    {
        return DB::transaction(function () use ($history) {
            $history->delete();
            return $history;
        });
    }
}
