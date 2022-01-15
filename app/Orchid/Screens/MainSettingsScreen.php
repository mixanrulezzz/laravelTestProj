<?php

namespace App\Orchid\Screens;

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
                    Input::make('two_factor.recovery_codes.amount')
                        ->type('number')
                        ->title(__('Number of recovery codes'))
                        ->placeholder(__('Enter number'))
                        ->value(settings('two_factor.recovery_codes.amount', 8)),

                    Button::make(__('Save'))->method('changeTwoFactorSettings')->type(Color::PRIMARY())
                ]),
            ])
        ];
    }

    /**
     * @param Request $request
     */
    public function changeTwoFactorSettings(Request $request)
    {
        $request->validate([
            'two_factor.recovery_codes.amount'  => 'required|integer',
        ]);

        $twoFactorSettings = $request->get('two_factor');

        settings(['two_factor.recovery_codes.amount' => $twoFactorSettings['recovery_codes']['amount']]);
    }
}
          
