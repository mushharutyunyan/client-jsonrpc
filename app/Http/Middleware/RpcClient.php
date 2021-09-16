<?php

namespace App\Http\Middleware;

use App\Jobs\LogActivity;
use App\Services\JsonRpcClient;
use Closure;
use Illuminate\Http\Request;

class RpcClient
{
    protected $client;

    public function __construct(JsonRpcClient $client)
    {
        $this->client = $client;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Focus on route_method_name == page_name
        $pageName = $request->route()->getActionMethod();
        LogActivity::dispatch([
            'page' => $pageName
        ]);
        return $next($request);
    }
}
