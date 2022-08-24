<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class IndexController extends Controller
{
    /**
     * @return RedirectResponse
     */
    public function index()
    {
        return redirect()->route('admin.setting_index');
    }

}
