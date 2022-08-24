<?php

namespace App\Services;

use App\Models\Bank;
use Illuminate\Support\Facades\DB;

class BankService
{

    /**
     * @param $params
     * @return mixed
     */
    public function searchBank($params)
    {
        $query = Bank::with('transfers');
        if (!empty($params['id'])) {
            $query->where('id', $params['id']);
        }
        if (!empty($params['search'])) {
            $query->where(function ($query) use ($params) {
                $query->where('id', $params['search'])
                    ->orWhere('username', 'like', '%' . $params['search'] . '%');
            });
        }
        if (!empty($params['except_id'])) {
            $query->where('id', '<>', $params['except_id']);
        }
        return $query->paginate(config('app.pagination'));
    }

    /**
     * @param array $bankAttributes
     * @return mixed
     */
    public function createBank(array $bankAttributes)
    {
        return DB::transaction(function () use ($bankAttributes) {
            $bank = new Bank($bankAttributes);
            $bank->save();
            return $bank->load('transfers');
        });
    }

    /**
     * @param Bank $bank
     * @param array $bankAttributes
     * @return mixed
     */
    public function updateBank(Bank $bank, array $bankAttributes)
    {
        return DB::transaction(function () use ($bankAttributes, $bank) {
            $bank->update($bankAttributes);
            return $bank->load('transfers');
        });
    }

    /**
     * @param Bank $bank
     * @return mixed
     */
    public function deleteBank(Bank $bank)
    {
        return DB::transaction(function () use ($bank) {
            $bank->delete();
            return $bank;
        });
    }
}
