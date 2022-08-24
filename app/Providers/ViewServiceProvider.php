<?php

namespace App\Providers;

use App\Http\View\Composers\Admin\AdminComposer;
use App\Http\View\Composers\Admin\BankComposer;
use App\Http\View\Composers\Admin\FileComposer;
use App\Http\View\Composers\Admin\HistoryTransfersComposer;
use App\Http\View\Composers\Admin\IndexComposer;
use App\Http\View\Composers\Admin\ProfileComposer;
use App\Http\View\Composers\Admin\RoleComposer;
use App\Http\View\Composers\Admin\SettingComposer;
use App\Http\View\Composers\Admin\UserComposer;
use App\Http\View\Composers\Shop\ShopComposer;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Shop
        view()->composer(['user.screens.*', 'user.layouts.*'], ShopComposer::class);
//		view()->composer(['shop.screens.home', 'shop.screens.post'], 'App\Http\ViewComposers\Shop\HomeComposer::class);
//		view()->composer(['shop.screens.category'], 'App\Http\ViewComposers\Shop\ProductComposer::class);
//		view()->composer(['shop.screens.blog'], 'App\Http\ViewComposers\Shop\BlogComposer::class);


        // Admin
        view()->composer(['admin.screens.*'], IndexComposer::class);
        view()->composer(['admin.screens.admins'], AdminComposer::class);
        view()->composer(['admin.screens.files'], FileComposer::class);
        view()->composer(['admin.screens.users'], UserComposer::class);
        view()->composer(['admin.screens.profile'], ProfileComposer::class);
        view()->composer(['admin.screens.history_transfers'], HistoryTransfersComposer::class);
        view()->composer(['admin.screens.banks'], BankComposer::class);

        view()->composer(['admin.screens.setting'], SettingComposer::class);
        view()->composer(['admin.screens.roles'], RoleComposer::class);

    }
}
