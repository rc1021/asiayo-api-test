<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\CurrencyModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Services\ExtraCurrency\Facades\ExtraCurrency;

class CurrencyController extends Controller
{
    public function index(Request $request)
    {
        // 驗證規則
        $rules = [
            'source_amount' => 'required|numeric',
            'source_currency' => 'required',
            'target_currency' => 'required',
        ];

        // 錯誤訊息定義
        $messages = [
            'source_amount.required' => 'Missing required parameters :attribute',
            'source_amount.numeric' => 'The :attribute is not a numeric value',
            'source_currency.required' => 'Missing required parameters :attribute',
            'target_currency.required' => 'Missing required parameters :attribute',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(CurrencyModel::Error($errors->first())->toArray(), 400, [], JSON_PRETTY_PRINT);
        }

        $result = CurrencyModel::GetInstance($request->all(), ExtraCurrency::all())->toArray();
        return response()->json($result, ($result['success']) ? 200 : 400, [], JSON_PRETTY_PRINT);
    }
}
