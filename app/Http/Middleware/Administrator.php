<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Auth\Guard;


class Administrator
{
    protected $auth;

    public function __construct(Guard $auth) {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission = null): Response {
        /*
        As shown i checked for the current user is_admin property, if it equal to 1 then it’s allowed,
        otherwise we check if the user has a permission for the current route action,
        as you see i send a parameter to the function called permission,
        this parameter will be passed inside each controller you want to authorize access to it.
        */

        if ($this->auth->user()->is_admin) {
            return $next($request);
        } else {
            if ($permission != null && ($permission_parts = explode("|", $permission))) {
                $action_permission_array = array_filter(
                    $permission_parts,
                    function ($value) use ($request) {
                        return ($parts = explode("-", $value)) && $parts[0] == $request->route()->getActionMethod();
                    }
                );
                // dd($action_permission_array);

                if ($action_permission_array && count($action_permission_array)) {
                    $action_permission = array_pop($action_permission_array);
                    $parts = explode("-", $action_permission);

                    /** @var \App\Models\User $user */
                    $user = $this->auth->user();

                    if (isset($parts[1]) && $user->can($parts[1])) {
                        return $next($request);
                    }
                }
            }
        }
        return redirect('/admin/forbidden');
    }
}
