<x-filament-panels::page>
    @php
        $statistics = $this->getStatistics();
        $listings = $this->getListings();
        $attributes = $this->getDynamicAttributes();

        $cards = [
            ['key' => 'total', 'label' => 'Total Listing', 'icon' => '▦', 'color' => 'blue'],
            ['key' => 'published', 'label' => 'Published', 'icon' => '✓', 'color' => 'green'],
            ['key' => 'pending', 'label' => 'Menunggu Review', 'icon' => '◷', 'color' => 'orange'],
            ['key' => 'draft', 'label' => 'Draft', 'icon' => '✎', 'color' => 'gray'],
            ['key' => 'rejected', 'label' => 'Ditolak', 'icon' => '×', 'color' => 'red'],
        ];
    @endphp

    <style>
        .fjb-dashboard {
            --fjb-primary: #2563eb;
            --fjb-text: #172033;
            --fjb-muted: #788398;
            --fjb-border: #e8edf4;
            color: var(--fjb-text);
            width: 100%;
        }

        .dark .fjb-dashboard {
            --fjb-text: #f1f5f9;
            --fjb-muted: #a1aabd;
            --fjb-border: #303b4d;
        }

        .fjb-dashboard * {
            box-sizing: border-box;
        }

        .fjb-hero {
            position: relative;
            overflow: hidden;
            padding: 30px;
            border-radius: 18px;
            color: white;
            background: linear-gradient(120deg, #172554, #1d4ed8 65%, #3b82f6);
            margin-bottom: 24px;
        }

        .fjb-hero:after {
            content: "";
            position: absolute;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            right: -65px;
            top: -110px;
            background: rgba(255,255,255,.10);
        }

        .fjb-hero h2 {
            color: white;
            font-size: 25px;
            font-weight: 800;
            margin: 0 0 8px;
        }

        .fjb-hero p {
            color: #dbeafe;
            margin: 0;
            font-size: 14px;
        }

        .fjb-hero-label {
            display: inline-block;
            padding: 6px 11px;
            border: 1px solid rgba(255,255,255,.25);
            background: rgba(255,255,255,.12);
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 14px;
            letter-spacing: .7px;
        }

        .fjb-stat-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 15px;
            margin-bottom: 24px;
        }

        .fjb-stat {
            background: white;
            border: 1px solid var(--fjb-border);
            border-radius: 15px;
            padding: 19px;
            min-width: 0;
            box-shadow: 0 3px 12px rgba(15,23,42,.035);
            transition: transform .2s, box-shadow .2s;
        }

        .dark .fjb-stat,
        .dark .fjb-panel {
            background: #111827;
        }

        .fjb-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(15,23,42,.08);
        }

        .fjb-stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .fjb-stat-label {
            color: var(--fjb-muted);
            font-size: 12px;
            font-weight: 600;
        }

        .fjb-stat-icon {
            display: flex;
            width: 38px;
            height: 38px;
            border-radius: 11px;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 800;
        }

        .fjb-stat-number {
            font-size: 29px;
            font-weight: 800;
            margin-top: 13px;
            letter-spacing: -1px;
            color: var(--fjb-text);
        }

        .fjb-stat-blue .fjb-stat-icon { background: #dbeafe; color: #2563eb; }
        .fjb-stat-green .fjb-stat-icon { background: #dcfce7; color: #15803d; }
        .fjb-stat-orange .fjb-stat-icon { background: #ffedd5; color: #c2410c; }
        .fjb-stat-gray .fjb-stat-icon { background: #f1f5f9; color: #475569; }
        .fjb-stat-red .fjb-stat-icon { background: #fee2e2; color: #dc2626; }

        .fjb-panel {
            background: white;
            border: 1px solid var(--fjb-border);
            border-radius: 16px;
            margin-bottom: 20px;
            overflow: hidden;
            box-shadow: 0 3px 12px rgba(15,23,42,.035);
        }

        .fjb-panel-header {
            padding: 20px 22px;
            border-bottom: 1px solid var(--fjb-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .fjb-panel-title {
            color: var(--fjb-text);
            font-size: 16px;
            font-weight: 800;
            margin: 0;
        }

        .fjb-panel-subtitle {
            color: var(--fjb-muted);
            font-size: 12px;
            margin-top: 5px;
        }

        .fjb-filter {
            padding: 18px 22px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .fjb-search {
            flex: 1;
            min-width: 220px;
            position: relative;
        }

        .fjb-search input,
        .fjb-select {
            width: 100%;
            height: 43px;
            border: 1px solid var(--fjb-border);
            border-radius: 10px;
            padding: 0 13px;
            background: white;
            color: #172033;
            font-size: 13px;
            outline: none;
        }

        .dark .fjb-search input,
        .dark .fjb-select {
            background: #1f2937;
            color: white;
        }

        .fjb-search input:focus,
        .fjb-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,.12);
        }

        .fjb-select {
            width: 190px;
            cursor: pointer;
        }

        .fjb-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .fjb-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        .fjb-table th {
            background: #f8fafc;
            color: #64748b;
            padding: 14px 17px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .6px;
            font-weight: 800;
            white-space: nowrap;
        }

        .dark .fjb-table th {
            background: #1f2937;
            color: #cbd5e1;
        }

        .fjb-table td {
            padding: 16px 17px;
            border-top: 1px solid var(--fjb-border);
            font-size: 12px;
            color: var(--fjb-text);
            vertical-align: middle;
        }

        .fjb-table tbody tr:hover {
            background: #f8fafc;
        }

        .dark .fjb-table tbody tr:hover {
            background: #1f2937;
        }

        .fjb-product {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 220px;
        }

        .fjb-product-image {
            width: 55px;
            height: 48px;
            border-radius: 9px;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
            flex-shrink: 0;
            overflow: hidden;
        }

        .fjb-product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .fjb-product-name {
            font-size: 13px;
            font-weight: 750;
            color: var(--fjb-text);
            line-height: 1.4;
        }

        .fjb-product-type {
            color: var(--fjb-muted);
            font-size: 11px;
            margin-top: 4px;
        }

        .fjb-price {
            font-weight: 800;
            color: #2563eb;
            white-space: nowrap;
        }

        .fjb-badge {
            display: inline-flex;
            padding: 6px 10px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .fjb-badge-published { background: #dcfce7; color: #15803d; }
        .fjb-badge-pending { background: #ffedd5; color: #c2410c; }
        .fjb-badge-draft { background: #f1f5f9; color: #475569; }
        .fjb-badge-rejected { background: #fee2e2; color: #b91c1c; }

        .fjb-empty {
            padding: 65px 20px;
            text-align: center;
        }

        .fjb-empty-icon {
            width: 68px;
            height: 68px;
            border-radius: 20px;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 30px;
        }

        .fjb-empty h3 {
            font-size: 16px;
            font-weight: 800;
            color: var(--fjb-text);
            margin-bottom: 6px;
        }

        .fjb-empty p {
            font-size: 12px;
            color: var(--fjb-muted);
        }

        .fjb-pagination {
            padding: 17px 22px;
            border-top: 1px solid var(--fjb-border);
        }

        .fjb-count {
            color: var(--fjb-muted);
            font-size: 12px;
        }

        @media(max-width: 1100px) {
            .fjb-stat-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media(max-width: 640px) {
            .fjb-hero {
                padding: 23px 19px;
            }

            .fjb-hero h2 {
                font-size: 21px;
            }

            .fjb-stat-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .fjb-stat {
                padding: 14px;
            }

            .fjb-stat-number {
                font-size: 24px;
            }

            .fjb-filter {
                padding: 14px;
            }

            .fjb-search,
            .fjb-select {
                width: 100%;
                min-width: 100%;
            }

            .fjb-panel-header {
                padding: 16px;
            }
        }

        .fjb-filter {
            align-items: stretch;
        }

        .fjb-filter .fjb-select {
            width: auto;
            min-width: 165px;
            flex: 1 1 165px;
        }

        .fjb-sort-button {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 0;
            border: 0;
            background: transparent;
            color: inherit;
            font: inherit;
            font-weight: 800;
            text-transform: inherit;
            letter-spacing: inherit;
            cursor: pointer;
            white-space: nowrap;
        }

        .fjb-sort-button:hover {
            color: #2563eb;
        }

        .fjb-info-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            min-width: 230px;
        }

        .fjb-info-button {
            border: 0;
            border-radius: 7px;
            padding: 7px 10px;
            color: white;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
            transition: opacity .15s, transform .15s;
        }

        .fjb-info-button:hover {
            opacity: .86;
            transform: translateY(-1px);
        }

        .fjb-modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 18px;
            background: rgba(15, 23, 42, .65);
        }

        .fjb-modal {
            width: min(900px, 100%);
            max-height: 90vh;
            overflow-y: auto;
            padding: 24px;
            border-radius: 16px;
            background: white;
            color: #172033;
            box-shadow: 0 20px 60px rgba(0,0,0,.25);
        }

        .dark .fjb-modal {
            background: #111827;
            color: #f1f5f9;
        }

        .fjb-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding-bottom: 15px;
            margin-bottom: 18px;
            border-bottom: 1px solid #e5e7eb;
        }

        .fjb-modal-title {
            margin: 0;
            font-size: 19px;
            font-weight: 800;
        }

        .fjb-modal-close {
            border: 0;
            border-radius: 8px;
            padding: 8px 12px;
            background: #fee2e2;
            color: #b91c1c;
            font-weight: 700;
            cursor: pointer;
        }

        .fjb-info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .fjb-info-item {
            padding: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            min-width: 0;
        }

        .fjb-info-label {
            display: block;
            margin-bottom: 5px;
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .fjb-info-value {
            overflow-wrap: anywhere;
            font-size: 13px;
            font-weight: 600;
        }

        .fjb-photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 10px;
        }

        .fjb-photo-grid img {
            width: 100%;
            height: 130px;
            border-radius: 9px;
            object-fit: cover;
        }

        @media(max-width: 640px) {
            .fjb-filter .fjb-select {
                width: 100%;
                min-width: 100%;
            }

            .fjb-info-grid {
                grid-template-columns: 1fr;
            }

            .fjb-modal {
                padding: 17px;
            }

            .fjb-modal-backdrop {
                padding: 8px;
            }
        }

    </style>

    <div class="fjb-dashboard">

        {{-- HEADER --}}
        <section class="fjb-hero">
            <div class="fjb-hero-label">FJB ADMINISTRATION</div>
            <h2>{{ $this->getPageHeading() }}</h2>
            <p>Kelola, pantau, dan awasi seluruh aktivitas listing marketplace FJB.</p>
        </section>

        {{-- STATISTIK --}}
        <section class="fjb-stat-grid">
            @foreach ($cards as $card)
                <div class="fjb-stat fjb-stat-{{ $card['color'] }}">
                    <div class="fjb-stat-top">
                        <span class="fjb-stat-label">{{ $card['label'] }}</span>
                        <span class="fjb-stat-icon">{{ $card['icon'] }}</span>
                    </div>
                    <div class="fjb-stat-number">
                        {{ number_format($statistics[$card['key']] ?? 0) }}
                    </div>
                </div>
            @endforeach
        </section>

        {{-- DATA LISTING --}}
        <section class="fjb-panel">
            <div class="fjb-panel-header">
    <div>
        <h3 class="fjb-panel-title">Manajemen Listing</h3>
        <div class="fjb-panel-subtitle">
            Data iklan dan produk yang terdaftar di marketplace
        </div>
    </div>

    <div style="display:flex;align-items:center;gap:12px;">
        <span class="fjb-count">
            {{ number_format($listings->total()) }} data
        </span>

        <a href="{{ \App\Filament\Resources\Products\ProductResource::getUrl('create', array_filter([
    'category_id' => $this->categoryId,
    'sub_category_id' => $this->subCategoryId,
])) }}"
   style="background:#2563eb;color:white;padding:10px 15px;border-radius:9px;font-size:12px;font-weight:700;text-decoration:none;">
    + Tambah Produk
</a>
    </div>
</div>

            {{-- FILTER --}}
            <div class="fjb-filter">
                <div class="fjb-search">
                    <input
                        type="search"
                        wire:model.live.debounce.400ms="search"
                        placeholder="Cari nama produk atau penjual..."
                    >
                </div>

               

                <select wire:model.live="subCategoryId" class="fjb-select">
                    <option value="">Semua Subkategori</option>
                    @foreach ($this->getSubCategories() as $subCategory)
                        <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="status" class="fjb-select">
                    <option value="">Semua Status</option>
                    <option value="draft">Draft</option>
                    <option value="pending">Menunggu Review</option>
                    <option value="published">Published</option>
                    <option value="rejected">Rejected</option>
                </select>

                <select wire:model.live="publication" class="fjb-select">
                    <option value="">Semua Publikasi</option>
                    <option value="published">Sudah Published</option>
                    <option value="unpublished">Belum Published</option>
                </select>

                <select wire:model.live="perPage" class="fjb-select">
                    <option value="50">50 data</option>
                    <option value="100">100 data</option>
                    <option value="all">Semua data</option>
                </select>
            </div>

            {{-- TABEL --}}
            <div class="fjb-table-wrap">
                <table class="fjb-table">
                    <thead>
                        <tr>
                            <th>
                                <button type="button" class="fjb-sort-button" wire:click="sortBy('title')">
                                    Produk / Listing
                                    {{ $sortField === 'title' ? ($sortDirection === 'asc' ? '↑' : '↓') : '↕' }}
                                </button>
                            </th>
                            <th>
                                <button type="button" class="fjb-sort-button" wire:click="sortBy('category')">
                                    Kategori
                                    {{ $sortField === 'category' ? ($sortDirection === 'asc' ? '↑' : '↓') : '↕' }}
                                </button>
                            </th>
                            <th>
                                <button type="button" class="fjb-sort-button" wire:click="sortBy('subcategory')">
                                    Subkategori
                                    {{ $sortField === 'subcategory' ? ($sortDirection === 'asc' ? '↑' : '↓') : '↕' }}
                                </button>
                            </th>
                            <th>
                                <button type="button" class="fjb-sort-button" wire:click="sortBy('price')">
                                    Harga
                                    {{ $sortField === 'price' ? ($sortDirection === 'asc' ? '↑' : '↓') : '↕' }}
                                </button>
                            </th>
                            <th>
                                <button type="button" class="fjb-sort-button" wire:click="sortBy('seller')">
                                    Penjual
                                    {{ $sortField === 'seller' ? ($sortDirection === 'asc' ? '↑' : '↓') : '↕' }}
                                </button>
                            </th>
                            <th>
                                <button type="button" class="fjb-sort-button" wire:click="sortBy('status')">
                                    Status
                                    {{ $sortField === 'status' ? ($sortDirection === 'asc' ? '↑' : '↓') : '↕' }}
                                </button>
                            </th>
                            <th>
                                <button type="button" class="fjb-sort-button" wire:click="sortBy('created_at')">
                                    Tanggal
                                    {{ $sortField === 'created_at' ? ($sortDirection === 'asc' ? '↑' : '↓') : '↕' }}
                                </button>
                            </th>
                            <th>Informasi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($listings as $listing)
                            <tr>
                                <td>
                                    <div class="fjb-product">
                                        <div class="fjb-product-image">
                                            @if ($listing->primaryImage)
                                                <img src="{{ \Illuminate\Support\Facades\Storage::url($listing->primaryImage->image_path) }}" alt="{{ $listing->title }}">
                                            @else
                                                ◈
                                            @endif
                                        </div>

                                        <div>
                                            <a href="{{ \App\Filament\Resources\Products\ProductResource::getUrl('edit', ['record' => $listing->public_id]) }}"
   class="fjb-product-name"
   style="display:block;text-decoration:none;color:#2563eb;cursor:pointer;">
    {{ $listing->title }}

                                            <div class="fjb-product-type">{{ ucfirst($listing->listing_type) }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td>{{ $listing->category?->name ?? '-' }}</td>
                                <td>{{ $listing->subCategory?->name ?? '-' }}</td>

                                <td class="fjb-price">
                                    {{ $listing->price !== null
                                        ? 'Rp ' . number_format($listing->price, 0, ',', '.')
                                        : '-' }}
                                </td>

                                <td>{{ $listing->user?->name ?? '-' }}</td>

                                <td>
                                    <span class="fjb-badge fjb-badge-{{ $listing->status }}">
                                        {{ ucfirst($listing->status) }}
                                    </span>
                                </td>

                                <td>{{ $listing->created_at?->format('d M Y') ?? '-' }}</td>
</a>
                                <td>
                                    <div class="fjb-info-buttons">
                                        @if ($listing->sub_category_id)
                                            <button type="button" class="fjb-info-button" style="background:#2563eb"
                                                wire:click="openInfo('{{ $listing->public_id }}', 'specification')">Spesifikasi</button>
                                        @endif

                                        @if ($listing->user)
                                            <button type="button" class="fjb-info-button" style="background:#16a34a"
                                                wire:click="openInfo('{{ $listing->public_id }}', 'seller')">Penjual</button>
                                        @endif

                                        @if ($listing->store)
                                            <button type="button" class="fjb-info-button" style="background:#9333ea"
                                                wire:click="openInfo('{{ $listing->public_id }}', 'store')">Toko</button>
                                        @endif

                                        @if ($listing->location)
                                            <button type="button" class="fjb-info-button" style="background:#ea580c"
                                                wire:click="openInfo('{{ $listing->public_id }}', 'address')">Alamat</button>
                                        @endif

                                        @if ($listing->images->isNotEmpty())
                                            <button type="button" class="fjb-info-button" style="background:#db2777"
                                                wire:click="openInfo('{{ $listing->public_id }}', 'photos')">Foto</button>
                                        @endif

                                        
                                        @if ($listing->listing_type === 'service' && $listing->serviceDetail)
                                            <button type="button" class="fjb-info-button" style="background:#4f46e5"
                                                wire:click="openInfo('{{ $listing->public_id }}', 'service')">Detail Jasa</button>
                                        @endif

                                        @if ($listing->listing_type === 'service' && $listing->serviceAreas->isNotEmpty())
                                            <button type="button" class="fjb-info-button" style="background:#0d9488"
                                                wire:click="openInfo('{{ $listing->public_id }}', 'service-areas')">Wilayah Layanan</button>
                                        @endif

                                        <button type="button" class="fjb-info-button" style="background:#1e293b"
                                            wire:click="openInfo('{{ $listing->public_id }}', 'complete')">Detail Lengkap</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="fjb-empty">
                                        <div class="fjb-empty-icon">⌕</div>
                                        <h3>Belum Ada Listing</h3>
                                        <p>Listing yang sesuai dengan filter ini tidak ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="fjb-pagination">
                <div class="fjb-count" style="margin-bottom:10px;">
                    Menampilkan {{ $listings->firstItem() ?? 0 }}–{{ $listings->lastItem() ?? 0 }}
                    dari {{ number_format($listings->total()) }} listing
                </div>
                {{ $listings->links() }}
            </div>
        </section>

    {{-- MODAL DETAIL LISTING --}}
    @if ($selectedListingPublicId && $activeInfo)
        @php
            $selected = $this->getSelectedListing();
            $modalTitles = [
                'specification' => 'Spesifikasi Listing',
                'seller' => 'Informasi Penjual',
                'store' => 'Informasi Toko',
                'address' => 'Alamat Listing',
                'photos' => 'Foto Listing',
                'category' => 'Kategori Listing',
                'service' => 'Detail Jasa',
                'service-areas' => 'Wilayah Layanan',
                'complete' => 'Detail Lengkap Listing',
            ];
        @endphp

        @if ($selected)
            <div class="fjb-modal-backdrop" wire:click.self="closeInfo">
                <div class="fjb-modal">
                    <div class="fjb-modal-header">
                        <h2 class="fjb-modal-title">
                            {{ $modalTitles[$activeInfo] ?? 'Informasi Listing' }}
                        </h2>
                        <button type="button" class="fjb-modal-close" wire:click="closeInfo">Tutup ✕</button>
                    </div>

                    @if ($activeInfo === 'specification')
                        <div class="fjb-info-grid">
                            @forelse ($this->getSelectedSpecifications() as $spec)
                                <div class="fjb-info-item">
                                    <span class="fjb-info-label">
                                        {{ $spec['name'] }}
                                        @if ($spec['required']) <span style="color:#dc2626">*</span> @endif
                                    </span>
                                    <div class="fjb-info-value">{{ $spec['value'] }}</div>
                                </div>
                            @empty
                                <p>Belum ada spesifikasi yang terdaftar untuk subkategori ini.</p>
                            @endforelse
                        </div>

                    @elseif ($activeInfo === 'seller')
                        <div class="fjb-info-grid">
                            <div class="fjb-info-item"><span class="fjb-info-label">Nama Penjual</span><div class="fjb-info-value">{{ $selected->user?->name ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Email</span><div class="fjb-info-value">{{ $selected->user?->email ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Nama Lengkap</span><div class="fjb-info-value">{{ $selected->user?->profile?->full_name ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Telepon</span><div class="fjb-info-value">{{ $selected->user?->profile?->phone ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Bio</span><div class="fjb-info-value">{{ $selected->user?->profile?->bio ?? '-' }}</div></div>
                        </div>

                    @elseif ($activeInfo === 'store')
                        <div class="fjb-info-grid">
                            <div class="fjb-info-item"><span class="fjb-info-label">Nama Toko</span><div class="fjb-info-value">{{ $selected->store?->name ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Telepon</span><div class="fjb-info-value">{{ $selected->store?->phone ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">WhatsApp</span><div class="fjb-info-value">{{ $selected->store?->whatsapp ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Alamat Toko</span><div class="fjb-info-value">{{ $selected->store?->address ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Deskripsi</span><div class="fjb-info-value">{{ $selected->store?->description ?? '-' }}</div></div>
                        </div>

                    @elseif ($activeInfo === 'address')
                        <div class="fjb-info-grid">
                            <div class="fjb-info-item"><span class="fjb-info-label">Provinsi</span><div class="fjb-info-value">{{ $selected->location?->province?->name ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Kabupaten</span><div class="fjb-info-value">{{ $selected->location?->regency?->name ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Kecamatan</span><div class="fjb-info-value">{{ $selected->location?->district?->name ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Desa/Kelurahan</span><div class="fjb-info-value">{{ $selected->location?->village?->name ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Alamat Detail</span><div class="fjb-info-value">{{ $selected->location?->address_detail ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Koordinat</span><div class="fjb-info-value">{{ $selected->location?->latitude ?? '-' }}, {{ $selected->location?->longitude ?? '-' }}</div></div>
                        </div>

                    @elseif ($activeInfo === 'photos')
                        <div class="fjb-photo-grid">
                            @forelse ($selected->images as $photo)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($photo->image_path) }}" alt="{{ $photo->alt_text ?? $selected->title }}">
                            @empty
                                <p>Belum ada foto listing.</p>
                            @endforelse
                        </div>

                    @elseif ($activeInfo === 'category')
                        <div class="fjb-info-grid">
                            <div class="fjb-info-item"><span class="fjb-info-label">Kategori</span><div class="fjb-info-value">{{ $selected->category?->name ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Subkategori</span><div class="fjb-info-value">{{ $selected->subCategory?->name ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Jenis Listing</span><div class="fjb-info-value">{{ ucfirst($selected->listing_type ?? '-') }}</div></div>
                        </div>

                    @elseif ($activeInfo === 'service')
                        <div class="fjb-info-grid">
                            <div class="fjb-info-item"><span class="fjb-info-label">Jenis Harga</span><div class="fjb-info-value">{{ $selected->serviceDetail?->pricing_type ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Harga Mulai</span><div class="fjb-info-value">{{ $selected->serviceDetail?->price_from ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Harga Sampai</span><div class="fjb-info-value">{{ $selected->serviceDetail?->price_to ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Satuan</span><div class="fjb-info-value">{{ $selected->serviceDetail?->price_unit ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Pengalaman (tahun)</span><div class="fjb-info-value">{{ $selected->serviceDetail?->experience_years ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Deskripsi Jasa</span><div class="fjb-info-value">{{ $selected->serviceDetail?->service_description ?? '-' }}</div></div>
                        </div>

                    @elseif ($activeInfo === 'service-areas')
                        <div class="fjb-info-grid">
                            @forelse ($selected->serviceAreas as $area)
                                <div class="fjb-info-item">
                                    <span class="fjb-info-label">Wilayah Layanan</span>
                                    <div class="fjb-info-value">
                                        {{ $area->village?->name ?? '' }}
                                        {{ $area->district?->name ?? '' }}
                                        {{ $area->regency?->name ?? '' }}
                                    </div>
                                </div>
                            @empty
                                <p>Belum ada wilayah layanan.</p>
                            @endforelse
                        </div>

                    @else
                        <h3 style="font-size:16px;font-weight:800;margin-bottom:12px;">{{ $selected->title }}</h3>
                        <div class="fjb-info-grid">
                            <div class="fjb-info-item"><span class="fjb-info-label">Jenis Listing</span><div class="fjb-info-value">{{ ucfirst($selected->listing_type ?? '-') }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Harga</span><div class="fjb-info-value">{{ $selected->price !== null ? 'Rp '.number_format($selected->price, 0, ',', '.') : '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Kategori</span><div class="fjb-info-value">{{ $selected->category?->name ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Subkategori</span><div class="fjb-info-value">{{ $selected->subCategory?->name ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Penjual</span><div class="fjb-info-value">{{ $selected->user?->name ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Toko</span><div class="fjb-info-value">{{ $selected->store?->name ?? '-' }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Status</span><div class="fjb-info-value">{{ ucfirst($selected->status ?? '-') }}</div></div>
                            <div class="fjb-info-item"><span class="fjb-info-label">Tanggal</span><div class="fjb-info-value">{{ $selected->created_at?->format('d M Y H:i') ?? '-' }}</div></div>
                        </div>
                        <div class="fjb-info-item" style="margin-top:12px;">
                            <span class="fjb-info-label">Deskripsi</span>
                            <div class="fjb-info-value" style="font-weight:400;white-space:pre-line;">{{ $selected->description ?? '-' }}</div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endif

    </div>
</x-filament-panels::page>