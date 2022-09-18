<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\ValueObject;

use JsonSerializable as JsonSerializableContract;

abstract class JsonSerializable implements JsonSerializableContract
{
    public function jsonSerialize() : array
    {
        return (array)$this;
    }
}
