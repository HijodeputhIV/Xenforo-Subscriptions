<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\ValueObject;

use Stripe\Util\RandomGenerator as UuidGenerator;
use Laminas\Validator\Uuid as UuidValidator;

use HijodeputhIV\Subscriptions\Exception\InvalidUuidException;

final class SubscriptionId
{
    public readonly string $uuid;

    /**
     * @throws InvalidUuidException
     */
    public function __construct(string $uuid)
    {
        $uuidValidator = new UuidValidator();

        if (!$uuidValidator->isValid($uuid)) {
            throw new InvalidUuidException($uuid);
        }

        $this->uuid = $uuid;
    }

    /**
     * @throws InvalidUuidException
     */
    public static function generate() : SubscriptionId
    {
        $uuidGenerator = new UuidGenerator();
        return new SubscriptionId($uuidGenerator->uuid());
    }

    public function getValue() : string
    {
        return $this->uuid;
    }

}
