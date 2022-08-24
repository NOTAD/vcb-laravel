<?php

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = new Setting([
            'logo' => '/images/logo.png',
            'name' => 'Cày Thuê',
            'address' => 'Hà Nội, Việt Nam',
            'phone' => '0436688xxx',
            'hotline' => '0986688xxx',
            'email' => 'contact@dzus-dev.com',
            'color' => '#000',
            'title' => 'Cày Thuê - Hệ thống cày thuê uy tín nhất việt nam',
            'keyword' => 'Cày Thuê',
            'description' => 'Cày Thuê - Hệ thống cày thuê uy tín nhất việt nam',
            'thumbnail' => '/template/images/logo.jpg',
            'favicon' => '/template/images/logo.jpg',
            'slider' => json_encode([
                [
                    'image' => '/images/subscribe.jpg',
                    'link' => ''
                ],
                [
                    'image' => '/images/subscribe.jpg',
                    'link' => ''
                ],
            ])
        ]);
        $setting->save();
    }
}
