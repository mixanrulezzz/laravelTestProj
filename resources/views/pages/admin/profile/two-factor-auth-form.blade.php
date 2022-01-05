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
        <div class="form-group px-4 py-2">
            <label for="verificationCodeInput" class="form-label">{{ __('Verification code') }}:</label>
            {!!
                Orchid\Screen\Fields\Input::make('verificationCode')
                    ->id('verificationCodeInput')
                    ->value(old('verificationCode'))
                    ->autocomplete('off')
                    ->render()
            !!}
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
        {{-- Костыль для отправки формы подтверждения включения двухфакторной аунтентификации --}}
        let $modalForm = $('body').find('#screen-modal-form-{{ \App\Orchid\Screens\User\UserProfileScreen::TWO_FACTOR_MODAL }}');
        $modalForm.attr('action', '{{ route('platform.profile.checkTwoFactorAuth') }}');
    });
</script>
