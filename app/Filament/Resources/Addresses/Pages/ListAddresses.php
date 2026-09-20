<?php

namespace App\Filament\Resources\Addresses\Pages;

use App\Filament\Imports\ProductImporter;
use App\Filament\Resources\Addresses\AddressResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListAddresses extends ListRecords
{
    protected static string $resource = AddressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(ProductImporter::class)
                ->label('Import Products')
                ->icon(Heroicon::OutlinedArrowUpTray)
                ->color('success'),
        ];
    }
}
