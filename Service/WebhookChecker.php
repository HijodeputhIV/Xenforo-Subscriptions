<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Service;

use GuzzleHttp\Client;

use HijodeputhIV\Subscriptions\ValueObject\Webhook;
use HijodeputhIV\Subscriptions\ValueObject\Token;
use HijodeputhIV\Subscriptions\Exception\WebhookNotImplementedException;

final class WebhookChecker
{
    public function __construct(
        private readonly Client $httpClient,
    ) {}

    /**
     * @throws WebhookNotImplementedException
     */
    public function check(Webhook $webhook, Token $challenge) : void
    {
        $challengeUrlValue = $webhook->getValue().'/'.$challenge->getValue();

        if ($this->httpClient->head($challengeUrlValue)->getStatusCode() !== 200) {
            throw new WebhookNotImplementedException($webhook);
        }
    }

}
