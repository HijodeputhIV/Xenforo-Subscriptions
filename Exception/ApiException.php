<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Exception;

use XF\Mvc\Reply\Exception;
use XF\Mvc\Reply\Error;
use XF\Api\ErrorMessage;

abstract class ApiException extends Exception
{
    public function __construct(string $message, int $httpCode)
    {
        $apiErrorMessage = new ErrorMessage($message, $httpCode);
        parent::__construct(new Error($apiErrorMessage));
    }
}
