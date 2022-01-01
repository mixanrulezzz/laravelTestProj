@if(\Laravel\Fortify\Features::canManageTwoFactorAuthentication())

    @php($user = session('user'))
    @if(session('status') == 'show-two-factor-qr-code')
        {{-- Show SVG QR Code, After Enabling 2FA --}}
        <div class="px-4 py-2">
            {{ __('Scan the following QR code using your phone\'s authenticator application and enter the resulting code from app to enable two-factor auth') }}
        </div>

        <div class="text-center p-3">
            {!! $user->twoFactorQrCodeSvg() !!}
        </div>
        <div class="text-center p-3">
            <label for="verificationCodeInput">{{ __('Verification code') }}:</label>
            <input type="text" name="verificationCode" class="input" id="verificationCodeInput" autocomplete="off">
        </div>
    @endif

    @if( is_object($user) && $user->two_factor_recovery_codes )
        {{-- Show 2FA Recovery Codes --}}
        <div class="px-4 py-2">
            {{ __('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.') }}
        </div>

        <div class="bg-light px-4 py-2">
            @foreach (json_decode(decrypt($user->two_factor_recovery_codes), true) as $code)
                <p class="m-0 text-black">{{ $code }}</p>
            @endforeach
        </div>
    @endif
@endif

<script>
    $(document).ready(function () {
        // Костыль для открытия формы при клике на кнопку
        // todo Перенести в отдельный js файл и возможно предложить добавить в платформу orchid
        $('body').find('.modal[data-modal-open=1]').modal('show');
        // Костыль для отправки формы подтверждения включения двухфакторной аунтентификации
        // todo Добавить константу для названия модального окна чтобы можно было везде использовать
        $('body').find('#screen-modal-form-two-factor-auth').attr('action', '{{ route('platform.profile.checkTwoFactorAuth') }}');
    });
</script>