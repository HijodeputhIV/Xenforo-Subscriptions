<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Controller;

use XF\Api\Controller\AbstractController;
use XF\Api\Mvc\Reply\ApiResult;
use XF\Db\Exception as XenforoDatabaseException;
use XF\Mvc\Reply\Exception as XenforoApiException;

use HijodeputhIV\Subscriptions\Entity\Subscription;
use HijodeputhIV\Subscriptions\Repository\Subscriptions;

class Subscribe extends AbstractController
{

    /**
     * @throws XenforoApiException
     */
    public function actionPost() : ApiResult
    {
        $this->assertRequiredApiInput([
            'user_id',
            'token',
            'webhook'
        ]);

        $subscription = new Subscription(
            userId: $this->request->filter('user_id', 'uint'),
            webhook: $this->request->filter('webhook', 'str'),
            token: $this->request->filter('token', 'str'),
        );

        $subscriptions = new Subscriptions($this->em());

        try {
            $subscriptions->save($subscription);
        }
        catch (XenforoDatabaseException) {
            throw $this->exception(
                $this->apiError(
                    'The subscription has failed',
                    'subscription_not_saved',
                    [],
                    500,
                )
            );
        }

        return $this->apiSuccess();
    }

}
