<?php

declare(strict_types=1);

namespace CarVolunteer\Infrastructure\Telegram;

final readonly class ButtonResponder
{
    /**
     * @return array{text: string, callback_data: string}
     */
    public function generate(
        ActionInfo $actionInfo,
        ?RouteParam $param = null,
        ?string $title = null
    ): array {
        $result = [
            'text' => $title ?? $actionInfo->defaultTitle,
            'callback_data' => $actionInfo->route->value
        ];

        if ($param !== null) {
            $result['callback_data'] .= sprintf('?%s=%s', $param->name, $param->value);
        }

        return $result;
    }
}
