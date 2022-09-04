<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Service;

use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use Throwable;
use Generator;

use GuzzleHttp\Client;
use GuzzleHttp\Pool;

use HijodeputhIV\Subscriptions\ValueObject\Webhook;
use HijodeputhIV\Subscriptions\ValueObject\XFPostData;

final class WebhookNotifier
{
    public function __construct(
        private readonly Client $httpClient,
    ) {}

    /**
     * @param Webhook[] $webhooks
     */
    public function notifyPost(array $webhooks, XFPostData $postData) : void
    {
        $asyncRequests = function() use($webhooks, $postData) : Generator {
            foreach ($webhooks as $webhook) {
                yield function() use ($webhook, $postData) : PromiseInterface {
                    return $this->httpClient->postAsync($webhook->getValue(), ['json' => $postData]);
                };
            }
        };

        $pool = new Pool($this->httpClient, $asyncRequests());
        $pool->promise()->wait();
    }

}
