<?php

namespace App\Http\Middleware;

use App\Enums\BankStatus;
use App\Models\Bank;
use Closure;
use Illuminate\Http\Request;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $bank = Bank::whereToken($request->token)->whereStatus(BankStatus::ENABLE)->first();
        if(!$bank){
            return response()->json(['error' => __('auth.token_not_found')], 403);
        }
        return $next($request);
    }
}
