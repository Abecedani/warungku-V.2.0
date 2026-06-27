@extends('layouts.main')

@section('title', 'Kelola Menu - WarungKu')

@section('content')
<link rel="stylesheet" href="{{ asset('css/penjual-dashboard.css') }}">

<main class="flex-grow-1 p-4" style="background: #fafafa; min-height: 100vh;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Kelola Menu</h4>
            <p class="text-muted small mb-0">{{ $warung->name }}</p>
        </div>
        <button class="btn btn-orange rounded-3 px-4" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle me-2"></i>Tambah Menu
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-3 border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    @if($menus->count() > 0)
        <div class="row g-4">
            @foreach($menus as $menu)
                <div class="col-md-4">
                    <div class="bg-white rounded-3 shadow-sm overflow-hidden h-100">

                        {{-- Foto --}}
                        @if($menu->images->count() > 0)
                            <div class="position-relative">
                                <div id="carousel-{{ $menu->id }}" class="carousel slide" data-bs-ride="false">
                                    <div class="carousel-inner">
                                        @foreach($menu->images as $i => $img)
                                            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                                <img src="{{ Storage::url($img->path) }}"
                                                    class="d-block w-100 object-fit-cover" height="180" alt="{{ $menu->name }}">
                                            </div>
                                        @endforeach
                                    </div>
                                    @if($menu->images->count() > 1)
                                        <button class="carousel-control-prev" type="button"
                                            data-bs-target="#carousel-{{ $menu->id }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon"></span>
                                        </button>
                                        <button class="carousel-control-next" type="button"
                                            data-bs-target="#carousel-{{ $menu->id }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon"></span>
                                        </button>
                                    @endif
                                </div>

                                @foreach($menu->images as $i => $img)
                                    <form method="POST" action="{{ route('warungs.menu.image.destroy', $img) }}"
                                        onsubmit="return confirm('Hapus foto ini?')"
                                        class="position-absolute d-none"
                                        id="deleteForm-{{ $img->id }}"
                                        style="top: 8px; right: 8px; z-index: 20;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger rounded-circle"
                                            style="width:28px;height:28px;padding:0;">
                                            <i class="bi bi-x" style="font-size:0.8rem;"></i>
                                        </button>
                                    </form>
                                @endforeach

                                <div id="deleteBtn-{{ $menu->id }}"
                                    class="position-absolute"
                                    style="top: 8px; right: 8px; z-index: 20;">
                                </div>
                            </div>
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light"
                                style="height:180px;font-size:3rem;">🍽️</div>
                        @endif

                        <div class="p-3">
                            {{-- Status --}}
                            <span class="badge mb-2 {{ $menu->status === 'tersedia' ? 'bg-success' : 'bg-danger' }}">
                                {{ $menu->status === 'tersedia' ? 'Tersedia' : 'Habis' }}
                            </span>

                            {{-- Kategori --}}
                            @foreach($menu->categories as $cat)
                                <span class="badge bg-secondary mb-2">{{ $cat->name }}</span>
                            @endforeach

                            <h6 class="fw-bold mb-1">{{ $menu->name }}</h6>
                            <p class="text-muted small mb-2">{{ Str::limit($menu->description, 60) }}</p>

                            {{-- Harga / Varian --}}
                            @if($menu->variants->count() > 0)
                                <div class="mb-3">
                                    @foreach($menu->variants as $variant)
                                        <div class="d-flex justify-content-between small">
                                            <span class="text-muted">{{ $variant->name }}</span>
                                            <span class="fw-bold" style="color:var(--orange);">Rp {{ number_format($variant->price, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="fw-bold mb-3" style="color:var(--orange);">
                                    Rp {{ number_format($menu->price, 0, ',', '.') }}
                                </p>
                            @endif

                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-secondary rounded-3 flex-grow-1"
                                    onclick="editMenu({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }}, '{{ addslashes($menu->description ?? '') }}', '{{ $menu->status }}', {{ json_encode($menu->categories->pluck('id')) }}, {{ json_encode($menu->variants) }})">
                                    <i class="bi bi-pencil me-1"></i>Edit
                                </button>
                                <form method="POST" action="{{ route('warungs.menu.destroy', $menu) }}"
                                    onsubmit="return confirm('Hapus menu ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger rounded-3">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-3 shadow-sm p-5 text-center">
            <div style="font-size:3rem;">🍽️</div>
            <h5 class="fw-bold mt-3">Belum ada menu</h5>
            <p class="text-muted mb-4">Tambahkan menu pertama warungmu sekarang!</p>
            <button class="btn btn-orange px-4 rounded-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
                + Tambah Menu
            </button>
        </div>
    @endif

</main>

{{-- ===== MODAL TAMBAH ===== --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-3 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">🍽️ Tambah Menu</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('warungs.menu.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="small fw-bold mb-1">Nama Menu</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Nasi Goreng" required>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold mb-1">Status</label>
                            <select name="status" class="form-select">
                                <option value="tersedia">Tersedia</option>
                                <option value="habis">Habis</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="small fw-bold mb-1">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Deskripsi singkat menu..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold mb-1">Harga Default (Rp)</label>
                            <input type="number" name="price" class="form-control" placeholder="Contoh: 15000" required>
                            <small class="text-muted">Dipakai jika tidak ada varian</small>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold mb-2">Kategori</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($categories as $cat)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="category_ids[]"
                                            value="{{ $cat->id }}" id="cat_tambah_{{ $cat->id }}">
                                        <label class="form-check-label small" for="cat_tambah_{{ $cat->id }}">
                                            {{ $cat->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="small fw-bold mb-1">Foto Menu (bisa lebih dari 1)</label>
                            <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                        </div>

                        {{-- Varian --}}
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="small fw-bold mb-0">Varian (opsional)</label>
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-3"
                                    onclick="tambahVarian('varianTambah')">
                                    <i class="bi bi-plus me-1"></i>Tambah Varian
                                </button>
                            </div>
                            <div id="varianTambah" class="d-flex flex-column gap-2"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-orange rounded-3 px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== MODAL EDIT ===== --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-3 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">✏️ Edit Menu</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEdit" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="small fw-bold mb-1">Nama Menu</label>
                            <input type="text" name="name" id="editName" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold mb-1">Status</label>
                            <select name="status" id="editStatus" class="form-select">
                                <option value="tersedia">Tersedia</option>
                                <option value="habis">Habis</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="small fw-bold mb-1">Deskripsi</label>
                            <textarea name="description" id="editDescription" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold mb-1">Harga Default (Rp)</label>
                            <input type="number" name="price" id="editPrice" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold mb-2">Kategori</label>
                            <div class="d-flex flex-wrap gap-2" id="editCategories">
                                @foreach($categories as $cat)
                                    <div class="form-check">
                                        <input class="form-check-input edit-cat-check" type="checkbox"
                                            name="category_ids[]" value="{{ $cat->id }}" id="cat_edit_{{ $cat->id }}">
                                        <label class="form-check-label small" for="cat_edit_{{ $cat->id }}">
                                            {{ $cat->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="small fw-bold mb-1">Tambah Foto Baru</label>
                            <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                        </div>

                        {{-- Varian --}}
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="small fw-bold mb-0">Varian</label>
                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-3"
                                    onclick="tambahVarian('varianEdit')">
                                    <i class="bi bi-plus me-1"></i>Tambah Varian
                                </button>
                            </div>
                            <div id="varianEdit" class="d-flex flex-column gap-2"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-orange rounded-3 px-4">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function tambahVarian(containerId) {
        const container = document.getElementById(containerId);
        const index = container.children.length;
        const row = document.createElement('div');
        row.className = 'd-flex gap-2 align-items-center';
        row.innerHTML = `
            <input type="text" name="variant_names[]" class="form-control form-control-sm"
                placeholder="Nama varian (contoh: Porsi Besar)" required>
            <input type="number" name="variant_prices[]" class="form-control form-control-sm"
                placeholder="Harga" required>
            <button type="button" class="btn btn-sm btn-outline-danger rounded-3"
                onclick="this.parentElement.remove()">
                <i class="bi bi-x"></i>
            </button>
        `;
        container.appendChild(row);
    }

    function editMenu(id, name, price, description, status, categoryIds, variants) {
        document.getElementById('formEdit').action = '/warungs/menu/' + id;
        document.getElementById('editName').value        = name;
        document.getElementById('editPrice').value       = price;
        document.getElementById('editDescription').value = description;
        document.getElementById('editStatus').value      = status;

        // Reset & set kategori
        document.querySelectorAll('.edit-cat-check').forEach(cb => {
            cb.checked = categoryIds.includes(parseInt(cb.value));
        });

        // Reset & set varian
        const varianEdit = document.getElementById('varianEdit');
        varianEdit.innerHTML = '';
        variants.forEach(v => {
            const row = document.createElement('div');
            row.className = 'd-flex gap-2 align-items-center';
            row.innerHTML = `
                <input type="text" name="variant_names[]" class="form-control form-control-sm"
                    value="${v.name}">
                <input type="number" name="variant_prices[]" class="form-control form-control-sm"
                    value="${v.price}">
                <button type="button" class="btn btn-sm btn-outline-danger rounded-3"
                    onclick="this.parentElement.remove()">
                    <i class="bi bi-x"></i>
                </button>
            `;
            varianEdit.appendChild(row);
        });

        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    }

document.querySelectorAll('.carousel').forEach(carousel => {
    const menuId = carousel.id.replace('carousel-', '');
    const deleteContainer = document.getElementById('deleteBtn-' + menuId);

    function updateDeleteBtn() {
        const activeItem = carousel.querySelector('.carousel-item.active');
        const index = [...carousel.querySelectorAll('.carousel-item')].indexOf(activeItem);
        const forms = document.querySelectorAll(`#deleteForm-${menuId.replace('-', '\\-')}`);
        

        const allForms = carousel.closest('.position-relative').querySelectorAll('form[id^="deleteForm"]');
        deleteContainer.innerHTML = '';
        if (allForms[index]) {
            const imgId = allForms[index].id.replace('deleteForm-', '');
            deleteContainer.innerHTML = `
                <button type="button" onclick="document.getElementById('deleteForm-${imgId}').submit()"
                    class="btn btn-sm btn-danger rounded-circle"
                    style="width:28px;height:28px;padding:0;">
                    <i class="bi bi-x" style="font-size:0.8rem;"></i>
                </button>
            `;
        }
    }

    updateDeleteBtn();
    carousel.addEventListener('slid.bs.carousel', updateDeleteBtn);
});


</script>

@endsection