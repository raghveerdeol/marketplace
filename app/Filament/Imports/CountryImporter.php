<?php

namespace App\Filament\Imports;

use App\Models\Country;
use Filament\Actions\Imports\Exceptions\RowImportFailedException;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Carbon\CarbonInterface;

class CountryImporter extends Importer
{
    protected static ?string $model = Country::class;

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
            ImportColumn::make('latitude')
                ->ignoreBlankState(),
            ImportColumn::make('longitude')
                ->ignoreBlankState(),
        ];
    }

    public function resolveRecord(): Country
    {
        $importUser = $this->import->user_id ?? null;

        $country = Country::firstOrNew([
            'name' => trim($this->data['name']),
            'code' => trim($this->data['code']),
        ]);

        $country->fill([
            'latitude' => isset($this->data['latitude']) ? trim($this->data['latitude']) : null,
            'longitude' => isset($this->data['longitude']) ? trim($this->data['longitude']) : null,
        ]);

        if(!$country->exists) {
            $country->created_by = $importUser;
        }

        $country->updated_by = $importUser;

        return $country;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your country import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

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
