<x-filament-panels::page>
    <div class="space-y-6">

        <div class="flex flex-col gap-4 sm:flex-row">
            <input
                type="text"
                wire:model.live.debounce.500ms="search"
                placeholder="Cari judul listing..."
                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900"
            >

            <select
                wire:model.live="listingType"
                class="rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900"
            >
                <option value="">Semua Jenis</option>
                <option value="product">Produk</option>
                <option value="service">Jasa</option>
                <option value="other">Lainnya</option>
            </select>
        </div>

        <div class="overflow-x-auto rounded-xl border dark:border-gray-700">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="p-4 text-left">Judul</th>
                        <th class="p-4 text-left">Jenis</th>
                        <th class="p-4 text-left">Kategori</th>
                        <th class="p-4 text-left">Subkategori</th>
                        <th class="p-4 text-left">Harga</th>
                        <th class="p-4 text-left">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($this->getListings()->paginate(15) as $listing)
                        <tr class="border-t dark:border-gray-700">
                            <td class="p-4">{{ $listing->title }}</td>
                            <td class="p-4">{{ ucfirst($listing->listing_type) }}</td>
                            <td class="p-4">{{ $listing->category?->name ?? '-' }}</td>
                            <td class="p-4">{{ $listing->subCategory?->name ?? '-' }}</td>
                            <td class="p-4">
                                {{ $listing->price !== null
                                    ? 'Rp ' . number_format((float) $listing->price, 0, ',', '.')
                                    : '-' }}
                            </td>
                            <td class="p-4">{{ ucfirst($listing->status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500">
                                Belum ada listing.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-filament-panels::page>