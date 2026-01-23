<?php

namespace App\Http\Middleware;

use App\Models\Local\Leads\Document;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckForContract
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $excludedRoutes = ['leads.contract.generate', 'leads.contract.signing', 'leads.contract.complete'];

        $route = $request->route();

        if ($route) {
            $routeName = $route->getName(); // e.g. leads.show
            $id = $route->parameter('id');  // route param

            if (
                !in_array($routeName, $excludedRoutes) &&
                $routeName &&
                str_starts_with($routeName, 'leads.') &&
                $id
            ) {
                $lead = Auth::user()->leads->where('rep', Auth::user()->user_id)->where('id', $id)->firstOrFail();

                if ($liveSigningDocument = Document::where('lead_id', $id)->where('status', '!=', 'document.completed')->first())
                    return redirect()->route('leads.contract.generate', ['id' => $lead->id, 'uuid' => $liveSigningDocument->uuid]);
            }
        }

        return $next($request);
    }
}
