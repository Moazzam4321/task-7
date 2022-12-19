<?php

namespace App\Http\Middleware;

use App\Models\Client;
use Closure;

class VerifiedEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {

        $user=Client::where('email',$request->email)->first();
        
        $gets= $user->email_verified_at;
        if($gets!=""){
           // return $next($request);
            return response('Email not verified. First go to ur gmail for verify link then login'. $user);
        }
        else{
           //dd($request->all());
        return response('Email not verified. First go to ur gmail for verify link then login'. $request->all());
        }
    }

}
