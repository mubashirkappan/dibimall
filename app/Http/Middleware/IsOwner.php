<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->user_type ==2) {
            return $next($request);
        }
    
        // If the user is not the owner, redirect them back to the previous page
        return redirect()->back()->withErrors(['message' => 'Unauthorized action.']);
     
    }
}
