<?php

namespace App\Orchid\Screens;

use App\Services\User\Auth\RecoveryCode;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;

class MainSettingsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = "Settings";

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::tabs([
                __('Two-factor authentication') => Layout::rows([
                    Input::make(RecoveryCode::AMOUNT_SETTING_NAME)
                        ->type('number')
                        ->title(__('Number of recovery codes'))
                        ->placeholder(__('Enter number'))
                        ->value(settings(RecoveryCode::AMOUNT_SETTING_NAME, RecoveryCode::DEFAULT_AMOUNT)),

                    Button::make(__('Save'))->method('saveTwoFactorSettings')->type(Color::PRIMARY())
                ]),
            ])
        ];
    }

    /**
     * Сохранить настройки двухфакторной аунтентификации
     *
     * @param Request $request
     */
    public function saveTwoFactorSettings(Request $request)
    {
        $request->validate([
            RecoveryCode::AMOUNT_SETTING_NAME  => 'required|integer',
        ]);

        settings([
            RecoveryCode::AMOUNT_SETTING_NAME => $request->input(RecoveryCode::AMOUNT_SETTING_NAME)
        ]);
    }
}
          
