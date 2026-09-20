<?php

namespace App\Filament\Imports;

use App\Models\Country;
use App\Models\Region;
use Filament\Actions\Imports\Exceptions\RowImportFailedException;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class RegionImporter extends Importer
{
    protected static ?string $model = Region::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->ignoreBlankState()
                ->rules(['required', 'max:255']),
            ImportColumn::make('country')
                ->relationship(resolveUsing: function(string $state): ?Country {
                    return Country::query()
                        ->where('name', 'ILIKE', trim($state))
                        ->first();
                })
                ->rules(['required'])
                ->requiredMapping(),
        ];
    }

    public function resolveRecord(): Region
    {
        if(!isset($this->data['name'])) throw new RowImportFailedException("Error, column or data not found!");

        $name = isset($this->data['name']) ? trim($this->data['name']) : null;
        $importUser = $this->import->user() ?? null;

        $region = Region::query()
            ->where('name', 'ILIKE', $name)
            ->first();

        if(!$region){
            $newRegion = [
                'name' => $name,
                'created_by' => $importUser,
                'updated_by' => $importUser,
            ];
            $region = Region::create($newRegion);
        }

        return $region;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your region import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
