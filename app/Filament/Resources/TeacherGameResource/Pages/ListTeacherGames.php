<?php

namespace App\Filament\Resources\TeacherGameResource\Pages;

use App\Filament\Resources\TeacherGameResource;
use Filament\Resources\Pages\ListRecords;

class ListTeacherGames extends ListRecords
{
    protected static string $resource = TeacherGameResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action for teacher games in admin
        ];
    }
}
