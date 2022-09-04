<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Exception;

final class InvalidWebhookException extends InputException
{
    public function __construct(string $errorKey)
    {
        parent::__construct(
            message:'Invalid url',
            errorCode: 'subscription_webhook_url_'.$errorKey,
        );
    }
}
