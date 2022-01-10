<?php

namespace App\Services\ExtraCurrency\Contracts;

interface Factory
{
    /**
     * 取得匯率資料
     *
     * @return mixed
     */
    public function all() : mixed;
}
