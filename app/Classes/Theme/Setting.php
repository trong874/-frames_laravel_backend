<?php
namespace App\Classes\Theme;

use App\Models\Setting as SettingAlias;

class Setting {
    public static function configs($name)
    {
        return SettingAlias::query()->where('name',$name)->first()['val'];
    }
}
