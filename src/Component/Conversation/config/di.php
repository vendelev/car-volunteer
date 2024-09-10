<?php

declare(strict_types=1);

use CarVolunteer\Component\Conversation\Application\UseCases\GetLastConversation;
use CarVolunteer\Component\Conversation\Application\UseCases\SaveConversation;
use CarVolunteer\Component\Conversation\Domain\ConversationRepositoryInterface;
use CarVolunteer\Component\Conversation\Domain\Entity\Conversation;
use CarVolunteer\Component\Conversation\EntryPoint\BusHandler\GetLastConversationHandler;
use CarVolunteer\Component\Conversation\EntryPoint\BusHandler\SaveConversationHandler;
use CarVolunteer\Component\Conversation\Infrastructure\Repository\ConversationRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services
        ->set(GetLastConversationHandler::class)
        ->set(GetLastConversation::class)->autowire()

        ->set(SaveConversationHandler::class)
        ->set(SaveConversation::class)

        ->set(Conversation::class)
        ->alias(ConversationRepositoryInterface::class, ConversationRepository::class)
        ->set(ConversationRepository::class)
            ->public()
            ->autowire();
};
