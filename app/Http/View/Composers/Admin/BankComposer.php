<?php

namespace App\Http\View\Composers\Admin;

use App\Enums\BankStatus;
use Illuminate\Contracts\View\View;

class BankComposer
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
            '_activeRoute' => 'admin.banks_index',
            '_breadcrumb' => [
                [
                    'label' => 'Ngân hàng',
                    'route' => route('admin.banks_index')
                ],
            ],
            'bankStatus' => binding_enum(BankStatus::values(), 'bank_status'),
            'bankStatusEnum' => BankStatus::toArray(),
        ]);
    }
}
