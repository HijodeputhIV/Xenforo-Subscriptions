<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Exception;

use HijodeputhIV\Subscriptions\ValueObject\Webhook;

class WebhookNotImplementedException extends ApiException
{
    public function __construct(Webhook $webhook)
    {
        parent::__construct(
            message: sprintf(
                'The endpoint %s does not implement a webhook supporting subscriptions',
                $webhook->getValue(),
            ),
            errorCode: 'subscriptions_webhook_not_implemented',
            httpCode: 502,
        );
    }
}
