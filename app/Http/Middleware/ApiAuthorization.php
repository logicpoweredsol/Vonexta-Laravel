<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Route;
use Closure;
use Illuminate\Http\Request;

use App\Models\authorization_key as AuthorizationKey;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('authkey')) {
            // Retrieve the key from the URL
            $apiKey = $request->authkey;
            // Validate the key against your database
            $validKey = $this->validateKey($apiKey);

            if ($validKey) {
                // Key is valid, allow the request to proceed
                return $next($request);
            } else {
                // Key is invalid, return an authorization error response
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } else {
            // 'key' parameter is not present, return an authorization error response
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // return $next($request);
    }


    private function validateKey(string $apiKey): bool
    {
        $matchFound = false;
        $check_key = AuthorizationKey::where('key',$apiKey)->with('associatedPath')->first();
        if($check_key != "" && $check_key != null){
            $currentRoute = Route::current();
            $currentUrl = $currentRoute->uri();
            foreach ($check_key->associatedPath as $keyRule) {
                if ($keyRule->path === $currentUrl) {
                    $matchFound = true;
                    break;
                }
            }
        }
        return $matchFound; 
          
        
    }

}
