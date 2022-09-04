<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\ValueObject;

use HijodeputhIV\Subscriptions\Exception\InvalidTokenException;

final class Token
{
    private readonly string $hash;

    /**
     * @throws InvalidTokenException
     */
    public function __construct(string $hash)
    {
        if (strlen($hash) !== 32) {
            throw new InvalidTokenException();
        }

        $this->hash = $hash;
    }

    public function getValue() : string
    {
        return $this->hash;
    }

}

