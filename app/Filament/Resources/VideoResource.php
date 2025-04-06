<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Video;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\VideoResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VideoResource\RelationManagers;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->minLength(3),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->minLength(3)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('url')
                    ->label('YouTube URL')
                    ->required()
                    ->minLength(3)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('embedurl', self::convertToEmbedUrl($state));
                    }),
                Forms\Components\TextInput::make('embedurl')
                    ->label('Embed URL')
                    ->disabled()
                    ->dehydrated()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('embedurl')
                    ->label('Video')
                    ->url(fn($record) => $record->embedurl, true) // true opens in new tab
                    ->openUrlInNewTab()
                    ->formatStateUsing(fn() => 'Preview Video'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Date')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageVideos::route('/'),
        ];
    }

    private static function convertToEmbedUrl($url)
    {
        if (preg_match('/youtu\.be\/([^\?]+)(.*)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1] . $matches[2];
        }

        if (preg_match('/youtube\.com\/watch\?v=([^\&]+)(.*)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1] . $matches[2];
        }

        return $url;
    }
}
