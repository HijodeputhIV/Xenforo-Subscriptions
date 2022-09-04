<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions\Controller;

use XF\App;
use XF\Http\Request;
use XF\Api\Controller\AbstractController;
use XF\Api\Mvc\Reply\ApiResult;
use XF\Mvc\Reply\Exception;

use HijodeputhIV\Subscriptions\Action\CreateSubscription;

class Subscribe extends AbstractController
{
    private CreateSubscription $createSubscription;

    public function __construct(App $app, Request $request)
    {
        parent::__construct($app, $request);
        $this->createSubscription = $app->get(CreateSubscription::class);
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
