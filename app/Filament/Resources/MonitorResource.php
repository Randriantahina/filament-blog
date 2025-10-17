<?php

namespace App\Filament\Resources;

use App\Enums\MonitorStatus;
use App\Enums\MonitorType;
use App\Filament\Resources\MonitorResource\Pages;
use App\Filament\Resources\MonitorResource\RelationManagers;
use App\Models\Monitor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MonitorResource extends Resource
{
    protected static ?string $model = Monitor::class;

    protected static ?string $navigationIcon = "heroicon-o-rectangle-stack";

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make("General Information")
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make("name")
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make("type")
                        ->options(MonitorType::class)
                        ->required()
                        ->live(),
                    Forms\Components\Select::make("check_interval_minutes")
                        ->label("Check Interval")
                        ->options([
                            1 => "1 Minute",
                            5 => "5 Minutes",
                            10 => "10 Minutes",
                            30 => "30 Minutes",
                            60 => "1 Hour",
                        ])
                        ->default(5)
                        ->required(),
                    Forms\Components\Select::make("user_id")
                        ->relationship("user", "name")
                        ->default(fn() => auth()->id())
                        ->required(),
                ]),

            Forms\Components\Section::make("HTTP(s) Options")
                ->visible(fn(Forms\Get $get) => $get("type") === "http")
                ->schema([
                    Forms\Components\TextInput::make("url")
                        ->label("URL")
                        ->required(
                            fn(Forms\Get $get) => $get("type") === "http",
                        )
                        ->maxLength(255),
                    Forms\Components\Select::make("method")
                        ->options([
                            "GET" => "GET",
                            "POST" => "POST",
                            "PUT" => "PUT",
                            "PATCH" => "PATCH",
                            "DELETE" => "DELETE",
                        ])
                        ->default("GET")
                        ->required(),
                    Forms\Components\Textarea::make("body")
                        ->label("Request Body")
                        ->rows(3),
                    Forms\Components\KeyValue::make("headers")->label(
                        "Request Headers",
                    ),
                ]),

            Forms\Components\Section::make("Port Options")
                ->visible(fn(Forms\Get $get) => $get("type") === "port")
                ->schema([
                    Forms\Components\TextInput::make("url")
                        ->label("IP Address or Host")
                        ->required(
                            fn(Forms\Get $get) => $get("type") === "port",
                        )
                        ->maxLength(255),
                    Forms\Components\TextInput::make("port")
                        ->label("Port Number")
                        ->required(
                            fn(Forms\Get $get) => $get("type") === "port",
                        )
                        ->numeric(),
                ]),

            Forms\Components\Section::make("Keyword Options")
                ->visible(fn(Forms\Get $get) => $get("type") === "keyword")
                ->schema([
                    Forms\Components\TextInput::make("url")
                        ->label("URL")
                        ->required(
                            fn(Forms\Get $get) => $get("type") === "keyword",
                        )
                        ->maxLength(255),
                    Forms\Components\TextInput::make("keyword")
                        ->label("Keyword")
                        ->required(
                            fn(Forms\Get $get) => $get("type") === "keyword",
                        )
                        ->maxLength(255),
                    Forms\Components\Toggle::make(
                        "keyword_case_sensitive",
                    )->label("Case Sensitive"),
                ]),

            Forms\Components\Section::make("Heartbeat Options")
                ->visible(fn(Forms\Get $get) => $get("type") === "heartbeat")
                ->schema([
                    Forms\Components\TextInput::make(
                        "heartbeat_grace_period_in_minutes",
                    )
                        ->label("Grace Period (minutes)")
                        ->required(
                            fn(Forms\Get $get) => $get("type") === "heartbeat",
                        )
                        ->numeric()
                        ->default(5),
                ]),

            // === BLOC AJOUTÉ ===
            Forms\Components\Section::make("Notifications")->schema([
                Forms\Components\CheckboxList::make("alertContacts")
                    ->relationship("alertContacts", "name")
                    ->label("Notifier ces contacts")
                    ->columns(2)
                    ->helperText(
                        "Sélectionnez les contacts qui recevront une alerte pour ce moniteur.",
                    ),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")->searchable(),
                Tables\Columns\BadgeColumn::make("uptime_status")
                    ->formatStateUsing(
                        fn(?MonitorStatus $state): string => $state
                            ? $state->value
                            : "",
                    )
                    ->label("Status")
                    ->colors([
                        "success" => "up",
                        "danger" => "down",
                        "warning" => "paused",
                    ]),
                Tables\Columns\TextColumn::make("type")->label("Type"),
                Tables\Columns\TextColumn::make("url")
                    ->label("Target")
                    ->searchable(),
                Tables\Columns\TextColumn::make("check_interval_minutes")
                    ->label("Interval (min)")
                    ->sortable(),
                Tables\Columns\TextColumn::make("last_checked_at")
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
        return [RelationManagers\CheckLogsRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListMonitors::route("/"),
            "create" => Pages\CreateMonitor::route("/create"),
            "edit" => Pages\EditMonitor::route("/{record}/edit"),
        ];
    }
}
