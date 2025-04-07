<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Tables;
use Filament\Pages\Page;
use App\Models\RequestBet;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Concerns\InteractsWithTable;

class StakedBets extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.staked-bets';

    protected function getTableQuery()
    {
        return RequestBet::query()->latest()->whereIn('status', ['staked', 'won', 'lost']);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('user.firstname')
                ->label('Full name')
                ->getStateUsing(fn($record) => "{$record->user->firstname} {$record->user->lastname}")
                ->searchable(),

            Tables\Columns\TextColumn::make('user.phone')
                ->label('Phone Number')
                ->searchable(),

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
                ->label('Total amount')
                ->money('GHS', true),

            Tables\Columns\TextColumn::make('status')
                ->label('Status'),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'staked' => 'Staked',
                    'won' => 'Won',
                    'lost' => 'Lost',
                ])
                ->placeholder('All Statuses'),


            Tables\Filters\Filter::make('created_at')
                ->form([
                    Forms\Components\DatePicker::make('start_date'),
                    Forms\Components\DatePicker::make('end_date'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['start_date'],
                            fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['end_date'],
                            fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })
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
                            'staked' => 'Staked',
                            'won' => 'Won',
                            'lost' => 'Lost',
                        ])
                        ->required(),
                ]),
        ];
    }
}
