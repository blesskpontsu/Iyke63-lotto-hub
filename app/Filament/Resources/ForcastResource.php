<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Forcast;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\ForcastResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ForcastResource\RelationManagers;

class ForcastResource extends Resource
{
    protected static ?string $model = Forcast::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('company')
                    ->options([
                        'Afriluck NLA' => 'Afriluck NLA',
                        'NLA' => 'NLA',
                        'UK49S' => 'UK49S',
                        'Alpha Lotto' => 'Alpha Lotto',
                    ])
                    ->label('Select Lotto company')
                    ->required(),
                TextInput::make('game')
                    ->required()
                    ->maxLength(255)
                    ->label('Name of Lotto'),
                DateTimePicker::make('draw_time')
                    ->required(),
                FileUpload::make('image')
                    ->disk('public')
                    ->image()
                    ->imageEditor()
                    ->directory('forcast')
                    ->storeFileNamesIn('forcast')
                    ->imageResizeTargetWidth(800)
                    ->imageResizeMode('contain')
                    ->imageResizeUpscale(false)
                    ->previewable(true) // Ensure preview is enabled
                    ->openable() // Allow opening in new tab
                    ->downloadable() // Allow downloading
                    ->loadingIndicatorPosition('right') // Loading indicator position
                    ->panelAspectRatio('2:1') // Preview panel aspect ratio
                    ->panelLayout('integrated') // Preview panel layout
                    ->removeUploadedFileButtonPosition('right') // Remove button position
                    ->uploadButtonPosition('right') // Upload button position
                    ->uploadProgressIndicatorPosition('right') // Progress indicator position
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company')
                    ->searchable(),
                Tables\Columns\TextColumn::make('game')
                    ->searchable(),
                Tables\Columns\TextColumn::make('draw_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image')
                    ->disk('public')
                    ->height(50)
                    ->width(50)
                    ->square()
                    ->checkFileExistence(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ManageForcasts::route('/'),
        ];
    }
}
