<?php

namespace App\Filament\Pages;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected int|string|array $columnSpan = 'full';

    public function getColumns(): int|string|array
    {
        return 2;
    }
}
