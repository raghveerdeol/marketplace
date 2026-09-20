<?php

namespace App\Filament\Resources\Countries\Schemas;

use App\Models\Country;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CountryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->trim()
                    ->label('Name')
                    ->columnSpan(1)
                    ->live()
                    ->hint(function($state){
                        if(!$state) return null;
                        $countryExsists = Country::query()
                            ->where('name', 'ILIKE', trim($state))
                            ->count();
                        return $countryExsists > 0 ? 'Exsists a saved country with same name!' : null;
                    })
                    ->hintColor('danger'),
                TextInput::make('code')
                    ->nullable()
                    ->trim()
                    ->label('Code')
                    ->live(true)
                    ->hint(function($state){
                        if(!$state) return null;
                        $countryExsists = Country::query()
                            ->where('code', 'ILIKE', trim($state))
                            ->count();
                        return $countryExsists > 0 ? 'Exsists a saved country with same code!' : null;
                    })
                    ->hintColor('danger'),
            ])
            ->columns(2);
    }
}
