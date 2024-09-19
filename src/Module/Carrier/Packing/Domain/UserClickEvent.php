<?php

declare(strict_types=1);

namespace CarVolunteer\Module\Carrier\Packing\Domain;

/**
 * Набор действий пользователя для автомата состояний подтверждения, что посылка собрана
 */
enum UserClickEvent: string
{
    /** Клик по кнопке "загрузить фото" */
    case LoadPhoto = 'photo';

    /** Клик по кнопке "Подтвердить готовность посылки" */
    case Packing = 'pack';

    /** Подтверждение, что посылка собрана */
    case Packed = 'packed';
}
