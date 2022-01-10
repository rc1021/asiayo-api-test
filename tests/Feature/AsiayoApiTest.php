<?php

namespace Tests\Feature;

use App\Models\CurrencyModel;
use App\Services\ExtraCurrency\ExtraCurrencyMock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AsiayoApiTest extends TestCase
{
    private $_api_path = '/api/v1/currency?';

    private $_extral_url = 'https://currency.extra.url';

    private $_assert_data = [
        'correct' =>[
            "target" => [
                "amount" => 255.91799999999998,
                "currency" => "USD",
                "format" => "255.92"
            ],
            "source" => [
                "amount" => 7800,
                "currency" => "TWD",
                "format" => "7,800.00"
            ],
            "success" => true
        ],
        'not_numeric' => [
            "success" => false,
            "message" => "The source amount is not a numeric value"
        ],
        'not_numeric2' => [
            "success" => false,
            "message" => "A non-numeric value encountered"
        ],
        'no_covert' => [
            "success" => false,
            "message" => "No currency convert data from TWD2 to USD."
        ],
    ];

    private $_params = [
        'source_amount' => 7800, // ⾦額數字
        'source_currency' => 'TWD', // 來源幣別
        'target_currency' => 'USD', // ⽬標幣別
    ];

    /**
     * 驗證一個 Error 物件
     *
     * @return void
     */
    public function test_error()
    {
        $error = '錯誤訊息';
        $response = CurrencyModel::Error($error)->toArray();
        $assertdata = json_encode([
            "success" => false,
            "message" => $error
        ]);
        $this->assertJsonStringEqualsJsonString($assertdata, json_encode($response));
    }

    /**
     * 使用外部來源(curl) api get 方式驗證 200 測試
     *
     * @return void
     */
    public function test_get_api_200()
    {
        // 變更外部資料來源網址
        config(['app.extra_currency.api_url' => $this->_extral_url]);
        // 題目輸入要求
        $params = $this->_params;
        $response = $this->get($this->_api_path . http_build_query($params));
        $response->assertStatus(200)
            ->assertJson(data_get($this->_assert_data, 'correct', []));
    }

    /**
     * 使用外部來源(curl) api get 方式驗證 400 測試
     *
     * @return void
     */
    public function test_get_api_400()
    {
        // 變更外部資料來源網址
        config(['app.extra_currency.api_url' => $this->_extral_url]);

        // 題目輸入要求: 錯誤的金額
        $params = $this->_params;
        $params['source_amount'] = '7800NotNumeric';
        $response = $this->get($this->_api_path . http_build_query($params));
        $response->assertStatus(400)
            ->assertJson(data_get($this->_assert_data, 'not_numeric', []));

        // 題目輸入要求: 錯誤的幣別
        $params = $this->_params;
        $params['source_currency'] = 'TWD2';
        $response = $this->get($this->_api_path . http_build_query($params));
        $response->assertStatus(400)
            ->assertJson(data_get($this->_assert_data, 'no_covert', []));
    }

    /**
     * 使用模組Mock方式驗證 200 測試
     *
     * @return void
     */
    public function test_model_correct()
    {
        // 題目輸入要求額
        $params = $this->_params;
        $response = CurrencyModel::GetInstance($params, (new ExtraCurrencyMock)->all())->toArray();
        $assertdata = json_encode(data_get($this->_assert_data, 'correct', []));
        $this->assertJsonStringEqualsJsonString($assertdata, json_encode($response), 'test_model_correct_ok');
    }

    /**
     * 使用模組Mock方式驗證 400 測試
     *
     * @return void
     */
    public function test_model_error()
    {
        // 題目輸入要求: 錯誤的金額
        $params = $this->_params;
        $params['source_amount'] = '7800NotNumeric';
        $response = CurrencyModel::GetInstance($params, (new ExtraCurrencyMock)->all())->toArray();
        $assertdata = json_encode(data_get($this->_assert_data, 'not_numeric2', []));
        $this->assertJsonStringEqualsJsonString($assertdata, json_encode($response));

        // 題目輸入要求: 錯誤的幣別
        $params = $this->_params;
        $params['source_currency'] = 'TWD2';
        $response = CurrencyModel::GetInstance($params, (new ExtraCurrencyMock)->all())->toArray();
        $assertdata = json_encode(data_get($this->_assert_data, 'no_covert', []));
        $this->assertJsonStringEqualsJsonString($assertdata, json_encode($response));
    }
}
