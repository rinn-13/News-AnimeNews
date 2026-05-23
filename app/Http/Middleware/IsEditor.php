<?php
// app/Http/Middleware/IsEditor.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsEditor
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!in_array(auth()->user()->role, ['admin', 'editor'])) {
            abort(403, 'Unauthorized access. Editor or Admin role required.');
        }

        return $next($request);
    }
}