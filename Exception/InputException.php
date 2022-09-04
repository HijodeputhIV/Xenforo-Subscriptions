<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Exception;

abstract class InputException extends ApiException
{
    public function __construct(string $message, string $errorCode)
    {
        parent::__construct(
            message: $message,
            errorCode: $errorCode,
            httpCode: 400
        );
    }
}
