<?php

namespace App\Http\View\Composers\Admin;

use Illuminate\Contracts\View\View;

class UserComposer
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
            '_activeRoute' => 'admin.users_index',
            '_breadcrumb' => [
                [
                    'label' => 'Người dùng',
                    'route' => route('admin.users_index')
                ],
            ]
        ]);
    }
}
