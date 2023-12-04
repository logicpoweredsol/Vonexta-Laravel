<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;

class checkAuth
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
        // dd($request->path());
        if(!session()->has('user') && ($request->path() !='login')){
            dd("LL");
            return redirect('login')->with('fail','You must be logged in');
        }
        // dd(request()->headers->get('referer'));
        if(session()->has('user') && ($request->path() == 'login'/*  || $request->path() == 'auth/register' */ ) ){
            
            $credentials = [
                'user'=>session()->get('user')->user,
                'pass'=>session()->get('user')->pass
            ];
            $user = User::where($credentials)->first();
            if(!$user){
                session()->flush();
                return redirect('login')->with('fail', 'Your must be logged in to continue');
            }
            $request->session()->put('user', $user);
            dd(env('APP_URL'));
            if(request()->headers->get('referer')== env('APP_URL')){
                return redirect()->route('dashboard');
            }
            dd(request()->headers->get('referer'));
            return back();
        }
        return $next($request)
        ->header('Cache-Control','no-cache, no-store, max-age=0, must-revalidate')
        ->header('Pragma','no-cache')
        ->header('Expires','Sat 01 Jan 1990 00:00:00 GMT+5');;
    }
}
