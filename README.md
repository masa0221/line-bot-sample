## 概要
LINEボットのサンプルです

以下の機能が実装されています。
- ボットを友だち登録しているメンバーに同じメッセージを送信する
- メッセージに返信する
    - 何パターンか完全一致で返答
    - 「？」だけ部分一致で返信
    - 「今日の天気は？」と聞くと今日の天気を返信

## 準備
### 1. ダウンロード
```
git clone https://github.com/masa0221/line-bot-sample.git && cd line-bot-sample
```

### 2. 環境変数用のファイルを用意
```
cp .env.example .env
```

### 3. `composer` を使ってパッケージをインストール
```
docker run --rm -it -u $UID:$GID -v $(pwd):/app -w /app composer:2.2.7 composer install --ignore-platform-reqs
```
※ `--ignore-platform-reqs` をつけずに実行すると、 `linecorp/line-bot-sdk 6.2.0 requires ext-sockets` というエラーが出ますが、 `laravel.test` コンテナで `ext-sockets` のPHPモジュールが入っているので問題ありません。



### 4. `sail` コマンドのエイリアスを作成
```
alias sail='bash vendor/bin/sail'
```
※シェルを起動したときに、再度設定する必要があります。  
 面倒な方は `.bashrc` や `.zshrc` などのRun-Controll Fileにalias設定を追加してください。

### 5. 起動
```
sail up -d
```
※ `-d` オプションをつけずに実行するとコンテナのログが見える状態になります(`Ctrl` + `C` を押す(同時押しする)と停止します)  

### 6. セキュリティのためのキーを（ `APP_KEY` ）を生成
```
sail artisan key:generate
```
`.env` ファイルの `APP_KEY` にハッシュ値が設定されます。


## APIの実行
### APIキーの取得
`.env`の以下は値の設定が必須になります
```
LINE_CHANNEL_ACCESS_TOKEN=
LINE_CHANNEL_SECRET=

OPENWEATHERMAP_API_KEY=
OPENWEATHERMAP_LATITUDE=
OPENWEATHERMAP_LONGITUDE=
```

### curlから実行

```
curl --header 'Accept: application/json' http://localhost/api/v1/delivery
```

```
curl -XPOST --header 'Accept: application/json' http://localhost/api/v1/callback
```
※ヘッダーの署名をチェックしているので上記のまま実行すると必ず「403 Forbidden」になります


## ngrokについて
Webhookをローカルで確認するためには、ngrokが便利です。  

ngrok（ [en-grok](https://ngrok.com/docs#name) エングロック）  
https://ngrok.com/

起動方法
```
ngrok http -region=jp 80
```
※-regionは指定なしでも動きますが、LINEのWebhookが失敗する可能性が高いです

