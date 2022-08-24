<?php

namespace App\Http\View\Composers\Admin;

use App\Enums\TransactionStatus;
use Illuminate\Contracts\View\View;

class HistoryTransfersComposer
{

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with([
            '_activeRoute' => 'admin.history_transfers_index',
            '_breadcrumb' => [
                [
                    'label' => 'Lịch sử chuyển khoản',
                    'route' => route('admin.history_transfers_index')
                ],
            ],
            'transactionStatus' => binding_enum(TransactionStatus::values(), 'transaction_status'),
            'transactionStatusEnum' => TransactionStatus::toArray(),
        ]);
    }
}
