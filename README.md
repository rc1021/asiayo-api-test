
## About Asiayo Api Test

僅提供 Asiayo Api 考題用

## 專案結構

![專案結構](https://github.com/rc1021/asiayo-api-test/raw/main/docs.png)

## 環境需求

```
- PHP 8.0 或以上 (with curl)
- Composer
```

## 安裝

```
$ git clone git@github.com:rc1021/asiayo-api-test.git
$ cd asiayo-api-test
$ composer install
```

## 環境變數
請修改 .env 的 `EXTRA_CURRENCY_API_URL` 或參考 .env.example 的設定

```
EXTRA_CURRENCY_API_URL=https://currency.extra.url
...(略過)
```

## 啟動服務

```
$ php artisan serv
Starting Laravel development server: http://127.0.0.1:8000
[Mon Jan 10 17:44:34 2022] PHP 8.0.14 Development Server (http://127.0.0.1:8000) started
```

## 範例一

```
$ wget http://127.0.0.1:8000/api/v1/currency?source_amount=7800&source_currency=TWD&target_currency=USD -O result.txt
$ cat result.txt
{
    "target": {
        "amount": 255.91799999999998,
        "currency": "USD",
        "format": "255.92"
    },
    "source": {
        "amount": 7800,
        "currency": "TWD",
        "format": "7,800.00"
    },
    "success": true
}% 
```

## 範例二 

```
$ wget http://127.0.0.1:8000/api/v1/currency?source_amount=7800&source_currency=TWD2&target_currency=USD -O result.txt
$ cat result.txt 
{
    "success": false,
    "message": "No currency convert data from TWD2 to USD."
}% 
```

## 單元測試

![單元測試](https://github.com/rc1021/asiayo-api-test/raw/main/unit_test.jpeg)
