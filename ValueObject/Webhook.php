<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\ValueObject;

use HijodeputhIV\Subscriptions\Exception\InvalidWebhookException;

final class Webhook
{
    private readonly string $url;

    /**
     * @throws InvalidWebhookException
     */
    public function __construct(string $url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL) === true) {
            throw new InvalidWebhookException;
        }

        $this->url = $url;
    }

    public function getValue() : string
    {
        return $this->url;
    }

}

