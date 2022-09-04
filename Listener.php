<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions;

use HijodeputhIV\Subscriptions\Action\CreateSubscription;
use XF\App;
use XF\Container;

use GuzzleHttp\Client;

use HijodeputhIV\Subscriptions\Repository\MysqlSubscriptionRepository;
use HijodeputhIV\Subscriptions\Repository\MysqlUserRepository;
use HijodeputhIV\Subscriptions\Service\UserFinder;
use HijodeputhIV\Subscriptions\Service\WebhookChecker;
use HijodeputhIV\Subscriptions\Service\WebhookNotifier;

final class Listener
{

    private static function createSilentHttpClient(App $app) : Client
    {
        return $app->http()->createClient([
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'http_errors' => false,
        ]);
    }

    public static function appSetup(App $app) : void
    {
        $container = $app->container();

        $container[MysqlSubscriptionRepository::class] = function () use ($app) {
            return new MysqlSubscriptionRepository($app->em());
        };

        $container[MysqlUserRepository::class] = function () use ($app) {
            return new MysqlUserRepository($app->em());
        };

        $container[UserFinder::class] = function () use ($app) {
            return new UserFinder($app->get(MysqlUserRepository::class));
        };

        $container[WebhookChecker::class] = function () use ($app) {
            return new WebhookChecker(self::createSilentHttpClient($app));
        };

        $container[WebhookNotifier::class] = function () use ($app) {
            return new WebhookNotifier(self::createSilentHttpClient($app));
        };

        $container[CreateSubscription::class] = function () use ($app) {
            return new CreateSubscription(
                urlValidator: $app->validator('Url'),
                userFinder: $app->get(UserFinder::class),
                webhookChecker: $app->get(WebhookChecker::class),
                subscriptionRepository: $app->get(MysqlSubscriptionRepository::class),
            );
        };
    }

}
