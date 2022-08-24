<?php


namespace App\Http\View\Composers\Shop;


use App\Enums\Languages;
use App\Enums\VoucherTypes;
use App\Models\Game;
use App\Models\ProjectCategory;
use App\Models\Setting;
use Illuminate\Contracts\View\View;

class ShopComposer
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
            'settings' => Setting::first(),
            'project_categories' => ProjectCategory::select('id', 'name', 'name_en', 'slug')->with('projects')->whereIsDeleted(false)->get(),
            'lang_enum' => Languages::toArray(),
        ]);
    }
}
