<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\ValueObject;

use XF\Validator\Url as UrlValidator;

use HijodeputhIV\Subscriptions\Exception\InvalidWebhookException;

final class Webhook
{
    private readonly string $url;

    /**
     * @throws InvalidWebhookException
     */
    public function __construct(string $url, UrlValidator $urlValidator)
    {
        $urlValidator->setOption('allow_empty', false);

        if (!$urlValidator->isValid($url, $errorKey)) {
            throw new InvalidWebhookException($errorKey);
        }

        $this->url = $url;
    }

    public function getValue() : string
    {
        return $this->url;
    }

}

