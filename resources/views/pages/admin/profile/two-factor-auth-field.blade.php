<fieldset class="row g-0 mb-3 ">
    <div class="col p-0 px-3">
        <legend class="text-black">
            {{ __('Two-factor authentication') }}
            <p class="small text-muted mt-2 mb-0">
                {{ __('For better protection you can activate two-factor authentication') }}
            </p>
        </legend>
    </div>
    <div class="col-12 col-md-7 d-flex align-content-around">

{{--        <div class="bg-white d-flex flex-column layout-wrapper rounded-top">--}}

{{--        </div>--}}

        <div class="bg-light px-4 py-3 d-flex align-items-center justify-content-end rounded" style="width: 100%">
            <div class="form-group mb-0">

                @if($isTwoFactorAuthEnable)
                    <button data-controller="button" data-turbo="true" class="btn btn-danger" type="submit" form="post-form"
                            formaction="{{ route('platform.profile.disableTwoFactorAuth') }}">
                        {{ __('Disable two-factor authentication') }}
                    </button>
                @else
                    <button data-controller="button" data-turbo="true" class="btn btn-default" type="submit" form="post-form"
                            formaction="{{ route('platform.profile.openTwoFactorModal') }}">
                        {{ __('Enable two-factor authentication') }}
                    </button>
                @endif

            </div>
        </div>
    </div>
</fieldset>
