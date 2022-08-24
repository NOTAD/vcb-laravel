<?php

namespace App\Http\View\Composers\Admin;

use App\Models\Role;
use Illuminate\Contracts\View\View;

class IndexComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $adminRoles = Role::whereGuard('admin')->get();
        $admin = admin();
        $view->with([
            '_user' => $admin,
            '_availableRoles' => admin_avaiable_roles($admin, $adminRoles),
            '_adminRoles' => $adminRoles,
            '_notifications' => []
        ]);
    }
}
