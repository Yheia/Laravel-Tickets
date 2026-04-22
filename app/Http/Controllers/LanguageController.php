<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(string $locale)
    {
        if (in_array($locale, config('app.supported_locales'))) {
            Session::put('locale', $locale);
        }

        return redirect()->back();
    }
}