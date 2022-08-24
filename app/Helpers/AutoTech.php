<?php

namespace App\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
//chỉ được dùng tài khoản VND-TGTT
class AutoTech
{

    private static $instance = null;
    public $account_number;
    protected $username;
    protected $password;
    protected $imei;
    protected $value;
    protected $customerId;
    protected $account_holder;
    protected $paymentInstrumentId;
    protected $id;
    protected $bankUrl;
    protected $cookie;
    protected $stkName;
    private $applicationVersion = "1.3.0.0";

    public function __construct(string $username, string $password, string $imei, string $account_number = null , string $stkName = null)
    {
        $this->bankUrl = 'https://mob.techcombank.com.vn/mobiliser/rest/smartphone/';
        $this->username = $username;
        $this->password = $password;
        $this->stkName = $stkName;
        $this->imei = $imei;
        $this->account_number = $account_number;
        $this->value = Str::random(64);
        $this->cookie = Storage::path($username . '.txt');
    }

    private function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public function login()
    {
        $data = [
            'origin' => 'MAPP',
            'traceNo' => $this->traceNo(),
            'AuditData' => [
                'device' => 'iOS/14.4.2 iPhone12,5',
                'deviceId' => $this->imei,
                'otherDeviceId' => '5.1.1',
                'application' => 'MAPP',
                'applicationVersion' => $this->applicationVersion,
            ],
            'identification' => $this->username,
            'credential' => $this->password,
            'identificationType' => '0',
            'credentialType' => '0',
            'UnstructuredData' => [
                0 => ['Key' => 'DeviceToken', 'Value' => '[ios]' . $this->value],
                1 => ['Key' => 'DeviceTime', 'Value' => $this->getMicroTime()],
                2 => ["Key" => "stkName", "Value" => $this->stkName]
            ],
        ];
        $result = $this->curl(
            $this->bankUrl . 'loginTcbCustomer?uname=' . $this->username . '&aid=' . $this->imei,
            $data
        );
        if ($this->isJson($result)) {
            $response = json_decode($result, true);
            if (isset($response['Status']['code']) && $response['Status']['code'] === 0) {
                $this->id = $response['customer']['id'];
                $this->customerId = $response['customer']['id'];
                $this->account_holder = $response['customer']['displayName'];
                $this->getInfo();
                return ['status' => true, 'data' => $response, 'message' => 'Thành công!', 'value' => $this->value];
            }
            return ['status' => false, 'data' => [], 'message' => $response['Status']['value']];
        }
        return ['status' => false, 'data' => [], 'message' => 'Hệ thống Techcombank quá tải! Quý khách vui lòng thử lại sau!'];
    }

    public function traceNo(): string
    {
        $time = md5($this->getMicroTime() . '000000');
        $text = substr($time, 0, 8) . '-';
        $text .= substr($time, 8, 4) . '-';
        $text .= substr($time, 12, 4) . '-';
        $text .= substr($time, 16, 4) . '-';
        $text .= substr($time, 17, 12);
        return $text;
    }

    public function getMicroTime()
    {
        return floor(microtime(true) * 1000);
    }

    public function curl($url = null, $param = [], $cookie = true, $token = '')
    {
        $response = null;
        $header[] = 'Host: fmb.techcombank.com.vn:443';
        $header[] = 'Accept: application/json';
        $header[] = 'Content-Type: application/json';
        $header[] = 'Connection: keep-alive';
        $header[] = 'access-control-allow-credentials: true';
        $header[] = 'Accept-Language: vi-vn';
        $header[] =
            'User-Agent: F%40st%20Mobile/1.3.0.0 CFNetwork/1325.0.1 Darwin/21.1.0';
        if ($token) {
            $header[] = 'Authorization: Bearer ' . trim($token);
        }
        if (!empty($url)) {
            $ch = curl_init($url);
            //curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 120);

            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            if ($cookie) {
                curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);
                curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie);
            }
            if (count($param) > 0) {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));
            }
            if (curl_errno($ch)) {
                $response = 'Lỗi: ' . curl_error($ch);
            } else {
                $response = curl_exec($ch);
            }
            curl_close($ch);
        }
        return $response;
    }

    public function getInfo()
    {
        $getWalletEntriesByCustomer = [
            'origin' => 'MAPP',
            'traceNo' => $this->traceNo(),
            'AuditData' => [
                'device' => 'iOS/14.4.2 iPhone12,5',
                'deviceId' => $this->imei,
                'otherDeviceId' => '5.1.1',
                'application' => 'MAPP',
                'applicationVersion' => $this->applicationVersion,
            ],
            'customerId' => $this->customerId,
        ];

        $result = $this->curl($this->bankUrl . 'getWalletEntriesByCustomer', $getWalletEntriesByCustomer);
        if ($this->isJson($result)) {
            $response = json_decode($result, true);
            if (isset($response['walletEntries'])) {
                $info = collect($response['walletEntries']);
                if(isset($info[0]['bankAccount']['accountNumber'])){
                    $info = $info[0];
                }else{
                    $info = $info[1];
                }
                $this->id = $info['customerId'];
                $this->paymentInstrumentId = $info['paymentInstrumentId'];
                return ['status' => true, 'data' => $info, 'message' => 'Thành công!'];
            }
            return ['status' => false, 'data' => [], 'message' => $response['Status']['value']];
        }

        return ['status' => false, 'data' => [], 'message' => 'Có Lỗi Xảy Ra,Thử Lại Sau Ít Phút.'];
    }

    public function getHistories(int $fromDate = null, int $toDate = null, int $maxRecords = 10)
    {
        $fromDate = $fromDate ?: date('Ymd', strtotime('yesterday'));
        $toDate = $toDate ?: date('Ymd', strtotime('tomorrow'));
        $data = [
            'origin' => 'MAPP',
            'traceNo' => $this->traceNo(),
            'AuditData' => [
                'device' => 'iOS/14.4.2 iPhone12,5',
                'deviceId' => $this->imei,
                'otherDeviceId' => '5.1.1',
                'application' => 'MAPP',
                'applicationVersion' => $this->applicationVersion,
            ],
            'maxRecords' => $maxRecords,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'paymentInstrumentId' => $this->paymentInstrumentId,
        ];

        $result = $this->curl($this->bankUrl . 'findTcbTransactions', $data);
        if ($this->isJson($result)) {
            $response = json_decode($result, true);
            return (isset($response['Status']['code']) && $response['Status']['code'] === 0) ?
                ['status' => true, 'data' => $this->formatTransaction($response['transactions']), 'message' => 'Thành công!'] :
                ['status' => false, 'data' => [], 'message' => 'Có lỗi xảy ra vui lòng thử lại sau'];
        }
        return ['status' => false, 'data' => [], 'message' => 'Server Techcombank quá tải. Vui lòng thử lại sau.'];
    }

    public function formatTransaction(array $transactions)
    {
        $histories = [];
        $transactions = collect($transactions)->reverse()->toArray();

        foreach ($transactions as $transaction) {
            $histories = Arr::prepend($histories, [
                'amount' => $transaction['txnAmount'],
                'content' => $transaction['txnDesc'],
                'date' => $transaction['txnDate'],
                'trans_id' => $transaction['coreBankTxnId'],
                'is_receive' => $transaction['txnAmount'] > 0
            ]);
        }

        return $histories;
    }

    public function getBankList247()
    {
        $getBankList247 = [
            'origin' => 'MAPP',
            'traceNo' => $this->traceNo(),
            'AuditData' => [
                'device' => 'iOS/14.4.2 iPhone12,5',
                'deviceId' => $this->imei,
                'otherDeviceId' => '5.1.1',
                'application' => 'MAPP',
                'applicationVersion' => $this->applicationVersion,
            ],
        ];
        $response = $this->curl(
            $this->bankUrl . 'getBankList247',
            $getBankList247
        );
        if ($this->isJson($response)) {
            $bankList = json_decode($response, true);
            return ((isset($bankList['Status']['code']) && $bankList['Status']['code'] === 0)) ? ['status' => true, 'data' => $bankList['BankList'], 'message' => 'Thành công!']
                : ['status' => false, 'data' => [], 'message' => 'Có Lỗi Xảy Ra,Thử Lại Sau Ít Phút.'];
        }
        return ['status' => false, 'data' => [], 'message' => 'Server Techcombank quá tải. Vui lòng thử lại sau.'];
    }

    public function getAccountName($AccountNumber, $BankID = null)
    {

        $url = $this->bankUrl . 'acctInquiry247?BankID=' . $BankID;
        $data = [
            'origin' => 'MAPP',
            'traceNo' => $this->traceNo(),
            'AuditData' => [
                'device' => 'iOS/14.4.2 iPhone12,5',
                'deviceId' => $this->imei,
                'otherDeviceId' => '5.1.1',
                'application' => 'MAPP',
                'applicationVersion' => $this->applicationVersion,
            ],
            'AccountNumber' => $AccountNumber,
            'BankID' => $BankID
        ];

        $result = $this->curl($url, $data);
        if ($this->isJson($result)) {
            $response = json_decode($result, true);
            return (isset($response['Status']['code']) && $response['Status']['code'] === 0) ?
                ['status' => true, 'data' => $response, 'message' => 'Thành công!'] :
                ['status' => false, 'data' => [], 'message' => 'Có Lỗi Xảy Ra,Thử Lại Sau Ít Phút.'];
        }
        return ['status' => false, 'data' => [], 'message' => 'Server Techcombank quá tải. Vui lòng thử lại sau.'];
    }

    public function createTransfer247($BankID, $AccountNumber, $amount, $msg)
    {
        $id = $this->getAccountName($AccountNumber, $BankID);
        if ($id['status']) {
            $tp = [
                'origin' => 'MAPP',
                'traceNo' => $this->traceNo(),
                'AuditData' => [
                    'device' => 'iOS/14.4.2 iPhone12,5',
                    'deviceId' => $this->imei,
                    'otherDeviceId' => '5.1.1',
                    'application' => 'MAPP',
                    'applicationVersion' => $this->applicationVersion,
                ],
                'autoCapture' => 'true',
                'orderChannel' => '0',
                'usecase' => '1019',
                'Payer' => [
                    'identifier' => ['type' => '1', 'value' => $this->id],
                    'paymentInstrumentId' => $this->paymentInstrumentId,
                ],
                'Payee' => [
                    'identifier' => ['type' => '5', 'value' => 'Techcombank'],
                    'paymentInstrumentId' => '1000317',
                ],
                'Amount' => ['currency' => 'VND', 'vat' => '0', 'value' => $amount],
                'Text' => $msg,
                'attribute' => [
                    0 => ['key' => '515', 'value' => $AccountNumber],
                    ['key' => '516', 'value' => $BankID],
                ],
                "UnstructuredData" => [
                    0 => ["Key" => "stkName", "Value" => $this->stkName],
                    ["Key" => "QueryID", "Value" => $id['data']['QueryID']],
                ],
            ];

            $response = $this->curl(
                $this->bankUrl . 'preAuthorisation',
                $tp
            );

            Log::debug('Create 247 Transfer', ['response' => $response]);

            if ($this->isJson($response)) {
                $dataTransfer = json_decode($response, true);

                return (isset($dataTransfer['Status']['code']) && $dataTransfer['Status']['code'] === 0) ?
                    $this->step2Transfer($dataTransfer['Transaction']['systemId']) :
                    ['status' => false, 'data' => [], 'message' => $dataTransfer['Status']['value']];
            }
            return ['status' => false, 'data' => [], 'message' => 'Server Techcombank quá tải. Vui lòng thử lại sau.'];
        }
        return ['status' => false, 'data' => [], 'message' => 'Server Techcombank quá tải. Vui lòng thử lại sau.'];
    }

    private function step2Transfer($systemId)
    {
        $data = [
            'origin' => 'MAPP',
            'traceNo' => $this->traceNo(),
            'AuditData' => [
                'device' => 'iOS/14.4.2 iPhone12,5',
                'deviceId' => $this->imei,
                'otherDeviceId' => '5.1.1',
                'application' => 'MAPP',
                'applicationVersion' => $this->applicationVersion,
            ],
            'ReferenceTransaction' => ['systemId' => $systemId],
        ];
        $response = $this->curl(
            $this->bankUrl . 'preAuthorisationContinue',
            $data
        );

        Log::debug('Step 2 Transfer', ['response' => $response]);

        return ($response) ?
            ['status' => true, 'data' => json_decode($response, true), 'message' => 'Thành công!'] :
            ['status' => false, 'data' => [], 'message' => 'Có Lỗi Xảy Ra,Thử Lại Sau Ít Phút.'];
    }

    public function createTechTranfer($AccountNumber, $amount, $msg)
    {

        $getReceiver = $this->getTechAccountName($AccountNumber);
        $receiverPaymentInstrumentId = ($getReceiver['status']) ? $getReceiver['data']['id'] : null;

        $data = [
            'origin' => 'MAPP',
            'traceNo' => $this->traceNo(),
            'AuditData' =>
                [
                    'device' => 'iOS/14.4.2 iPhone12,5',
                    'deviceId' => $this->imei,
                    'otherDeviceId' => '5.1.1',
                    'application' => 'MAPP',
                    'applicationVersion' => '1.2.1.1',
                ],
            'autoCapture' => 'true',
            'orderChannel' => '0',
            'usecase' => '1002',
            'Payer' =>
                [
                    'identifier' =>
                        [
                            'type' => '1',
                            'value' => $this->customerId,
                        ],
                    'paymentInstrumentId' => $this->paymentInstrumentId,
                ],
            'Payee' =>
                [
                    'identifier' =>
                        [
                            'type' => '1',
                            'value' => '1000',
                        ],
                    'paymentInstrumentId' => $receiverPaymentInstrumentId,
                ],
            'Amount' =>
                [
                    'currency' => 'VND',
                    'vat' => '0',
                    'value' => $amount,
                ],
            'Text' => $msg,
            "UnstructuredData" => [["Key" => "stkName","Value" => $this->stkName]],
        ];


        $response = $this->curl(
            $this->bankUrl . 'preAuthorisation',
            $data
        );

        Log::debug('Create Tech Transfer', ['response' => $response]);

        if ($this->isJson($response)) {
            $dataTransfer = json_decode($response, true);

            return (isset($dataTransfer['Status']['code']) && $dataTransfer['Status']['code'] === 0) ?
                $this->step2Transfer($dataTransfer['Transaction']['systemId']) :
                ['status' => false, 'data' => [], 'message' => $dataTransfer['Status']['value']];
        }
        return ['status' => false, 'data' => [], 'message' => 'Server Techcombank quá tải. Vui lòng thử lại sau.'];
    }

    public function getTechAccountName($AccountNumber)
    {
        $url = $this->bankUrl . 'getPaymentInstrument';
        $data = [
            'origin' => 'MAPP',
            'traceNo' => $this->traceNo(),
            'AuditData' => [
                'device' => 'iOS/14.4.2 iPhone12,5',
                'deviceId' => $this->imei,
                'otherDeviceId' => '5.1.1',
                'application' => 'MAPP',
                'applicationVersion' => $this->applicationVersion,
            ],
            "UnstructuredData" =>
                [
                    0 => [
                        "Key" => "TcbBankAccount",
                        "Value" => $AccountNumber
                    ]
                ],
            "paymentInstrumentId" => "0",
            "includeInactive" => "false"
        ];
        $result = $this->curl($url, $data);

        if ($this->isJson($result)) {
            $response = json_decode($result, true);
            if (isset($response['Status']['code']) && $response['Status']['code'] === 0) {
                $data = [
                    'id' => $response['PaymentInstrument']['id'],
                    'AccountName' => $response['PaymentInstrument']['accountHolderName'],
                    'AccountNumber' => $response['PaymentInstrument']['accountNumber'],
                    'BankID' => $response['PaymentInstrument']['bankCode'],
                ];
                return ['status' => true, 'data' => $data, 'message' => 'Thành công!'];
            }

            return ['status' => false, 'data' => [], 'message' => 'Có Lỗi Xảy Ra,Thử Lại Sau Ít Phút.'];
        }
        return ['status' => false, 'data' => [], 'message' => 'Server Techcombank quá tải. Vui lòng thử lại sau.'];
    }

    public function confirmOtpTransfer($otp, $systemId)
    {
        $data = [
            'origin' => 'MAPP',
            'traceNo' => $this->traceNo(),
            'AuditData' => [
                'device' => 'iOS/14.4.2 iPhone12,5',
                'deviceId' => $this->imei,
                'otherDeviceId' => '5.1.1',
                'application' => 'MAPP',
                'applicationVersion' => $this->applicationVersion,
            ],
            'Credential' => ['payer' => $otp],
            'ReferenceTransaction' => ['systemId' => $systemId],
        ];

        $response = $this->curl(
            $this->bankUrl . 'authenticationContinue?uname=' .
            $this->username .
            '&aid=' .
            $this->imei,
            $data
        );


        return ($response) ?
            ['status' => true, 'data' => json_decode($response, true), 'message' => 'Thành công!'] :
            ['status' => false, 'data' => [], 'message' => 'Có Lỗi Xảy Ra,Thử Lại Sau Ít Phút.'];
    }
    public function __destruct(){
        unlink($this->cookie);
    }
}
