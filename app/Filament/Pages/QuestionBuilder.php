<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class QuestionBuilder extends Page
{
    protected static ?string $navigationLabel = 'Kelola Pertanyaan';

    protected static ?string $navigationParentItem = 'Produk';

    protected static ?int $navigationSort = 3;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected string $view = 'filament.pages.question-builder';
}
