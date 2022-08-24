<?php

use App\Models\Role;
use Illuminate\Support\Str;


if (!function_exists('encrypt_decrypt')) {

    function encrypt_decrypt($string, bool $is_encrypt = true)
    {
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'AA74CDCC2BBRT935136HH7B63C27';
        $key = substr(hash('sha256', $secret_key, true), 0, 32);
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

        return ($is_encrypt)
            ? bin2hex(base64_encode(openssl_encrypt($string, $encrypt_method, $key, OPENSSL_RAW_DATA, $iv)))
            : openssl_decrypt(base64_decode(hex2bin($string)), $encrypt_method, $key, OPENSSL_RAW_DATA, $iv);

    }
}

if (!function_exists('current_guard')) {
    function current_guard()
    {
        if (auth('admin')->check()) {
            return "admin";
        }

        if (auth('user')->check()) {
            return "user";
        }

        return 'guest';
    }
}


if (!function_exists('admin')) {
    function admin()
    {
        if (auth('admin')->check()) {
            return auth('admin')->user()->load('roles');
        }

        return null;
    }
}

if (!function_exists('is_system_admin')) {
    function is_system_admin($admin)
    {
        foreach ($admin->roles as $role) {
            if ($role->id === (int)config('app.system_role_id')) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('user')) {
    function user(array $relation = [])
    {

        if (auth('user')->check()) {
            if (empty($relation)) {
                $relation = 'roles';
            }

            return auth('user')->user()->load($relation);
        }

        return null;
    }
}

if (!function_exists('binding_enum')) {
    function binding_enum($enum, $langKey)
    {
        $data = [];

        foreach ($enum as $item) {
            $data[] = [
                'name' => __('enum.' . $langKey . '.' . strtolower($item->getKey())),
                'value' => $item->getValue(),
            ];
        }

        return $data;
    }
}

if (!function_exists('binding_collection')) {
    function binding_collection($collection)
    {
        $data = [];

        foreach ($collection as $item) {
            $data[] = [
                'text' => $item->name,
                'name' => $item->name,
                'value' => $item->id,
            ];
        }

        return $data;
    }
}

if (!function_exists('binding_array')) {
    function binding_array($items)
    {
        $data = [];

        foreach ($items as $item) {
            $data[] = $item->id;
        }

        return $data;
    }
}

if (!function_exists('admin_avaiable_roles')) {
    function admin_avaiable_roles($admin = null, $roles = null)
    {
        if (empty($admin)) {
            $admin = admin();
        }
        if (empty($roles)) {
            $roles = Role::whereGuard('admin')->get();
        }

        $data = [];
        $highestLevel = null;

        foreach ($admin->roles as $role) {

            if ($highestLevel === null) {
                $highestLevel = $role->level;
                continue;
            }

            if ($role->level < $highestLevel) {
                $highestLevel = $role->level;
            }
        }

        foreach ($roles as $role) {

            if ($role->level >= $highestLevel) {
                $data[] = $role;
            }
        }

        return $data;
    }
}

if (!function_exists('binding_meta_seo')) {
    function binding_meta_seo($data, $route)
    {
        $meta = new StdClass();
        $meta->name = $data->name ?? '';
        $meta->tag = $data->tag ?? $meta->name;
        $meta->description = $data->short_description ?? '';
        $meta->image = $data->image ?? '';
        $meta->url = $route;

        return $meta;
    }
}


if (!function_exists('generateImei')) {
    function generateImei()
    {
        return Str::upper(Str::random(8) . '-' . Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4) . '-' . Str::random(12));
    }
}


if (!function_exists('generateToken')) {
    function generateToken()
    {
        return Str::random(20);
    }
}
if (!function_exists('isJson')) {
    function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
