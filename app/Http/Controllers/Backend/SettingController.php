<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SettingController extends Controller
{
    public function index()
    {
        $settingTable = Setting::all();
        $configs = [];
        foreach ($settingTable as $item){
            $configs[$item['name']] = $item['val'];
        }
        return view('backend.config.index',
            [
                'page_title' => 'Cấu hình chung',
                'configs'=>$configs
            ]);
    }

    public function update(Request $request)
    {
        Setting::query()->truncate();
        foreach ($request->all() as $key => $config) {
            if ($key == '_method' || $key == '_token') {
                continue;
            }
            Setting::query()->create([
                'name' => $key,
                'val' => $config
            ]);
        }
        Session::put('message', 'Đã cập nhật thay đổi');
        return back();
    }

    public function contactUs()
    {
        $settings = Setting::all();
        return view('frontend.layout.contact', compact('settings'));
    }
}
