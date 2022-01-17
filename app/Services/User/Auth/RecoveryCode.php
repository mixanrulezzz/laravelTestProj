<?php


namespace App\Services\User\Auth;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Класс для генерации кодов восстановления для двухфакторной аунтентификации
 *
 * Class RecoveryCode
 * @package App\Services\User\Auth
 */
class RecoveryCode
{
    /** @var string Название настройки для кол-ва кодов восстановления */
    const AMOUNT_SETTING_NAME = 'two_factor.recovery_codes.amount';

    /** @var int Кол-во кодов восстановления по умолчанию */
    const DEFAULT_AMOUNT = 8;

    /**
     * Генерирует код для восстановления доступа при двухфакторной аунтентификации
     *
     * @return string
     */
    private static function generateOneCode(): string
    {
        return mb_strtoupper(Str::random(8)); // todo Сделать настраиваемость
    }

    /**
     * Генерирует массив кодов восстановления, количество зависит от настройки
     *
     * @return array
     */
    public static function generate(): array
    {
        return Collection::times(settings(RecoveryCode::AMOUNT_SETTING_NAME, RecoveryCode::DEFAULT_AMOUNT), function () {
            return RecoveryCode::generateOneCode();
        })->all();
    }
}
