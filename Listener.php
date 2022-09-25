<?php declare(strict_types=1);

namespace HijodeputhIV\Subscriptions;

use GuzzleHttp\Client;

use XF\App;

use HijodeputhIV\Subscriptions\Repository\MysqlSubscriptionRepository;
use HijodeputhIV\Subscriptions\Repository\MysqlUserRepository;
use HijodeputhIV\Subscriptions\Service\UserFinder;
use HijodeputhIV\Subscriptions\Service\WebhookChecker;
use HijodeputhIV\Subscriptions\Service\WebhookNotifier;
use HijodeputhIV\Subscriptions\Action\CreateSubscription;
use HijodeputhIV\Subscriptions\Action\NotifyPost;
use HijodeputhIV\Subscriptions\Action\NotifyUserAlert;
use HijodeputhIV\Subscriptions\Action\NotifyConversationMessage;

final class Listener
{

    private static function createJsonHttpClient(App $app) : Client
    {
        return $app->http()->createClient([
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'http_errors' => true,
        ]);
    }

    public static function appSetup(App $app) : void
    {
        $container = $app->container();

        $container[MysqlSubscriptionRepository::class] = function () use ($app) {
            return new MysqlSubscriptionRepository(
                entityManager: $app->em(),
                error: $app->error(),
            );
        };

        $container[MysqlUserRepository::class] = function () use ($app) {
            return new MysqlUserRepository($app->em());
        };

        $container[UserFinder::class] = function () use ($app) {
            return new UserFinder($app->get(MysqlUserRepository::class));
        };

        $container[WebhookChecker::class] = function () use ($app) {
            return new WebhookChecker(
                httpClient: self::createJsonHttpClient($app),
                error: $app->error(),
            );
        };

        $container[WebhookNotifier::class] = function () use ($app) {
            return new WebhookNotifier(
                httpClient: self::createJsonHttpClient($app),
                error: $app->error(),
            );
        };

        $container[CreateSubscription::class] = function () use ($app) {
            return new CreateSubscription(
                urlValidator: $app->validator('Url'),
                userFinder: $app->get(UserFinder::class),
                webhookChecker: $app->get(WebhookChecker::class),
                subscriptionRepository: $app->get(MysqlSubscriptionRepository::class),
            );
        };

        $container[NotifyPost::class] = function() use ($app) {
            return new NotifyPost(
                subscriptionRepository: $app->get(MysqlSubscriptionRepository::class),
                webhookNotifier: $app->get(WebhookNotifier::class),
            );
        };

        $container[NotifyUserAlert::class] = function() use ($app) {
            return new NotifyUserAlert(
                subscriptionRepository: $app->get(MysqlSubscriptionRepository::class),
                webhookNotifier: $app->get(WebhookNotifier::class),
            );
        };

        $container[NotifyConversationMessage::class] = function() use ($app) {
            return new NotifyConversationMessage(
                subscriptionRepository: $app->get(MysqlSubscriptionRepository::class),
                webhookNotifier: $app->get(WebhookNotifier::class),
            );
        };

    }

}
