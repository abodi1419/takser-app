<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = 'ar';
        if (session()->has('locale')) {
            Cookie::queue('locale', session()->get('locale'), 60 * 24 * 365);
            $locale = session()->get('locale');
        }else if(Cookie::get('locale')){
            $locale = Cookie::get('locale');
        }
        App::setlocale($locale);

        return $next($request);
    }
}
