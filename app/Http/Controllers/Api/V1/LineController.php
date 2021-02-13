<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LineController extends Controller
{
    // メッセージ送信
    public function delivery()
    {
        // TODO: ここに具体的に実装

        // 1. 登録されている友だちにメッセージを送信

        return response()->json(['message' => 'sent']);
    }

    // メッセージを受け取って返信
    public function callback(Request $request)
    {
        // TODO: ここに具体的に実装

        // 1. 受け取った情報からメッセージの情報を取り出す
        // 2. 受け取ったメッセージの内容から返信するメッセージを生成
        // 3. 返信メッセージを返信先に送信

        return response()->json(['message' => 'received']);
    }
}

