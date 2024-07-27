<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\ViewParcel\Application\UseCases;

use CarVolunteer\Domain\Telegram\SendMessageCommand;
use CarVolunteer\Module\Carrier\Domain\Entity\Parcel;
use CarVolunteer\Module\Carrier\Domain\ParcelRepositoryInterface;
use CarVolunteer\Module\Carrier\Domain\ParcelStatus;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Telephantast\MessageBus\MessageContext;

final readonly class ViewParcelsUseCase
{
    public function __construct(
        private ParcelRepositoryInterface $parcelRepository
    ) {
    }

    public function handle(string $userId, MessageContext $messageContext): void
    {
        /** @var list<Parcel> $list */
        $list = $this->parcelRepository->findBy(['status' => [ParcelStatus::Described->value]]);
        $buttons = [];

        foreach ($list as $item) {
            $buttons[] = [[
                'text' => sprintf('%s (от %s)', $item->title, $item->createAt->format('d.m.Y')),
                'callback_data' => '/viewParcel?id=' . $item->id
            ]];
        }

        $messageContext->dispatch(new SendMessageCommand(
            $userId,
            'Список активных посылок',
            new InlineKeyboardMarkup($buttons)
        ));
    }
}