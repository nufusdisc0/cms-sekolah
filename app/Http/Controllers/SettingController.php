<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index($group = 'general')
    {
        $settings = Setting::where('setting_group', $group)->get()->keyBy('setting_variable');


        return view('backend.settings.' . $group, compact('settings', 'group'));
    }

    public function update(Request $request, $group)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            // Handle file uploads specially
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                // In CI3, these went to ./media_library/images
                // Let's store them in public/media_library/images
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('media_library/images'), $fileName);
                $value = $fileName;
            }

            Setting::updateOrCreate(
            ['setting_group' => $group, 'setting_variable' => $key],
            ['setting_value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
