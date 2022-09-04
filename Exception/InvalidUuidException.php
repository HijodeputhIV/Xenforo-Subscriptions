<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Exception;

final class InvalidUuidException extends InputException
{
    public function __construct(string $invalidId)
    {
        parent::__construct(
            message: sprintf('%s is not a valid uuid', $invalidId),
            errorCode: 'subscription_id_invalid_uuid',
        );
    }
}
