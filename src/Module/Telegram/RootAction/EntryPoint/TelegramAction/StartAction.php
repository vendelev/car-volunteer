<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Telegram\RootAction\EntryPoint\TelegramAction;

use CarVolunteer\Domain\ActionInterface;
use CarVolunteer\Domain\Conversation\Conversation;
use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Domain\TelegramMessage;
use CarVolunteer\Infrastructure\Telegram\ActionInfo;
use CarVolunteer\Infrastructure\Telegram\ActionRouteMap;
use CarVolunteer\Infrastructure\Telegram\ButtonResponder;
use CarVolunteer\Module\Telegram\Domain\UserJoinedEvent;
use CarVolunteer\Module\Telegram\Domain\UserAttribute;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Telephantast\MessageBus\MessageContext;

final readonly class StartAction implements ActionInterface
{
    public function __construct(
        private ButtonResponder $buttonResponder,
    ) {
    }

    public static function getInfo(): ActionInfo
    {
        return new ActionInfo(self::class, '', ActionRouteMap::RootStart);
    }

    public function handle(TelegramMessage $message, MessageContext $messageContext): Conversation
    {
        $messageContext->dispatch(
            new SendMessageCommand(
                $message->userId,
                'Здравствуйте, для продолжения нажмите кнопку "Помощь"',
                new InlineKeyboardMarkup([
                    [$this->buttonResponder->generate(actionInfo: HelpAction::getInfo(), title: 'Помощь')],
                ])
            )
        );

        $user = $messageContext->getAttribute(UserAttribute::class);
        if ($user?->user !== null) {
            $messageContext->dispatch(new UserJoinedEvent($user->user));
        }

        return $message->conversation;
    }
}
