<?php

namespace App\Services\User\Auth;

use Illuminate\Support\Collection;
use Laravel\Fortify\TwoFactorAuthenticationProvider;
use PragmaRX\Google2FA\Google2FA;

trait TwoFactorAuthCustom {

    /**
     *
     */
    public function generateTwoFactorSecret() {
        $provider = new TwoFactorAuthenticationProvider(new Google2FA());

        $secretKey = encrypt($provider->generateSecretKey());
        $this->two_factor_secret = $secretKey;
    }

    public function generateRecoveryCodes() {
        // todo Сделать кол-во кодов настраиваемым
        $this->two_factor_recovery_codes = encrypt(json_encode(Collection::times(8, function () {
            return RecoveryCode::generate();
        })->all()));
    }

    public function checkTwoFactorCode($verificationCode) {
        $provider = new TwoFactorAuthenticationProvider(new Google2FA());

        return $provider->verify(decrypt($this->two_factor_secret), $verificationCode);
    }
}
