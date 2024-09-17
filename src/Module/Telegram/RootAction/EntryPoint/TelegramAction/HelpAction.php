<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\RootAction\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Domain\User\AuthorizeAttribute;
use CarVolunteer\Infrastructure\Telegram\ActionInfo;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Infrastructure\Telegram\ActionRouteAccess;
use CarVolunteer\Infrastructure\Telegram\ButtonResponder;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Telephantast\MessageBus\MessageContext;

final readonly class HelpAction implements ActionInterface
{
    public function __construct(
        private ButtonResponder $buttonResponder,
        private ActionRouteAccess $routeAccess,
    ) {
    }

    public static function getInfo(): ActionInfo
    {
        return new ActionInfo(self::class, 'В начало', ActionRouteMap::RootHelp);
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $roles = $messageContext->getAttribute(AuthorizeAttribute::class)->roles ?? [];
        $buttons = [];

        $action = $this->routeAccess->get(ActionRouteMap::ParcelCreate, $roles);
        if ($action !== null) {
            $buttons[] = [$this->buttonResponder->generate($action)];
        }

        $action = $this->routeAccess->get(ActionRouteMap::ParcelList, $roles);
        if ($action !== null) {
            $buttons[] = [$this->buttonResponder->generate($action)];
        }

        $action = $this->routeAccess->get(ActionRouteMap::ParcelDelivered, $roles);
        if ($action !== null) {
            $buttons[] = [$this->buttonResponder->generate($action)];
        }

        $messageContext->dispatch(new SendMessageCommand(
            $message->userId,
            'Список доступных действий:',
            $buttons ? new InlineKeyboardMarkup($buttons) : null
        ));

        return $message->conversation;
    }
}
