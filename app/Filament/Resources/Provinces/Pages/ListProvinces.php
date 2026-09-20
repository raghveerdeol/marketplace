<?php

namespace App\Filament\Resources\Provinces\Pages;

use App\Filament\Imports\ProvinceImporter;
use App\Filament\Resources\Provinces\ProvinceResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListProvinces extends ListRecords
{
    protected static string $resource = ProvinceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(ProvinceImporter::class)
                ->label('Import Provinces')
                ->icon(Heroicon::OutlinedArrowUpTray)
                ->color('success'),
        ];
    }
}
