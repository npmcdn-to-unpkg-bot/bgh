<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function getSiteDetails()
    {
        $settings = DB::table('sitesettings')->get();
        $title = 'Site Details/Info Settings';

        return view('admin.settings.details', compact('settings', 'title'));
    }

    public function getLimitSettings()
    {
        $title = 'Limit Settings';

        return view('admin.settings.limits', compact('title'));
    }

    public function getCacheSettings()
    {
        return view('admin.settings.cache')->with('title', 'Cache Settings');
    }
}
