<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Exception;

final class InvalidWebhookException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Invalid url',
            httpCode: 400,
        );
    }
}
