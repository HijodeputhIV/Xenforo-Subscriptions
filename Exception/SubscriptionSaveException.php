<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Exception;

final class SubscriptionSaveException extends ApiException
{
    public function __construct(string $message)
    {
        parent::__construct(
            message: $message,
            errorCode: 'subscription_save_failed',
            httpCode: 500,
        );
    }
}
