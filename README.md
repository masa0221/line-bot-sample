## 概要
- LINEボットのサンプルです

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
docker run --rm -it -v $PWD:/app -w /app composer composer install
```

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

```
curl --header 'Accept: application/json' http://localhost/api/v1/delivery
```

```
curl -XPOST --header 'Accept: application/json' http://localhost/api/v1/callback
```


## ngrokについて
Webhookをローカルで確認するためには、ngrokが便利です。  

ngrok - secure introspectable tunnels to localhost  
https://ngrok.com/

