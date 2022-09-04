<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\ValueObject;

use HijodeputhIV\Subscriptions\Exception\InvalidTokenException;

final class Token
{
    private readonly string $token;

    /**
     * @throws InvalidTokenException
     */
    public function __construct(string $token)
    {
        if (strlen($token) !== 32) {
            throw new InvalidTokenException();
        }

        $this->token = $token;
    }

    public function getValue() : string
    {
        return $this->token;
    }

}

