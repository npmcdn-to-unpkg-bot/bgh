<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Session;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
class PolicyController extends Controller
{
    /**
     * Terms and services
     *
     * @return mixed
     */
    public function getTos()
    {
        $title = t('Terms of Services');

        return view('policy.tos', compact('title'));
    }

    /**
     * Privacy Policies
     *
     * @return mixed
     */
    public function getPrivacy()
    {
        $title = t('Privacy Policy');

        return view('policy.privacy', compact('title'));
    }

    /**
     * Faq of the site
     *
     * @return mixed
     */
    public function getFaq()
    {
        $title = t('FAQ');

        return view('policy.faq', compact('title'));
    }

    /**
     * About us
     *
     * @return mixed
     */
    public function getAbout()
    {
        $title = t('About Us');

        return view('policy.about', compact('title'));
    }

    /**
     * @param $lang
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchLang($lang)
    {
        Session::forget('my.locale');
        if (in_array($lang, languageArray())) {
            Session::put('my.locale', $lang);
            return redirect()->route('home');
        }
        Session::put('my.locale', 'en');

        return redirect()->route('home');
    }

    /**
     * @return mixed
     */
    public function queue()
    {
        return Queue::marshal();
    }
}
