<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Controller;

use XF\App;
use XF\Http\Request;
use XF\Api\Controller\AbstractController;
use XF\Api\Mvc\Reply\ApiResult;
use XF\Mvc\Reply\Exception;

use HijodeputhIV\Subscriptions\Action\CreateSubscription;
use HijodeputhIV\Subscriptions\Repository\MysqlUserRepository;
use HijodeputhIV\Subscriptions\Service\UserFinder;
use HijodeputhIV\Subscriptions\Service\WebhookChecker;
use HijodeputhIV\Subscriptions\Repository\MysqlSubscriptionRepository;

class Subscribe extends AbstractController
{
    private CreateSubscription $createSubscription;

    public function __construct(App $app, Request $request)
    {
        parent::__construct($app, $request);

        $this->createSubscription = new CreateSubscription(
            userFinder: new UserFinder(new MysqlUserRepository(($this->em()))),
            webhookChecker: new WebhookChecker($app->http()->createClient([
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'http_errors' => false,
            ])),
            subscriptionRepository: new MysqlSubscriptionRepository($this->em()),
        );
    }

    /**
     * @throws Exception
     */
    public function actionPost() : ApiResult
    {
        $this->assertRequiredApiInput([
            'user_id',
            'token',
            'webhook'
        ]);

        $this->createSubscription->execute(
            user_id: $this->request->filter('user_id', 'uint'),
            webhook: $this->request->filter('webhook', 'str'),
            token: $this->request->filter('token', 'str'),
        );

        return $this->apiSuccess();
    }

}
