<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Service;

use JsonSerializable;
use Generator;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Pool;

use HijodeputhIV\Subscriptions\Entity\Subscription;
use HijodeputhIV\Subscriptions\ValueObject\Webhook;
use HijodeputhIV\Subscriptions\ValueObject\XFPostData;
use HijodeputhIV\Subscriptions\ValueObject\XFUserAlertData;

final class WebhookNotifier
{
    public function __construct(
        private readonly Client $httpClient,
    ) {}

    /**
     * @param Subscription[] $subscriptions
     */
    private function postAsyncRequests(string $uri, array $subscriptions, JsonSerializable $data) : void
    {
        $webhooks = array_map(
            function (Subscription $subscription) : Webhook {
                return $subscription->webhook;
            },
            $subscriptions,
        );

        $asyncRequests = function() use($webhooks, $uri, $data) : Generator {
            foreach ($webhooks as $webhook) {
                $url = $webhook->getValue().$uri;
                yield function() use ($url, $data) : PromiseInterface {
                    return $this->httpClient->postAsync(
                        $url,
                        ['json' => $data]
                    );
                };
            }
        };

        $pool = new Pool($this->httpClient, $asyncRequests());
        $pool->promise()->wait();
    }

    /**
     * @param Subscription[] $subscriptions
     */
    public function notifyPost(array $subscriptions, XFPostData $postData) : void
    {
        $this->postAsyncRequests(
            uri: '/posts',
            subscriptions: $subscriptions,
            data: $postData,
        );
    }

    /**
     * @param Subscription[] $subscriptions
     */
    public function notifyUserAlert(array $subscriptions, XFUserAlertData $userAlertData) : void
    {
        $this->postAsyncRequests(
            uri: '/user-alerts',
            subscriptions: $subscriptions,
            data: $userAlertData,
        );
    }

}
