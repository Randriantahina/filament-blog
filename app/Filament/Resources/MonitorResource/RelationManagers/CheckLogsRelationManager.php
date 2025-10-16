<?php

namespace App\Filament\Resources\MonitorResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CheckLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'checkLogs';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // We don't need a form here as logs are read-only
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('created_at')
            ->columns([
                Tables\Columns\IconColumn::make('is_up')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('status_code')
                    ->label('HTTP Code'),
                Tables\Columns\TextColumn::make('response_time_ms')
                    ->label('Response (ms)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('error_message')
                    ->label('Error')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Checked At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // We don't need create actions
            ])
            ->actions([
                // We don't need edit/delete actions
            ])
            ->bulkActions([
                // We don't need bulk actions
            ])
            ->defaultSort('created_at', 'desc');
    }
}