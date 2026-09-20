<?php

namespace App\Filament\Imports;

use App\Models\Country;
use App\Models\Region;
use Carbon\CarbonInterface;
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
        $userId = $this->import->user_id;
        $countryName = trim($this->data['country'] ?? '');
    
        $country = Country::query()
            ->where('name', 'ILIKE', $countryName)
            ->select('id')
            ->first();

        if (!$country) {
            throw new RowImportFailedException("Paese not found: {$countryName}");
        }

        $region = Region::firstOrNew([
            'name' => trim($this->data['name']),
            'country_id' => $country->id,
        ]);

        if(!$region->exists){
            $region->created_by = $userId; 
        }

        $region->updated_by = $userId;

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

    /**
       * @return int | array<int> | null
    */
    public function getJobBackoff(): int | array | null
    {
        return [60, 120, 300, 600];
    }


    public function getJobRetryUntil(): ?CarbonInterface
    {
        return now()->addMinutes(20);
    }
}
