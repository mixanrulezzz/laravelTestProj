<?php

namespace App\Services\User\Auth;

use Illuminate\Support\Collection;
use Laravel\Fortify\TwoFactorAuthenticationProvider;
use PragmaRX\Google2FA\Google2FA;

trait TwoFactorAuthUserTrait {

    /**
     * Генерирование нового секрета для двухфакторной аунтентификации
     */
    public function generateTwoFactorSecret() {
        $provider = $this->getNewTwoFactorAuthProvider();

        $secretKey = encrypt($provider->generateSecretKey());
        $this->two_factor_secret = $secretKey;
    }

    /**
     * Генерирование кодов для восстановления двухфакторной аунтентификации
     */
    public function generateRecoveryCodes() {
        $this->two_factor_recovery_codes = encrypt(json_encode(RecoveryCode::generate()));
    }

    /**
     * Проверка кода двухфакторной аунтентификации
     *
     * @param $verificationCode - код, который ввел пользователь
     * @return bool
     */
    public function checkTwoFactorCode($verificationCode) {
        $provider = $this->getNewTwoFactorAuthProvider();

        return $provider->verify(decrypt($this->two_factor_secret), $verificationCode);
    }

    private function getNewTwoFactorAuthProvider()
    {
        return new TwoFactorAuthenticationProvider(new Google2FA());
    }
}
