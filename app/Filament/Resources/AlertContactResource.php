<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlertContactResource\Pages;
use App\Filament\Resources\AlertContactResource\RelationManagers;
use App\Models\AlertContact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AlertContactResource extends Resource
{
    protected static ?string $model = AlertContact::class;

    protected static ?string $navigationIcon = "heroicon-o-rectangle-stack";

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make("name")
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make("type")
                ->options([
                    "email" => "Email",
                ])
                ->required(),
            Forms\Components\TextInput::make("value")
                ->label("Email Address")
                ->required()
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("type")->badge()->sortable(),
                Tables\Columns\TextColumn::make("value")
                    ->label("Destination")
                    ->searchable(),
                Tables\Columns\TextColumn::make("user.name")
                    ->label("Owner")
                    ->sortable(),
                Tables\Columns\TextColumn::make("created_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
                //
            ];
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListAlertContacts::route("/"),
            "create" => Pages\CreateAlertContact::route("/create"),
            "edit" => Pages\EditAlertContact::route("/{record}/edit"),
        ];
    }
}
