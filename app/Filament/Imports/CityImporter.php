<?php

namespace App\Filament\Imports;

use App\Models\City;
use App\Models\Province;
use Filament\Actions\Imports\Exceptions\RowImportFailedException;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class CityImporter extends Importer
{
    protected static ?string $model = City::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('province')
                ->relationship(resolveUsing: function(string $state): ?Province {
                    return Province::query()
                        ->where('name', 'ILIKE', trim($state))
                        ->first();
                })
                ->rules(['required'])
                ->requiredMapping(),
        ];
    }

    public function resolveRecord(): City
    {
        $userId = $this->import->user_id;
        $provinceName = trim($this->data['province'] ?? '');

        $province = Province::query()
            ->where('name', 'ILIKE', $provinceName)
            ->select('id')
            ->first();

        if (!$province) {
            throw new RowImportFailedException("Provinces not found: {$provinceName}");
        }

        $city = City::firstOrNew([
            'name' => $this->data['name'],
            'province_id' => $province->id,
        ]);

        if(!$city->exists){
            $city->created_by = $userId; 
        }

        $city->updated_by = $userId;

        return $city;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your city import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
