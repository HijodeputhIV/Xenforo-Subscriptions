<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Exception;

use HijodeputhIV\Subscriptions\ValueObject\UserId;

class UserNotFoundException extends ApiException
{
    public function __construct(UserId $userId)
    {
        parent::__construct(
            message: sprintf('User with id %s does not exist', $userId->getValue()),
            errorCode: 'user_not_found',
            httpCode: 404,
        );
    }
}
