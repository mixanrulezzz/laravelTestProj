<?php


namespace App\Services\User\Auth;

use Illuminate\Support\Str;

class RecoveryCode
{
    /**
     * Генерирует код для восстановления доступа при двухфакторной аунтентификации
     *
     * @return string
     */
    public function generate(): string
    {
        return mb_strtoupper(Str::random(8)); // todo Сделать настраиваемость
    }
}
