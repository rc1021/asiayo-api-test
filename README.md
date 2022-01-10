
## About Asiayo Api Test

僅提供 Asiayo Api 考題用

## 環境需求

```
- PHP 8.0 或以上 (with curl)
- Composer
```

## 安裝

```
$ git clone 
$ cd 
$ composer install
```

## 環境變數
請修改 .env 的 `EXTRA_CURRENCY_API_URL` 或參考 .env.example 的設定

```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:OAJ7hZAFo2utRGzlZnjQl0SVIUJUwU+utuwxX+Nt9RE=
APP_DEBUG=true
APP_URL=http://asiayo-api-test.test

EXTRA_CURRENCY_API_URL=https://currency.extra.url
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

