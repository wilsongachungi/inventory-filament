<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ProductOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make("Total Products",Product::count()),
            Stat::make("Total Category",Category::count()),
            Stat::make("Total Invoices",Purchase::count())
            ->description("Purchase Invoices")
            ->descriptionColor("success"),
        ];
    }
}
