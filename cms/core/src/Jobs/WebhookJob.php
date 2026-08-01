<?php

namespace Cms\Core\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class WebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $url;
    protected array $payload;
    protected ?string $secret;

    public function __construct(string $url, array $payload, ?string $secret = null)
    {
        $this->url = $url;
        $this->payload = $payload;
        $this->secret = $secret;
    }

    public function handle(): void
    {
        $headers = [
            'Content-Type' => 'application/json',
            'User-Agent' => 'LaraCMS-Webhook-Dispatcher/1.0',
        ];

        if ($this->secret) {
            $signature = hash_hmac('sha256', json_encode($this->payload), $this->secret);
            $headers['X-LaraCMS-Signature'] = $signature;
        }

        try {
            Http::withHeaders($headers)
                ->timeout(5)
                ->post($this->url, $this->payload);
        } catch (\Throwable $e) {
            logger()->error("Webhook failed to {$this->url}: " . $e->getMessage());
        }
    }
}
