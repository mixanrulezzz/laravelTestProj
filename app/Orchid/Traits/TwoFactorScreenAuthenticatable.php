<?php

namespace App\Orchid\Traits;

use App\Models\User;
use App\Orchid\Screens\User\UserProfileScreen;
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
     * Получение формы с кнопками для включения двухфакторной аунтентификации
     *
     * @return View
     */
    public function twoFactorView(): View
    {
        return Layout::view('pages.admin.profile.two-factor-auth-field', ['isTwoFactorAuthEnable' => !empty(auth()->user()->hasEnabledTwoFactorAuthentication())]);
    }

    /**
     * Получение данных о модальном окне для двухфакторной аунтентификации
     *
     * @return Modal
     */
    public function twoFactorModal(): Modal
    {
        return Layout::modal(UserProfileScreen::TWO_FACTOR_MODAL, [
            Layout::view('pages.admin.profile.two-factor-auth-form'),
        ])
            ->title(__('Two factor authentication'))
            ->applyButton(__('Enable two-factor authentication'))
            ->staticBackdrop()
            ->open(session(UserProfileScreen::TWO_FACTOR_MODAL) === 'show')
            ->method('checkTwoFactorAuth');
    }

    /**
     * Отключение двухфакторной аунтентификации
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
    // todo Переделать
    public function generateNewRecoveryCodes(Request $request, GenerateNewRecoveryCodes $generateNewRecoveryCodes)
    {
        $generateNewRecoveryCodes($request->user());

        Toast::success(__('Recovery codes have been updated.'));

        return back()->with(UserProfileScreen::TWO_FACTOR_MODAL, 'show');
    }

    /**
     * Открытие модального окна с данными для двухфакторной аунтентификации
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function openTwoFactorModal(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $user->generateTwoFactorSecret();
        $user->generateRecoveryCodes();

        $request->session()->put('user', $user);

        return back()
            ->with(UserProfileScreen::TWO_FACTOR_MODAL, 'show')
            ->with('status', 'show-two-factor-qr-code');
    }


    /**
     * Проверка введенных данных для включения двухфакторной аунтентификации
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
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
            // Если все хорошо, то сохраняем данные для двухфакторной аунтентификации и перезагружаем страницу
            $user->save();
            $request->session()->forget('user');

            Toast::success(__('Two-factor authentication has been enabled'));

            return back();
        } else {
            // Если была ошибка в проверочном коде, то перезагружаем экран с модальным окном и с ошибкой
            return back()
                ->with(UserProfileScreen::TWO_FACTOR_MODAL, 'show')
                ->with('status', 'show-two-factor-qr-code')
                ->withErrors(['verificationCode' => __('Wrong verification code')]);
        }
    }
}
