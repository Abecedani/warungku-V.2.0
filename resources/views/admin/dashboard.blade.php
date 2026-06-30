@extends('layouts.admin')

@section('title', 'Dashboard Admin - WarungKu')

@section('content')
<main class="flex-grow-1 p-4" style="background: #fafafa; min-height: 100vh;">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Dashboard Admin</h4>
            <p class="text-muted small mb-0">Selamat datang, {{ auth()->user()->name }}</p>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success rounded-3 border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="bg-white rounded-3 shadow-sm p-4 text-center">
                <div style="font-size: 2rem;">👤</div>
                <h5 class="fw-bold mt-2 mb-0">{{ $totalUsers }}</h5>
                <small class="text-muted">Total Pengguna</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-white rounded-3 shadow-sm p-4 text-center">
                <div style="font-size: 2rem;">🏪</div>
                <h5 class="fw-bold mt-2 mb-0">{{ $totalWarungs }}</h5>
                <small class="text-muted">Warung Aktif</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-white rounded-3 shadow-sm p-4 text-center">
                <div style="font-size: 2rem;">⏳</div>
                <h5 class="fw-bold mt-2 mb-0">{{ $pendingWarungs }}</h5>
                <small class="text-muted">Menunggu Verifikasi</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-white rounded-3 shadow-sm p-4 text-center">
                <div style="font-size: 2rem;">💰</div>
                <h5 class="fw-bold mt-2 mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h5>
                <small class="text-muted">Total Transaksi</small>
            </div>
        </div>
    </div>

    {{-- Grafik --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="bg-white rounded-3 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Pertumbuhan Pengguna</h6>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-secondary active" onclick="toggleUserChart('week', this)">Mingguan</button>
                        <button type="button" class="btn btn-outline-secondary" onclick="toggleUserChart('month', this)">Bulanan</button>
                    </div>
                </div>
                <canvas id="userGrowthChart" height="220"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="bg-white rounded-3 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Transaksi</h6>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-secondary active" onclick="toggleTransactionChart('week', this)">Mingguan</button>
                        <button type="button" class="btn btn-outline-secondary" onclick="toggleTransactionChart('month', this)">Bulanan</button>
                    </div>
                </div>
                <canvas id="transactionChart" height="220"></canvas>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Warung Pending --}}
        <div class="col-md-6">
            <div class="bg-white rounded-3 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Warung Menunggu Verifikasi</h6>
                    <a href="{{ route('admin.warungs') }}" class="small" style="color: #e65c00;">Lihat semua</a>
                </div>
                @forelse($warungsNeedVerify as $warung)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <p class="fw-bold small mb-0">{{ $warung->name }}</p>
                            <small class="text-muted">{{ $warung->user->name }}</small>
                        </div>
                        <form method="POST" action="{{ route('admin.warungs.verify', $warung->id) }}">
                            @csrf
                            <button class="btn btn-sm btn-success rounded-3">Verifikasi</button>
                        </form>
                    </div>
                @empty
                    <p class="text-muted small mb-0">Tidak ada warung yang menunggu verifikasi.</p>
                @endforelse
            </div>
        </div>

        {{-- Pesanan Terbaru --}}
        <div class="col-md-6">
            <div class="bg-white rounded-3 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Pesanan Terbaru</h6>
                    <a href="{{ route('admin.transaksi') }}" class="small" style="color: #e65c00;">Lihat semua</a>
                </div>
                @forelse($recentOrders as $order)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <p class="fw-bold small mb-0">#{{ $order->order_code }}</p>
                            <small class="text-muted">{{ $order->user->name }} · {{ $order->warung->name }}</small>
                        </div>
                        <span class="badge bg-secondary rounded-pill">{{ ucfirst($order->status) }}</span>
                    </div>
                @empty
                    <p class="text-muted small mb-0">Belum ada pesanan.</p>
                @endforelse
            </div>
        </div>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const userGrowthData = {
    week: @json($userGrowthWeekly),
    month: @json($userGrowthMonthly)
};

const transactionData = {
    week: @json($transactionWeekly),
    month: @json($transactionMonthly)
};

// Chart Pertumbuhan Pengguna
const userCtx = document.getElementById('userGrowthChart').getContext('2d');
let userChart = new Chart(userCtx, {
    type: 'line',
    data: {
        labels: userGrowthData.week.labels,
        datasets: [{
            label: 'Pengguna Baru',
            data: userGrowthData.week.data,
            borderColor: '#e65c00',
            backgroundColor: 'rgba(230, 92, 0, 0.1)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});

function toggleUserChart(period, btn) {
    btn.parentElement.querySelectorAll('button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    userChart.data.labels = userGrowthData[period].labels;
    userChart.data.datasets[0].data = userGrowthData[period].data;
    userChart.update();
}

// Chart Transaksi (jumlah + nominal)
const txCtx = document.getElementById('transactionChart').getContext('2d');
let txChart = new Chart(txCtx, {
    data: {
        labels: transactionData.week.labels,
        datasets: [
            {
                type: 'bar',
                label: 'Jumlah Transaksi',
                data: transactionData.week.counts,
                backgroundColor: 'rgba(230, 92, 0, 0.5)',
                yAxisID: 'y'
            },
            {
                type: 'line',
                label: 'Total Nominal (Rp)',
                data: transactionData.week.totals,
                borderColor: '#28a745',
                yAxisID: 'y1',
                tension: 0.3
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: { type: 'linear', position: 'left', beginAtZero: true, ticks: { precision: 0 } },
            y1: { type: 'linear', position: 'right', beginAtZero: true, grid: { drawOnChartArea: false } }
        }
    }
});

function toggleTransactionChart(period, btn) {
    btn.parentElement.querySelectorAll('button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    txChart.data.labels = transactionData[period].labels;
    txChart.data.datasets[0].data = transactionData[period].counts;
    txChart.data.datasets[1].data = transactionData[period].totals;
    txChart.update();
}
</script>
@endsection