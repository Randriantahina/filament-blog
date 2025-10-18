<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

use Illuminate\Database\Eloquent\Collection;

class ManageApiTokens extends Page
{
    public ?Collection $tokens = null;

    public function mount(): void
    {
        $this->tokens = auth()->user()->tokens;
    }

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static string $view = 'filament.pages.manage-api-tokens';

    protected static ?string $navigationGroup = 'Account';

    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return 'API Tokens';
    }

    public function getHeading(): string
    {
        return 'Manage API Tokens';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Create new token')
                ->form([
                    TextInput::make('name')
                        ->label('Token Name')
                        ->required()
                        ->maxLength(255),
                ])
                ->action(function (array $data) {
                    $user = auth()->user();
                    $token = $user->createToken($data['name']);

                    Notification::make()
                        ->title('New token created!')
                        ->body("Here is your new token. This is the only time it will be shown. Copy it now: {$token->plainTextToken}")
                        ->persistent()
                        ->success()
                        ->send();

                    $this->mount();
                }),
        ];
    }

    public function deleteToken($tokenId)
    {
        $user = auth()->user();
        $token = $user->tokens()->where('id', $tokenId)->first();

        if ($token) {
            $token->delete();
            Notification::make()
                ->title('Token deleted')
                ->success()
                ->send();
            $this->mount();
        }
    }
}
