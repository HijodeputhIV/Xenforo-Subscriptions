<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\ValueObject;

use HijodeputhIV\Subscriptions\Exception\InvalidUserIdException;

final class UserId
{
    private readonly int $user_id;

    /**
     * @throws InvalidUserIdException
     */
    public function __construct(int $user_id)
    {
        if ($user_id <= 0) {
            throw new InvalidUserIdException();
        }

        $this->user_id = $user_id;
    }

    public function getValue() : int
    {
        return $this->user_id;
    }

}
