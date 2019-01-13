<?php

namespace App\Http\Middleware;

use Closure;

class CheckIfUserIsPrivate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		$user=User::find(Auth::id());
       
		if($user->role->name != "Receiption" && $user->role->name != "Desk"  && $user->role->name != "Entrypoint" && $user->role->name != "GeneralRecept" 
		&& $user->role->name != "Private" && $user->role->name != "Injuires"){
			 return redirect()->guest('auth/login');
		}
        return $next($request);
    }
}
