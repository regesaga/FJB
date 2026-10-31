<?php

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubCategoryAttribute;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeOption;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

new class extends Component
{
    public string $listingType = 'product';

    public $selectedCategoryId = null;
    public $selectedSubCategoryId = null;

    public array $categories = [];
    public array $subCategories = [];
    public array $questions = [];

    public ?int $editingMappingId = null;
    public string $questionName = "";
    public string $questionType = "text";
    public bool $questionRequired = false;
    public bool $questionFilterable = false;
    public bool $questionVisible = true;
    public int $questionSort = 0;
    public string $questionOptionsText = "";

    public function mount(): void
    {
        $this->loadCategories();
    }
public function openCreateEditor(): void
{
    $this->editingMappingId = null;
    $this->questionName = '';
    $this->questionType = 'text';
    $this->questionRequired = false;
    $this->questionFilterable = false;
    $this->questionVisible = true;
    $this->questionSort = count($this->questions) + 1;
    $this->questionOptionsText = '';
}

public function openEditEditor(int $mappingId): void
{
    $mapping = SubCategoryAttribute::with('productAttribute.options')
        ->where('id', $mappingId)
        ->where('sub_category_id', $this->selectedSubCategoryId)
        ->where('listing_type', $this->listingType)
        ->firstOrFail();

    $attribute = $mapping->productAttribute;

    $this->editingMappingId = $mapping->id;
    $this->questionName = $attribute->name;
    $this->questionType = $attribute->type;
    $this->questionRequired = (bool) $mapping->required;
    $this->questionFilterable = (bool) $mapping->filterable;
    $this->questionVisible = (bool) $mapping->visible;
    $this->questionSort = (int) $mapping->sort;
    $this->questionOptionsText = $attribute->options->pluck('value')->implode("\n");
}
public function saveQuestion(): void
{
    $this->validate([
        'questionName' => 'required|string|max:255',
        'questionType' => 'required|in:text,number,textarea,date,select,checkbox,radio',
        'questionSort' => 'required|integer|min:0',
        'selectedSubCategoryId' => 'required|exists:sub_categories,id',
        'listingType' => 'required|in:product,service',
    ]);

    $options = collect(
        preg_split('/\r\n|\r|\n/', $this->questionOptionsText)
    )->map(fn ($value) => trim($value))
     ->filter()
     ->unique()
     ->values();

    if (in_array($this->questionType, ['select', 'radio', 'checkbox']) && $options->isEmpty()) {
        $this->addError('questionOptionsText', 'Isi minimal satu opsi jawaban.');
        return;
    }

    DB::transaction(function () use ($options) {
        $mapping = $this->editingMappingId
            ? SubCategoryAttribute::where('id', $this->editingMappingId)
                ->where('sub_category_id', $this->selectedSubCategoryId)
                ->where('listing_type', $this->listingType)
                ->firstOrFail()
            : null;

        $attribute = $mapping
            ? $mapping->productAttribute
            : new ProductAttribute();

        $slug = Str::slug($this->questionName) ?: 'atribut';

        $slugQuery = ProductAttribute::where('slug', $slug);

        if ($attribute->exists) {
            $slugQuery->where('id', '!=', $attribute->id);
        }

        if ($slugQuery->exists()) {
            $slug .= '-' . Str::lower(Str::random(6));
        }

        $attribute->fill([
            'name' => $this->questionName,
            'slug' => $slug,
            'type' => $this->questionType,
            'filterable' => $this->questionFilterable,
            'required' => $this->questionRequired,
            'sort' => $this->questionSort,
        ]);

        $attribute->save();

        if (!$mapping) {
            $mapping = new SubCategoryAttribute();
        }

        $mapping->fill([
            'sub_category_id' => $this->selectedSubCategoryId,
            'product_attribute_id' => $attribute->id,
            'listing_type' => $this->listingType,
            'required' => $this->questionRequired,
            'filterable' => $this->questionFilterable,
            'searchable' => true,
            'visible' => $this->questionVisible,
            'sort' => $this->questionSort,
        ]);

        $mapping->save();

        $attribute->options()->delete();

        foreach ($options as $index => $value) {
            $attribute->options()->create([
                'value' => $value,
                'sort' => $index + 1,
            ]);
        }
    });

    $this->resetErrorBag();
    $this->openCreateEditor();
    $this->loadQuestions();

    session()->flash('success', 'Pertanyaan berhasil disimpan.');
}
    public function loadCategories(): void
    {
        $this->categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('name')
            ->get(['id', 'name'])
            ->toArray();

        if (count($this->categories)) {
            $this->selectedCategoryId = $this->categories[0]['id'];
            $this->loadSubCategories();
        }
    }

    public function updatedListingType(): void
    {
        $this->loadQuestions();
    }

    public function updatedSelectedCategoryId(): void
    {
        $this->selectedSubCategoryId = null;
        $this->subCategories = [];
        $this->questions = [];

        $this->loadSubCategories();
    }

    public function updatedSelectedSubCategoryId(): void
    {
        $this->loadQuestions();
    }

    public function loadSubCategories(): void
    {
        if (!$this->selectedCategoryId) {
            return;
        }

        $this->subCategories = SubCategory::query()
            ->where('category_id', $this->selectedCategoryId)
            ->where('is_active', true)
            ->orderBy('sort')
            ->orderBy('name')
            ->get(['id', 'name'])
            ->toArray();

        if (count($this->subCategories)) {
            $this->selectedSubCategoryId = $this->subCategories[0]['id'];
            $this->loadQuestions();
        }
    }

    public function loadQuestions(): void
    {
        if (!$this->selectedSubCategoryId) {
            $this->questions = [];
            return;
        }

        $this->questions = SubCategoryAttribute::query()
            ->with([
                'productAttribute.options' => fn ($query) => $query->orderBy('sort'),
            ])
            ->where('sub_category_id', $this->selectedSubCategoryId)
            ->where('listing_type', $this->listingType)
            ->orderBy('sort')
            ->get()
            ->map(function ($mapping) {
                $attribute = $mapping->productAttribute;

                if (!$attribute) {
                    return null;
                }

                return [
                    'id' => $mapping->id,
                    'public_id' => $mapping->public_id,
                    'name' => $attribute->name,
                    'type' => $attribute->type,
                    'required' => $mapping->required,
                    'filterable' => $mapping->filterable,
                    'visible' => $mapping->visible,
                    'sort' => $mapping->sort,
                    'options_count' => $attribute->options->count(),
                ];
            })
            ->filter()
            ->values()
            ->toArray();
    }
};
?>

<div class="fjb-builder">

    <link rel="stylesheet" href="{{ asset('css/fjb-listing-builder.css') }}">

    {{-- Header --}}
    <div class="fjb-builder-header">
        <div>
            <div class="fjb-muted">Produk / Kendaraan / Motor / Kelola Pertanyaan</div>
            <h1 class="fjb-builder-title">Kelola Pertanyaan Subkategori</h1>
            <p class="fjb-builder-subtitle">
                Atur formulir listing berdasarkan kategori dan subkategori.
            </p>
        </div>

        <button class="fjb-btn" type="button" disabled>
            Lihat di Website
        </button>
    </div>

    {{-- Jenis Listing --}}
    <div class="fjb-builder-toolbar">
        <button
            type="button"
            wire:click="$set('listingType', 'product')"
            class="fjb-btn {{ $listingType === 'product' ? 'fjb-btn-primary' : '' }}"
        >
            Produk
        </button>

        <button
            type="button"
            wire:click="$set('listingType', 'service')"
            class="fjb-btn {{ $listingType === 'service' ? 'fjb-btn-primary' : '' }}"
        >
            Jasa
        </button>
    </div>

    {{-- Pilihan Kategori --}}
    <div class="fjb-panel fjb-panel-body" style="margin-bottom:18px">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

            <div class="fjb-form-group" style="margin:0">
                <label class="fjb-form-label">Kategori</label>
                <select wire:model.live="selectedCategoryId" class="fjb-form-control">
                    @foreach ($categories as $category)
                        <option value="{{ $category['id'] }}">
                            {{ $category['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="fjb-form-group" style="margin:0">
                <label class="fjb-form-label">Subkategori</label>
                <select wire:model.live="selectedSubCategoryId" class="fjb-form-control">
                    @foreach ($subCategories as $subCategory)
                        <option value="{{ $subCategory['id'] }}">
                            {{ $subCategory['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>
    </div>

    {{-- Toolbar Aksi --}}
    <div class="fjb-builder-toolbar">
        <button
    class="fjb-btn fjb-btn-primary"
    type="button"
    wire:click="openCreateEditor"
>
    + Tambah Pertanyaan
</button>

        <button class="fjb-btn" type="button" disabled>
            Import Excel
        </button>

        <button class="fjb-btn" type="button" disabled>
            Salin dari Subkategori
        </button>

        <button class="fjb-btn fjb-btn-success" type="button" disabled>
            Simpan Perubahan
        </button>
    </div>

    {{-- Tabel dan Editor --}}
    <div class="fjb-builder-grid">

        {{-- Daftar Pertanyaan --}}
        <div class="fjb-panel">

            <div class="fjb-panel-header">
                <div>
                    <div class="fjb-panel-title">Daftar Pertanyaan</div>
                    <div class="fjb-muted">
                        {{ count($questions) }} pertanyaan terpasang
                    </div>
                </div>
            </div>

            <div class="fjb-table-wrapper">
                <table class="fjb-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Pertanyaan</th>
                            <th>Tipe Input</th>
                            <th>Wajib</th>
                            <th>Filter</th>
                            <th>Aktif</th>
                            <th>Urutan</th>
                            <th>Opsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($questions as $index => $question)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <strong>{{ $question['name'] }}</strong>
                                </td>

                                <td>
                                    <span class="fjb-type-badge fjb-type-{{ $question['type'] === 'number' ? 'number' : ($question['type'] === 'select' ? 'select' : ($question['type'] === 'text' ? 'text' : 'other')) }}">
                                        {{ ucfirst($question['type']) }}
                                    </span>
                                </td>

                                <td>{{ $question['required'] ? 'Ya' : 'Tidak' }}</td>
                                <td>{{ $question['filterable'] ? 'Ya' : 'Tidak' }}</td>

                                <td>
                                    <span class="fjb-status {{ $question['visible'] ? 'active' : '' }}"></span>
                                </td>

                                <td>{{ $question['sort'] }}</td>
                                <td>{{ $question['options_count'] }}</td>

                                <td>
                                    <button type="button" class="fjb-btn" disabled>
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="fjb-empty">
                                    Belum ada pertanyaan untuk subkategori ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Editor --}}
        <div class="fjb-panel">

            <div class="fjb-panel-header">
                <div class="fjb-panel-title">Editor Pertanyaan</div>
            </div>

            <div class="fjb-panel-body">

                <div class="fjb-form-group">
                    <label class="fjb-form-label">Nama Pertanyaan</label>
                    <input
                        type="text"
                        class="fjb-form-control"
                        placeholder="Pilih pertanyaan untuk diedit"
                        disabled
                    >
                </div>

                <div class="fjb-form-group">
                    <label class="fjb-form-label">Tipe Input</label>
                    <select class="fjb-form-control" disabled>
                        <option>Pilih tipe input</option>
                        <option>Text</option>
                        <option>Number</option>
                        <option>Textarea</option>
                        <option>Select</option>
                        <option>Radio</option>
                        <option>Checkbox</option>
                        <option>Date</option>
                    </select>
                </div>

                <div class="fjb-form-group">
                    <label class="fjb-form-label">Pengaturan</label>

                    <div class="fjb-editor-option">
                        <input type="checkbox" disabled>
                        <span>Wajib diisi</span>
                    </div>

                    <div class="fjb-editor-option">
                        <input type="checkbox" disabled>
                        <span>Gunakan sebagai filter</span>
                    </div>

                    <div class="fjb-editor-option">
                        <input type="checkbox" disabled>
                        <span>Tampilkan pada listing</span>
                    </div>
                </div>

                <div class="fjb-form-group">
                    <label class="fjb-form-label">Urutan</label>
                    <input type="number" class="fjb-form-control" disabled>
                </div>

                <div class="fjb-editor-footer">
                    <button class="fjb-btn" type="button" disabled>
                        Batal
                    </button>

                    <button class="fjb-btn fjb-btn-primary" type="button" disabled>
                        Simpan
                    </button>
                </div>

            </div>
        </div>

    </div>

    {{-- Utilitas --}}
    <div class="fjb-utility-grid">

        <div class="fjb-panel">
            <div class="fjb-panel-header fjb-import-header">
                <strong>Import Pertanyaan Excel</strong>
            </div>

            <div class="fjb-panel-body">
                <p class="fjb-muted">
                    Tambahkan banyak pertanyaan sekaligus menggunakan template Excel.
                </p>

                <button class="fjb-btn" type="button" disabled>
                    Download Template
                </button>

                <button class="fjb-btn" type="button" disabled>
                    Upload Excel
                </button>
            </div>
        </div>

        <div class="fjb-panel">
            <div class="fjb-panel-header fjb-copy-header">
                <strong>Salin Pertanyaan</strong>
            </div>

            <div class="fjb-panel-body">
                <p class="fjb-muted">
                    Salin konfigurasi pertanyaan dari subkategori lain.
                </p>

                <select class="fjb-form-control" disabled>
                    <option>Pilih subkategori sumber</option>
                </select>

                <button class="fjb-btn" type="button" disabled style="margin-top:12px">
                    Salin Pertanyaan
                </button>
            </div>
        </div>

    </div>

</div>
