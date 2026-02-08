<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherGameResource\Pages;
use App\Models\Game;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TeacherGameResource extends Resource
{
    protected static ?string $model = Game::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Game Guru';

    protected static ?string $modelLabel = 'Game Guru';

    protected static ?string $pluralModelLabel = 'Game Guru';

    protected static ?string $navigationGroup = 'Manajemen Game';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        // Reuse GameResource form or simplify for viewing
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Game')
                    ->schema([
                        Forms\Components\Select::make('teacher_id')
                            ->label('Dibuat Oleh Guru')
                            ->relationship('teacher', 'name')
                            ->disabled(),
                            
                        Forms\Components\Select::make('template_id')
                            ->label('Template Game')
                            ->relationship('template', 'name')
                            ->disabled(),

                        Forms\Components\TextInput::make('title')
                            ->label('Judul Game')
                            ->disabled(),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->disabled()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('category')
                            ->label('Kategori')
                            ->options([
                                'matematika' => 'Matematika',
                                'bahasa' => 'Bahasa Indonesia',
                                'ipa' => 'IPA',
                                'ips' => 'IPS',
                                'umum' => 'Pengetahuan Umum',
                            ])
                            ->disabled(),

                        Forms\Components\Select::make('class')
                            ->label('Kelas')
                            ->disabled(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('thumbnail')
                            ->label('Thumbnail')
                            ->image()
                            ->disabled(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->circular(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Guru')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('template.name')
                    ->label('Template')
                    ->sortable()
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->colors([
                        'primary' => 'matematika',
                        'success' => 'bahasa',
                        'warning' => 'ipa',
                        'danger' => 'ips',
                        'info' => 'umum',
                    ]),

                Tables\Columns\TextColumn::make('class')
                    ->label('Kelas')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state) => $state ? "Kelas {$state}" : '-'),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('teacher_id')
                    ->label('Berdasarkan Guru')
                    ->relationship('teacher', 'name'),
                    
                Tables\Filters\SelectFilter::make('template_id')
                    ->label('Berdasarkan Template')
                    ->relationship('template', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereNotNull('teacher_id');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeacherGames::route('/'),
            'view' => Pages\ViewTeacherGame::route('/{record}'),
        ];
    }
}
