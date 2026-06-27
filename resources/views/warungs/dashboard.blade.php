@extends('layouts.main')

@section('title', 'Dashboard Penjual - WarungKu')

@section('content')
<link rel="stylesheet" href="{{ asset('css/penjual-dashboard.css') }}">

<div class="p-4" style="background: #fafafa; min-height: 100vh;">
    @php
        $warung = auth()->user()->warung;
        $jam = now()->hour;
        $greeting = $jam < 12
            ? 'Selamat pagi'
            : ($jam < 17 ? 'Selamat siang' : 'Selamat malam');

        $pesananPending = $warung
            ? $warung->orders()->where('status', 'pending')->count()
            : 0;

        $pendapatanHarian = $warung
            ? $warung->orders()
                ->whereDate('created_at', today())
                ->whereIn('status', ['dibayar', 'diproses', 'siap_diambil', 'selesai'])
                ->sum('total_price')
            : 0;

        $menuTerlaris = $warung
            ? \App\Models\OrderItem::whereHas('order', fn ($q) =>
                $q->where('warung_id', $warung->id)
                    ->whereDate('created_at', today()))
                ->selectRaw('menu_id, sum(quantity) as total_terjual')
                ->groupBy('menu_id')
                ->orderByDesc('total_terjual')
                ->with('menu')
                ->first()?->menu
            : null;
        $trendData = collect(range(6, 0))->map(function($daysAgo) use ($warung) {
            return $warung ? $warung->orders()
                ->whereDate('created_at', today()->subDays($daysAgo))
                ->whereIn('status', ['dibayar', 'diproses', 'siap_diambil', 'selesai'])
                ->sum('total_price') : 0;
              })->values()->toArray();

        $trendLabels = collect(range(6, 0))->map(fn($d) => 
            now()->subDays($d)->locale('id')->isoFormat('ddd')
            )->values()->toArray();

        $targetHarian = 500000;
        $pencapaian = $pendapatanHarian;
        $progressPersen = $targetHarian > 0
            ? min(100, round(($pencapaian / $targetHarian) * 100))
            : 0;
    @endphp

    @if($warung)

        {{-- Alert Verifikasi --}}
        @if(!$warung->is_verified)
            <div class="alert border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center gap-3"
                style="background: #fff3cd; color: #856404;">
                ⏳
                <div>
                    <strong>Menunggu Verifikasi Admin</strong><br>
                    <small>Warungmu sedang diperiksa. Kamu belum bisa membuka warung sebelum diverifikasi.</small>
                </div>
            </div>
        @endif

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h4 class="fw-bold mb-0">Dashboard Penjual</h4>
                <p class="text-muted small mb-0">{{ $greeting }}, {{ auth()->user()->name }} 👋</p>
            </div>
            <span id="statusBadge" class="{{ $warung->is_open ? 'badge-buka' : 'badge-tutup' }}">
                {{ $warung->is_open ? '🟢 Buka' : '🔴 Tutup' }}
            </span>
        </div>

        {{-- Toggle Status --}}
        <div class="bg-white rounded-3 shadow-sm p-4 mb-4 d-flex justify-content-between align-items-center">
            <div>
                <p class="fw-bold mb-0">Status Warung</p>
                <small class="text-muted" id="statusText">
                    Warung kamu saat ini sedang <strong>{{ $warung->is_open ? 'Buka' : 'Tutup' }}</strong>
                </small>
                @if(!$warung->is_verified)
                    <br><small class="text-danger">Belum bisa dibuka — menunggu verifikasi admin</small>
                @endif
            </div>
            <div class="d-flex align-items-center gap-3">
                @if($warung->is_verified)
                    <small class="text-muted" id="toggleLabel">
                        {{ $warung->is_open ? 'Tutup warung' : 'Buka warung' }}
                    </small>
                @endif
                <label class="switch mb-0">
                    <input type="checkbox" id="statusToggle"
                        {{ $warung->is_open ? 'checked' : '' }}
                        {{ !$warung->is_verified ? 'disabled' : '' }}>
                    <span class="slider" style="{{ !$warung->is_verified ? 'opacity: 0.5; cursor: not-allowed;' : '' }}"></span>
                </label>
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="bg-white rounded-3 shadow-sm p-4 h-100">
                    <div class="stat-icon mb-3" style="background: #fff3ec;">🍽️</div>
                    <p class="text-muted small text-uppercase mb-1">Total Menu</p>
                    <p class="fw-bold mb-0" style="font-size: 1.8rem;">{{ $warung->menus->count() }}</p>
                    @if($warung->menus->count() === 0)
                        <div class="empty-hint mt-2">
                            <p class="small mb-1" style="color: #856404;">Belum ada menu</p>
                            <a href="{{ route('warungs.menu') }}" class="btn btn-sm btn-orange rounded-pill">+ Tambah</a>
                        </div>
                    @else
                        <p class="text-muted small mt-1">menu aktif</p>
                    @endif
                </div>
            </div>

            <div class="col-md-3">
                <div class="bg-white rounded-3 shadow-sm p-4 h-100">
                    <div class="stat-icon mb-3" style="background: #fef3c7;">📦</div>
                    <p class="text-muted small text-uppercase mb-1">Pesanan Pending</p>
                    <p class="fw-bold mb-0" style="font-size: 1.8rem;">{{ $pesananPending }}</p>
                    @if($pesananPending > 0)
                        <span class="badge bg-danger mt-1">Butuh konfirmasi</span>
                    @else
                        <p class="text-muted small mt-1">tidak ada pesanan baru</p>
                    @endif
                </div>
            </div>

            <div class="col-md-3">
                <div class="bg-white rounded-3 shadow-sm p-4 h-100">
                    <div class="stat-icon mb-3" style="background: #dcfce7;">💰</div>
                    <p class="text-muted small text-uppercase mb-1">Pendapatan Hari Ini</p>
                    <p class="fw-bold mb-0" style="font-size: 1.4rem;">
                        Rp {{ number_format($pendapatanHarian, 0, ',', '.') }}
                    </p>
                    <p class="text-muted small mt-1">total omzet hari ini</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="bg-white rounded-3 shadow-sm p-4 h-100">
                    <div class="stat-icon mb-3" style="background: #fffbe6;">⭐</div>
                    <p class="text-muted small text-uppercase mb-1">Rating</p>
                    <p class="fw-bold mb-0" style="font-size: 1.8rem;">{{ $warung->rating ?? '0.0' }}</p>
                    <p class="text-muted small mt-1">dari 5.0</p>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">

            {{-- Target Harian --}}
            <div class="col-md-6">
                <div class="bg-white rounded-3 shadow-sm p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="fw-bold mb-0">🎯 Target Penjualan Harian</p>
                        <div class="d-flex align-items-center gap-2">
                            <small class="text-muted" id="targetPersen">{{ $progressPersen }}%</small>
                            <button class="btn btn-sm btn-outline-secondary rounded-pill"
                                data-bs-toggle="modal" data-bs-target="#modalTarget">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </div>
                    <div class="progress mb-2" style="height: 10px; border-radius: 999px;">
                        <div class="progress-bar" role="progressbar"
                            style="width: {{ $progressPersen }}%; background: var(--orange); border-radius: 999px;">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Rp {{ number_format($pencapaian, 0, ',', '.') }}</small>
                        <small class="text-muted" id="targetLabel">
                            Target: Rp {{ number_format($targetHarian, 0, ',', '.') }}
                        </small>
                    </div>
                </div>
            </div>

            {{-- Menu Terlaris --}}
            <div class="col-md-6">
                <div class="bg-white rounded-3 shadow-sm p-4 h-100">
                    <p class="fw-bold mb-3">🏆 Menu Terlaris Hari Ini</p>
                    @if($menuTerlaris)
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon" style="background: #fff3ec; font-size: 2rem;">🍽️</div>
                            <div>
                                <p class="fw-bold mb-0">{{ $menuTerlaris->name }}</p>
                                <small class="text-muted">{{ $menuTerlaris->total_terjual }} porsi terjual</small>
                            </div>
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <p class="mb-0">Belum ada data penjualan hari ini</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Grafik 7 Hari --}}
        <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
            <p class="fw-bold mb-3">📈 Tren Penjualan 7 Hari Terakhir</p>
            <canvas id="salesChart" height="80"></canvas>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white rounded-3 shadow-sm p-4">
            <p class="fw-bold mb-3">⚡ Aksi Cepat</p>
            <div class="d-flex gap-3 flex-wrap">
                <a href="{{ route('warungs.menu') }}" class="btn btn-orange rounded-3 px-4">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Menu
                </a>
                <a href="{{ route('warungs.pesanan') }}" class="btn btn-outline-secondary rounded-3 px-4">
                    <i class="bi bi-bag-check me-2"></i>Lihat Pesanan
                </a>
                <a href="#" class="btn btn-outline-secondary rounded-3 px-4">
                    <i class="bi bi-download me-2"></i>Unduh Laporan
                </a>
                <a href="{{ route('warungs.profil') }}" class="btn btn-outline-secondary rounded-3 px-4">
                    <i class="bi bi-shop me-2"></i>Edit Profil Warung
                </a>
            </div>
        </div>

    @else

        <h4 class="fw-bold mb-4">Dashboard Penjual</h4>
        <div class="bg-white rounded-3 shadow-sm p-5 text-center">
            <div style="font-size: 3rem;">🏪</div>
            <h5 class="fw-bold mt-3">Belum ada warung</h5>
            <p class="text-muted mb-4">Daftarkan warungmu sekarang dan mulai jualan di WarungKu!</p>
            <a href="{{ route('warungs.create') }}" class="btn btn-orange px-4 py-2 rounded-3">
                Buat Warung Sekarang
            </a>
        </div>

    @endif
</div>

{{-- Modal Target --}}
<div class="modal fade" id="modalTarget" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">🎯 Set Target Penjualan Harian</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="small fw-bold mb-1">Target (Rp)</label>
                <input type="number" id="inputTarget" class="form-control form-control-lg"
                    placeholder="Contoh: 500000" value="{{ $targetHarian }}">
                <small class="text-muted">Target akan disimpan di browser kamu.</small>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary rounded-3"
                    data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-orange rounded-3 px-4"
                    onclick="simpanTarget()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if($warung && $warung->is_verified)
    const toggle     = document.getElementById('statusToggle');
    const badge      = document.getElementById('statusBadge');
    const label      = document.getElementById('toggleLabel');
    const statusText = document.getElementById('statusText');

    toggle.addEventListener('change', function () {
        toggle.disabled = true;
        fetch("{{ route('warungs.toggle') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            badge.textContent    = data.is_open ? '🟢 Buka' : '🔴 Tutup';
            badge.className      = data.is_open ? 'badge-buka' : 'badge-tutup';
            label.textContent    = data.is_open ? 'Tutup warung' : 'Buka warung';
            statusText.innerHTML = 'Warung kamu saat ini sedang <strong>' + (data.is_open ? 'Buka' : 'Tutup') + '</strong>';
            toggle.disabled      = false;
        })
        .catch(() => toggle.disabled = false);
    });
    @endif

    const TARGET_KEY = 'target_harian_{{ auth()->user()->id }}';

    function simpanTarget() {
        const target = parseInt(document.getElementById('inputTarget').value);
        if (!target || target <= 0) return alert('Masukkan target yang valid.');
        localStorage.setItem(TARGET_KEY, target);
        bootstrap.Modal.getInstance(document.getElementById('modalTarget')).hide();
        updateTarget(target);
    }

    function updateTarget(target) {
        const pencapaian = {{ $pencapaian }};
        const persen = Math.min(100, Math.round((pencapaian / target) * 100));
        document.querySelector('.progress-bar').style.width = persen + '%';
        document.getElementById('targetPersen').textContent = persen + '%';
        document.getElementById('targetLabel').textContent  = 'Target: Rp ' + target.toLocaleString('id-ID');
        document.getElementById('inputTarget').value        = target;
    }

    const savedTarget = localStorage.getItem(TARGET_KEY);
    if (savedTarget) updateTarget(parseInt(savedTarget));

    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($trendLabels) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($trendData) !!},
                borderColor: '#e65c00',
                backgroundColor: 'rgba(230, 92, 0, 0.08)',
                borderWidth: 2,
                pointBackgroundColor: '#e65c00',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: val => 'Rp ' + val.toLocaleString('id-ID')
                    }
                }
            }
        }
    });
</script>
@endsection