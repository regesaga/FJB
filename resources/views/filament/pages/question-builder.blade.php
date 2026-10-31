<x-filament-panels::page>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/fjb-listing-builder.css') }}">
    @endpush

    <livewire:admin.listing-builder.listing-builder-editor />
</x-filament-panels::page>
