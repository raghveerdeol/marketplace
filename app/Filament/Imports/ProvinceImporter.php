<?php

namespace App\Filament\Imports;

use App\Models\Province;
use App\Models\Region;
use Filament\Actions\Imports\Exceptions\RowImportFailedException;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class ProvinceImporter extends Importer
{
    protected static ?string $model = Province::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->ignoreBlankState()
                ->rules(['required', 'max:255']),
            ImportColumn::make('code')
                ->requiredMapping()
                ->ignoreBlankState()
                ->rules(['required', 'max:255']),
            ImportColumn::make('region')
                ->relationship(resolveUsing: function(string $state): ?Region {
                    return Region::query()
                        ->where('name', 'ILIKE', trim($state))
                        ->first();
                })
                ->rules(['required'])
                ->requiredMapping(),
        ];
    }

    public function resolveRecord(): Province
    {
        $userId = $this->import->user_id;
        $regionName = trim($this->data['region'] ?? '');

        $region = Region::query()
            ->where('name', 'ILIKE', $regionName)
            ->select('id')
            ->first();

        if (!$region) {
            throw new RowImportFailedException("Region not found: {$regionName}");
        }

        $province = Province::firstOrNew([
            'name' => trim($this->data['name']),
            'code' => trim($this->data['code']),
            'region_id' => $region->id
        ]);

        if(!$province->exists){
            $province->created_by = $userId; 
        }

        $province->updated_by = $userId;

        return $province;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your province import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
