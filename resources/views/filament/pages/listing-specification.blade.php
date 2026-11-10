<x-filament-panels::page>
    @php
        $listing = $this->getListing();
        $specifications = $this->getSpecifications();
    @endphp

    @if (!$listing)
        <div class="rounded-xl border border-danger-300 bg-danger-50 p-6 text-danger-700">
            Listing tidak ditemukan.
        </div>
    @else
        <div class="space-y-6">

            {{-- INFORMASI LISTING --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ $listing->title }}
                </h2>

                <div class="mt-3 flex flex-wrap gap-3 text-sm text-gray-500">
                    <span>Kategori: {{ $listing->category?->name ?? '-' }}</span>
                    <span>•</span>
                    <span>Subkategori: {{ $listing->subCategory?->name ?? '-' }}</span>
                    <span>•</span>
                    <span>Penjual: {{ $listing->user?->name ?? '-' }}</span>
                </div>
            </div>

            {{-- SPESIFIKASI --}}
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <div class="border-b border-gray-200 p-5 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        Spesifikasi {{ $listing->subCategory?->name }}
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Daftar pertanyaan berdasarkan subkategori listing ini.
                    </p>
                </div>

                @if ($specifications->isEmpty())
                    <div class="p-8 text-center text-gray-500">
                        Belum ada pertanyaan yang dikonfigurasi untuk subkategori ini.
                    </div>
                @else
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($specifications as $specification)
                            <div class="grid gap-2 p-5 md:grid-cols-3">
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-white">
                                        {{ $specification['name'] }}
                                    </div>

                                    <div class="mt-1 text-xs text-gray-500">
                                        {{ ucfirst($specification['type']) }}

                                        @if ($specification['required'])
                                            • Wajib diisi
                                        @else
                                            • Opsional
                                        @endif
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    @if (
                                        $specification['value'] !== null &&
                                        trim((string) $specification['value']) !== ''
                                    )
                                        <span class="font-medium text-gray-800 dark:text-gray-200">
                                            {{ $specification['value'] }}
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                            Belum diisi
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="text-sm text-gray-500">
                Total pertanyaan: {{ $specifications->count() }}
                |
                Sudah dijawab:
                {{ $specifications->filter(fn ($item) => filled($item['value']))->count() }}
                |
                Belum dijawab:
                {{ $specifications->filter(fn ($item) => blank($item['value']))->count() }}
            </div>

        </div>
    @endif
</x-filament-panels::page>