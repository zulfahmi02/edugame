<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GameResource\Pages;
use App\Filament\Resources\GameResource\RelationManagers;
use App\Models\Game;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class GameResource extends Resource
{
    protected static ?string $model = Game::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationLabel = 'Game Mingguan';

    protected static ?string $modelLabel = 'Game Mingguan';

    protected static ?string $pluralModelLabel = 'Game Mingguan';

    protected static ?string $navigationGroup = 'Manajemen Game';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Game')
                    ->schema([
                        Forms\Components\Select::make('template_id')
                            ->label('Template Game')
                            ->relationship('template', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->helperText('Pilih model permainan yang akan digunakan (Quiz, TTS, dsb).')
                            ->visible(fn(Forms\Get $get) => !$get('custom_template_enabled')),

                        Forms\Components\TextInput::make('title')
                            ->label('Judul Game')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn(string $operation, $state, Forms\Set $set) =>
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug (URL)')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('URL-friendly version dari judul'),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
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
                            ->searchable(),

                        Forms\Components\Select::make('class')
                            ->label('Kelas')
                            ->options([
                                '1' => 'Kelas 1',
                                '2' => 'Kelas 2',
                                '3' => 'Kelas 3',
                                '4' => 'Kelas 4',
                                '5' => 'Kelas 5',
                                '6' => 'Kelas 6',
                            ])
                            ->helperText('Kosongkan jika untuk semua kelas')
                            ->searchable(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('thumbnail')
                            ->label('Thumbnail')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->directory('games/thumbnails')
                            ->maxSize(2048)
                            ->helperText('Maksimal 2MB. Rekomendasi: 800x600px'),

                        Forms\Components\FileUpload::make('game_images')
                            ->label('Gambar Game')
                            ->image()
                            ->multiple()
                            ->directory('games/images')
                            ->maxSize(2048)
                            ->maxFiles(5)
                            ->helperText('Upload hingga 5 gambar untuk game'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Kustomisasi Lanjutan')
                    ->description('Hanya gunakan jika Anda ingin memodifikasi kode template secara spesifik untuk game ini.')
                    ->schema([
                        Forms\Components\Toggle::make('custom_template_enabled')
                            ->label('Aktifkan Mode Kustom')
                            ->live()
                            ->helperText('Jika aktif, kode di bawah akan menimpa template standar.'),

                        Forms\Components\Textarea::make('html_template')
                            ->label('Kode HTML Kustom')
                            ->rows(10)
                            ->columnSpanFull()
                            ->placeholder('Masukkan kode HTML di sini')
                            ->visible(fn(Forms\Get $get) => $get('custom_template_enabled')),
                            
                        Forms\Components\Textarea::make('css_style')
                            ->label('Kode CSS Kustom')
                            ->rows(5)
                            ->columnSpanFull()
                            ->visible(fn(Forms\Get $get) => $get('custom_template_enabled')),
                            
                        Forms\Components\Textarea::make('js_code')
                            ->label('Kode JS Kustom')
                            ->rows(5)
                            ->columnSpanFull()
                            ->visible(fn(Forms\Get $get) => $get('custom_template_enabled')),
                    ])
                    ->collapsed()
                    ->columnSpanFull(),

                Forms\Components\Section::make('Pengaturan')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->helperText('Game hanya muncul jika aktif'),

                        Forms\Components\TextInput::make('order')
                            ->label('Urutan Tampilan')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Semakin kecil angka, semakin di atas'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Daftar Soal')
                    ->schema([
                        Forms\Components\Repeater::make('questions')
                            ->relationship()
                            ->schema([
                                Forms\Components\Textarea::make('question_text')
                                    ->label('Teks Pertanyaan')
                                    ->required()
                                    ->rows(2)
                                    ->columnSpanFull(),
                                
                                Forms\Components\FileUpload::make('image')
                                    ->label('Gambar Pertanyaan')
                                    ->image()
                                    ->directory('questions/images')
                                    ->columnSpanFull(),

                                Forms\Components\TextInput::make('correct_answer')
                                    ->label('Jawaban Benar')
                                    ->required(),

                                Forms\Components\KeyValue::make('options')
                                    ->label('Pilihan Jawaban (JSON)')
                                    ->keyLabel('Opsi')
                                    ->valueLabel('Nilai')
                                    ->addActionLabel('Tambah Opsi'),

                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('points')
                                            ->label('Poin')
                                            ->numeric()
                                            ->default(10)
                                            ->required(),
                                        
                                        Forms\Components\Select::make('difficulty')
                                            ->label('Kesulitan')
                                            ->options([
                                                'easy' => 'Mudah',
                                                'medium' => 'Sedang',
                                                'hard' => 'Sulit',
                                            ])
                                            ->default('medium'),

                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Aktif')
                                            ->default(true),
                                    ]),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['question_text'] ?? 'Soal Baru')
                            ->collapsible()
                            ->collapsed()
                            ->addActionLabel('Tambah Soal Baru')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-game.png')),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->colors([
                        'primary' => 'matematika',
                        'success' => 'bahasa',
                        'warning' => 'ipa',
                        'danger' => 'ips',
                        'info' => 'umum',
                    ])
                    ->searchable(),

                Tables\Columns\TextColumn::make('class')
                    ->label('Kelas')
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state) => $state ? "Kelas {$state}" : '-')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('questions_count')
                    ->label('Jumlah Soal')
                    ->counts('questions')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'matematika' => 'Matematika',
                        'bahasa' => 'Bahasa Indonesia',
                        'ipa' => 'IPA',
                        'ips' => 'IPS',
                        'umum' => 'Pengetahuan Umum',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
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
            ])
            ->defaultSort('order', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\QuestionsRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereNull('teacher_id')
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGames::route('/'),
            'create' => Pages\CreateGame::route('/create'),
            'view' => Pages\ViewGame::route('/{record}'),
            'edit' => Pages\EditGame::route('/{record}/edit'),
        ];
    }
}
