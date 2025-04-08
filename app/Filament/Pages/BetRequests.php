<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Tables;
use Filament\Pages\Page;
use App\Models\RequestBet;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class BetRequests extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static string $view = 'filament.pages.bet-requests';

    protected function getTableQuery()
    {
        return RequestBet::query()->latest()->where('status', 'paid');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('game')
                ->label('Game'),

            Tables\Columns\TextColumn::make('bet_type')
                ->label('Bet Type')
                ->getStateUsing(fn($record) => "{$record->game_type} {$record->game_code}"),

            Tables\Columns\TextColumn::make('selected_numbers')
                ->label('Selected Numbers'),

            Tables\Columns\TextColumn::make('amount')
                ->label('Unit amount'),

            Tables\Columns\TextColumn::make('total_amount')
                ->money('GHS', true),

            Tables\Columns\TextColumn::make('user.phone')
                ->label('Phone Number'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\EditAction::make()
                ->form([
                    Forms\Components\FileUpload::make('image')
                        ->label('Upload Image')
                        ->image()
                        ->directory('bet-requests'),

                    Forms\Components\Select::make('status')
                        ->options([
                            'paid' => 'Paid',
                            'staked' => 'Staked',
                        ])
                        ->required(),
                ]),
        ];
    }
}
