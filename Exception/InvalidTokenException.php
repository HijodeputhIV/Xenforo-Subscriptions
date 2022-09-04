<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Exception;

final class InvalidTokenException extends InputException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Invalid md5 hash',
            errorCode: 'subscription_token_invalid_hash'
        );
    }
}
