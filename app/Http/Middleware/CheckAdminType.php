<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$types): Response
    {
        $admin = Auth::guard('admin')->user(); // assuming you're using an 'admin' guard

        if ($admin && (in_array($admin->type, $types) || $admin->type === 'Super Admin')) {

            return $next($request);
        }

        return redirect()->back();
    }
}
