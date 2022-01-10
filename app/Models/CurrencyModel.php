<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;

class CurrencyModel extends Model
{
    use HasFactory;

    private $_is_error;
    private $_message;
    private $_stype;  // 原始幣別
    private $_ttype;  // 目標幣別
    private $_amount; // 原始金額
    private $_tmp_target_amount;
    private $_extra_data; // 匯率資料

    /**
     * 建立一個物件
     *
     * @param  string $message 錯誤訊息
     * @return CurrencyModel
     */
    public static function GetInstance($data = [], $extra_data = null)
    {
        $model = new CurrencyModel;
        $model->reset();
        $model->_stype = data_get($data, 'source_currency', '');
        $model->_ttype = data_get($data, 'target_currency', '');
        $model->_amount = data_get($data, 'source_amount', 0);
        $model->_extra_data = $extra_data;
        return $model;
    }

    /**
     * 建立一個錯誤物件
     *
     * @param  string $message 錯誤訊息
     * @return CurrencyModel
     */
    public static function Error($message = '')
    {
        $model = new CurrencyModel;
        $model->_is_error = true;
        $model->_message = $message;
        return $model;
    }

    protected $appends = ['target', 'source', 'success', 'message'];

    protected function reset()
    {
        $this->_is_error = false;
        $this->_message = null;
        $this->_tmp_target_amount = null;
    }

    /**
     * 設定新的金額
     *
     * @param  mixed $value
     * @return void
     */
    public function setAmountAttribute($value)
    {
        $this->reset();
        $this->_amount = $value;
    }

    /**
     * 設定新的匯率資料
     *
     * @param  mixed $value
     * @return void
     */
    public function setExtraDataAttribute($value)
    {
        $this->reset();
        $this->_extra_data = $value;
    }

    /**
     * 回應是否成功
     *
     * @return bool
     */
    public function getSuccessAttribute() : bool
    {
        return !$this->_is_error;
    }

    /**
     * 回應訊息
     *
     * @return string
     */
    public function getMessageAttribute()
    {
        return $this->_message;
    }

    /**
     * 來源幣資訊
     *
     * @return array|null
     */
    public function getSourceAttribute()
    {
        return ($this->_is_error) ? null : [
            'amount' => (double)$this->_amount,
            'currency' => strtoupper($this->_stype),
            'format' => number_format($this->_amount, 2, '.', ','),
        ];
    }

    /**
     * 目標幣資訊
     *
     * @return array|null
     */
    public function getTargetAttribute()
    {
        if(($this->_is_error))
            return null;

        try {
            $target_amount = $this->convertAmount();
            return [
                'amount' => ($target_amount) ?: '',
                'currency' => strtoupper($this->_ttype),
                'format' => ($target_amount) ? number_format($target_amount, 2, '.', ',') : '',
            ];
        }
        catch(Exception $err) {
            $this->_is_error = true;
            $this->_message = $err->getMessage();
        }
        return null;
    }

    /**
     * 將來源幣轉為目標幣，或回傳 null
     *
     * @return double|null
     */
    public function convertAmount()
    {
        if($this->_tmp_target_amount)
            return $this->_tmp_target_amount;

        if($this->_is_error !== true) {
            $this->_is_error = false;
            $this->_tmp_target_amount = null;
            if($this->_extra_data) {
                // 取得來源幣別匯率資料
                $currency = data_get($this->_extra_data, 'currencies.'.$this->_stype, false);
                if($currency) {
                    // 取得目標幣別目前匯率
                    $rate = data_get($currency, $this->_ttype, 0);
                    if($rate > 0) {
                        // 儲存目標幣金額，防止重覆運算
                        $this->_tmp_target_amount = $this->_amount * $rate;
                    }
                }
            }
            if(!$this->_tmp_target_amount)
                throw new Exception(__('No currency convert data from :source to :target.', [
                    'source' => $this->_stype,
                    'target' => $this->_ttype,
                ]));
        }

        return $this->_tmp_target_amount;
    }

    public function toArray()
    {
        return array_filter(parent::toArray(), function ($val) {
            return !is_null($val);
        });
    }
}
