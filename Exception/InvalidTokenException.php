<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Exception;

final class InvalidTokenException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Invalid md5 hash',
            httpCode: 400,
        );
    }
}
