<?php

declare(strict_types=1);

use CarVolunteer\Module\Telegram\Application\Authorization;
use CarVolunteer\Module\Telegram\Application\CommandLocator;
use CarVolunteer\Module\Telegram\Domain\CommandHandler;
use CarVolunteer\Module\Telegram\EntryPoint\BotCommand\CreateOrderHandler;
use CarVolunteer\Module\Telegram\EntryPoint\BotCommand\HelpHandler;
use CarVolunteer\Module\Telegram\EntryPoint\BotCommand\StartHandler;
use CarVolunteer\Module\Telegram\EntryPoint\MessageCommand\RegisterUserCommand;
use CarVolunteer\Module\Telegram\EntryPoint\Web\WebhookController;
use CarVolunteer\Module\Telegram\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TelegramBot\Api\BotApi;

return static function (ContainerConfigurator $configurator, ContainerBuilder $builder): void {
    $services = $configurator->services();

    $services->set(BotApi::class)->arg('$token', '%env(TELEGRAM_BOT_TOKEN)%');
    $services->set(Authorization::class)->arg('$roles', '%env(json:file:resolve:ROLES)%');

    $services->set(WebhookController::class)->autowire()->tag('controller.service_arguments');

//    $builder->registerForAutoconfiguration(CommandHandler::class)->addTag(CommandHandler::class);
    $services->instanceof(CommandHandler::class)->tag(CommandHandler::class);

    $services->set(CommandLocator::class)->autowire();
    $services->set(StartHandler::class)->autowire();
    $services->set(HelpHandler::class)->autowire();
    $services->set(CreateOrderHandler::class)->autowire();

    $services->set(RegisterUserCommand::class)->autowire()->tag('messenger.message_handler');
    $services->set(UserRepository::class)->public()->autowire();
};
