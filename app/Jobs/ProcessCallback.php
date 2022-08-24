<?php

namespace App\Jobs;

use App\Models\HistoryTransfer;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessCallback implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $history;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(HistoryTransfer $history)
    {
        $this->history = $history;
        Log::info('InfoCallback ' . $this->history->callback_url, $history->toArray());
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = [
            "trans_id" => $this->history->trans_id,
            "system_id" => $this->history->system_id,
            "amount" => $this->history->amount,
            "status" => $this->history->status,
            "message" => $this->history->message,
            "sign" => md5($this->history->trans_id . $this->history->system_id . $this->history->amount . $this->history->status . $this->history->message)
        ];
        $client = new Client(['http_errors' => false]);
        $res = $client->request('POST', $this->history->callback_url, [
            'timeout' => 15,
            'headers' => array(
                'content-type' => 'application/json',
                'lang' => 'vi',
                'user-agent' => 'AutoBankSytem'
            ),
            'body' => json_encode($data),
        ]);

        Log::info('Callback ' . $this->history->callback_url, ['response' => $res->getBody()->getContents()]);
    }
}
