<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CallbackTest extends TestCase
{
    /**
     * 署名検証が通過すること
     *
     * @return void
     */
    public function testValidSignature()
    {
        $channelSecret = env('LINE_CHANNEL_SECRET');
        $httpRequestBody = '';
        $hash = hash_hmac('sha256', $httpRequestBody, $channelSecret, true);
        $signature = base64_encode($hash);
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'x-line-signature' => $signature,
        ])->post('/api/v1/callback');

        $response->assertStatus(200);
    }
}
