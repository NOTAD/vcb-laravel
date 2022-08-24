<?php

namespace App\Http\View\Composers\Admin;

use Illuminate\Contracts\View\View;

class FileComposer
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
            '_activeRoute' => 'admin.files_index',
            '_breadcrumb' => [
                [
                    'label' => 'Thư viện ảnh',
                    'route' => route('admin.files_index')
                ],
            ]
        ]);
    }
}
