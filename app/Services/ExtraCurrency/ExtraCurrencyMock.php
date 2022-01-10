<?php

namespace App\Services\ExtraCurrency;

use App\Services\ExtraCurrency\Contracts\Factory;

class ExtraCurrencyMock implements Factory
{
    /**
     * 取得匯率資料
     *
     * @return mixed
     */
    public function all() : mixed
    {
        $response = <<<Html
{
    "currencies": {
        "TWD": {
            "TWD": 1,
            "JPY": 3.669,
            "USD": 0.03281
        },
        "JPY": {
            "TWD": 0.26956,
            "JPY": 1,
            "USD": 0.00885
        },
        "USD": {
            "TWD": 30.444,
            "JPY": 111.801,
            "USD": 1
        }
    }
}
Html;
        return json_decode($response);
    }
}
