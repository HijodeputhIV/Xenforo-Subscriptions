<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Exception;

final class InvalidUserIdException extends ApiException
{
    public function __construct()
    {
        parent::__construct(
            message: 'User id must be greater than 0',
            httpCode: 400,
        );
    }
}
