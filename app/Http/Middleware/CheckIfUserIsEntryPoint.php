<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class CheckIfUserIsEntryPoint
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
		&& $user->role->name != "Private" && $user->role->name !="Injuires"){
			 return redirect()->guest('auth/login');
		}
        return $next($request);
    }
}
