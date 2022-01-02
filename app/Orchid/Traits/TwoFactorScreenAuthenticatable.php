<?php

namespace App\Orchid\Traits;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Laravel\Fortify\Features;
use Laravel\Fortify\TwoFactorAuthenticationProvider;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Layouts\View;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use PragmaRX\Google2FA\Google2FA;

trait TwoFactorScreenAuthenticatable
{
    /**
     * @return ModalToggle
     */
    public function twoFactorCommandBar(): ModalToggle
    {
        return ModalToggle::make('ModalToggle')
            ->modal('two-factor-auth')
            ->method('checkTwoFactorAuth')
            ->canSee(true);
    }

    /**
     * @return View
     */
    public function twoFactorView(): View
    {
        return Layout::view('pages.admin.profile.two-factor-auth-field', ['isTwoFactorAuthEnable' => !empty(auth()->user()->hasEnabledTwoFactorAuthentication())]);
    }

    public function twoFactorModal(): Modal
    {
        return Layout::modal('two-factor-auth', [
            Layout::view('pages.admin.profile.two-factor-auth-form'),
        ])
            ->title(__('Two factor authentication'))
            ->applyButton(__('Enable two-factor authentication'))
            ->staticBackdrop()
            ->open(session('two-factor-auth') === 'show')
            ->method('checkTwoFactorAuth');
    }

    /**
     * Disable two-factor authentication for the given user.
     *
     * @param Request                        $request
     * @param DisableTwoFactorAuthentication $disableTwoFactorAuthentication
     */
    public function disableTwoFactorAuth(Request $request, DisableTwoFactorAuthentication $disableTwoFactorAuthentication)
    {
        $disableTwoFactorAuthentication($request->user());

        Toast::success(__('Two-factor authentication has been disabled'));
    }

    /**
     * Generate new recovery codes for the user.
     *
     * @param Request                  $request
     * @param GenerateNewRecoveryCodes $generateNewRecoveryCodes
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateNewRecoveryCodes(Request $request, GenerateNewRecoveryCodes $generateNewRecoveryCodes)
    {
        $generateNewRecoveryCodes($request->user());

        Toast::success(__('Recovery codes have been updated.'));

        return back()->with('two-factor-auth', 'show');
    }

    public function openTwoFactorModal(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $user->generateTwoFactorSecret();
        $user->generateRecoveryCodes();

        $request->session()->put('user', $user);

        return back()
            ->with('two-factor-auth', 'show')
            ->with('status', 'show-two-factor-qr-code');
    }

    /**
     * Enable two-factor authentication for the user.
     *
     * @param Request $request
     */
    public function checkTwoFactorAuth(Request $request)
    {
        /** @var User $user */
        $user = $request->session()->get('user');

        $code = $request->get('verificationCode') ?? '';

        $isValid = false;

        if (strlen($code) == 6) {
            $isValid = $user->checkTwoFactorCode($code);
        }

        if ($isValid) {
            $user->save();
            $request->session()->forget('user');

            Toast::success(__('Two-factor authentication has been enabled'));

            return back();
        } else {
            // todo Вывод ошибок
            Toast::error(__('Two-factor authentication hasn\'t been enabled'));



//            throw ValidationException::withMessages([
//                'verificationCode' => __('Wrong verification code')
//            ]);
        }
    }
}
