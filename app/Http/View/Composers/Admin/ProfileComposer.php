<?php

namespace App\Http\View\Composers\Admin;

use Illuminate\Contracts\View\View;

class ProfileComposer
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
            '_activeRoute' => 'admin.admin_profile',
            '_breadcrumb' => [
                [
                    'label' => 'Trang cá nhân',
                    'route' => route('admin.admin_profile')
                ],
            ]
        ]);
    }
}
