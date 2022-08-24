<?php

namespace App\Http\Controllers;

class IndexController extends Controller
{
    public function index()
    {
        echo "Hello word!";
    }


//    public function autoCallback()
//    {
//        $history = HistoryTransfer::first();
//
//        $data =  [
//            "trans_id" => $history->trans_id,
//            "system_id" => $history->system_id,
//            "amount" => $history->amount,
//            "status" => $history->status,
//            "sign" => md5($history->trans_id . $history->system_id . $history->amount . $history->status)
//        ];
//        $response =  Http::post('https://autobank.web/api/test-callback', $data);
//        Log::info('Callback ' .$history->trans_id, $data);
//        Log::info('Callback ' .$history->trans_id, [ 'status' => $response->status(), 'data' => $response->json() ?: $response->body()]);
//    }
//
//    public function testCallback(Request $request)
//    {
//        return response()->json('ok');
//    }
}
