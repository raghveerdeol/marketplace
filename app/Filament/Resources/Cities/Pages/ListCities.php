<?php

namespace App\Filament\Resources\Cities\Pages;

use App\Filament\Imports\CityImporter;
use App\Filament\Resources\Cities\CityResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListCities extends ListRecords
{
    protected static string $resource = CityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(CityImporter::class)
                ->label('Import Cities')
                ->icon(Heroicon::OutlinedArrowUpTray)
                ->color('success'),
        ];
    }
}
