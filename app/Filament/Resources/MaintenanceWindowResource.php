<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceWindowResource\Pages;
use App\Filament\Resources\MaintenanceWindowResource\RelationManagers;
use App\Models\MaintenanceWindow;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MaintenanceWindowResource extends Resource
{
    protected static ?string $model = MaintenanceWindow::class;

    protected static ?string $navigationIcon = "heroicon-o-rectangle-stack";

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make("name")->required(),
            Forms\Components\TextInput::make("description")->required(),
            Forms\Components\DateTimePicker::make("starts_at")->required(),
            Forms\Components\DateTimePicker::make("ends_at")->required(),
            Forms\Components\CheckboxList::make("monitors")
                ->relationship("monitors", "name")
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")->searchable(),
                Tables\Columns\TextColumn::make("monitors_count")
                    ->counts("monitors")
                    ->label("Monitors")
                    ->sortable(),

                Tables\Columns\TextColumn::make("description")
                    ->searchable()
                    ->limit(40)
                    ->tooltip("Cliquer pour voir la description complète"),

                Tables\Columns\TextColumn::make("starts_at")
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make("ends_at")
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make("created_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make("updated_at")
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
            "index" => Pages\ListMaintenanceWindows::route("/"),
            "create" => Pages\CreateMaintenanceWindow::route("/create"),
            "edit" => Pages\EditMaintenanceWindow::route("/{record}/edit"),
        ];
    }
}
