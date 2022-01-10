<?php

namespace App\Services\ExtraCurrency;

use App\Services\ExtraCurrency\Contracts\Factory;
use Exception;

class ExtraCurrency implements Factory
{
    /**
     * 取得匯率資料
     *
     * @return mixed
     */
    public function all() : mixed
    {
        if(!$url = config('app.extra_currency.api_url'))
            throw new Exception('請先設定環境變數 EXTRA_CURRENCY_API_URL');

        if($url == 'https://currency.extra.url') {
            return (new ExtraCurrencyMock)->all();
        }
        else {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ]);
            $response = curl_exec($curl);
            curl_close($curl);
            return json_decode($response);
        }
    }
}
