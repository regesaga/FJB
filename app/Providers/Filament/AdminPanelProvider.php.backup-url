<?php

namespace App\Providers\Filament;

use App\Models\Category;
use App\Http\Middleware\EnsureUserIsNotSuspended;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Navigation\NavigationItem;
use Filament\Support\Icons\Heroicon;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->whereIn('listing_type', ['product', 'service', 'other'])
            ->with([
                'subCategories' => fn ($query) => $query
                    ->where('is_active', true)
                    ->orderBy('sort')
                    ->orderBy('name'),
            ])
            ->orderBy('sort')
            ->orderBy('name')
            ->get();

        $navigationItems = [
            NavigationItem::make('Produk')
                ->key('Produk')
                ->icon(Heroicon::OutlinedRectangleStack)
                ->sort(1),
        ];

        foreach ($categories as $category) {
            $group = match ($category->listing_type) {
                'product' => 'PRODUK',
                'service' => 'JASA',
                'other' => 'LAINNYA',
                default => null,
            };

            if (! $group) {
                continue;
            }

            $categoryUrl = '/admin/listing-manager?' . http_build_query([
                'listingType' => $category->listing_type,
                'categoryId' => $category->id,
            ]);

            $childItems = [];

            foreach ($category->subCategories as $subCategory) {
                $childItems[] = NavigationItem::make($subCategory->name)
                    ->url('/admin/listing-manager?' . http_build_query([
                        'listingType' => $category->listing_type,
                        'categoryId' => $category->id,
                        'subCategoryId' => $subCategory->id,
                    ]))
                    ->sort($subCategory->sort);
            }

            $navigationItems[] = NavigationItem::make($category->name)
                ->key('category-' . $category->id)
                ->group($group)
                ->icon(Heroicon::OutlinedFolder)
                ->url($categoryUrl)
                ->childItems($childItems)
                ->sort($category->sort);
        }

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('FJB Admin')
            ->brandLogoHeight('2.5rem')
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(Width::Full)
            ->navigationItems($navigationItems)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\Filament\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Pages'),
                for: 'App\Filament\Pages'
            )
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(
                in: app_path('Filament/Widgets'),
                for: 'App\Filament\Widgets'
            )
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                EnsureUserIsNotSuspended::class,
            ]);
    }
}