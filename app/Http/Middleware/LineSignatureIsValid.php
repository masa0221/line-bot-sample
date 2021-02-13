<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LineSignatureIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $channelSecret = env('LINE_CHANNEL_SECRET');
        $httpRequestBody = $request->getContent();

        // LINE公式サイトに書いている通りに署名検証用の文字列を用意する
        // @see https://developers.line.biz/ja/reference/messaging-api/#signature-validation
        $hash = hash_hmac('sha256', $httpRequestBody, $channelSecret, true);
        $signature = base64_encode($hash);

        // リクエストヘッダのx-line-signatureの文字列と、署名検証用の文字が一致するか確認
        if ($request->header('x-line-signature') !== $signature) {
            // 一致しない場合は403 Forbidden(認証拒否)
            return response()->json([
                'message' => 'invalid request',
            ], 403);
        }

        // 次の処理(Controller等の処理)へ
        return $next($request);
    }
}
