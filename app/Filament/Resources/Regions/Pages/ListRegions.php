<?php

namespace App\Filament\Resources\Regions\Pages;

use App\Filament\Imports\RegionImporter;
use App\Filament\Resources\Regions\RegionResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListRegions extends ListRecords
{
    protected static string $resource = RegionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(RegionImporter::class)
                ->label('Import Regions')
                ->icon(Heroicon::OutlinedArrowUpTray)
                ->color('success'),
        ];
    }
}
