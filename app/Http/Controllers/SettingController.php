<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $value = 0;
        if($request->setting == "true")
            $value = 1;
        else
            $value = 0;

        $result = [];
        try {
            $setting = Setting::where('type', 'notification')->delete();
            Setting::create([
                'type' => 'notification',
                'value' => $value
            ]);
            $result = [
                'success' => true,
                'config' => $value
            ];
        } catch(Exception $e) {
            $result = [
                'success' => false,
                'config' => $value
            ];
        }
        return $result;
    }
}
