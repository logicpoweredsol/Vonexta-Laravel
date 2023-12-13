<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Auth::check()) {
            // Check the user's status
            if (Auth::user()->active == 0) {
                $userName = Auth::user()->name;
                Auth::logout(); // Log out the user
         

                return redirect('/login')->withErrors([
                    'account_disabled' => "Your account ($userName) is disabled. Please contact the administrator.",
                ]);

                // return redirect('/login')->withErrors([
                //     $user_name => 'Your account is disabled. Please contact the administrator.',
                // ]);
            }
        }

        return $next($request);

        
    }
}
