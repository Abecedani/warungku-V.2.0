@extends('layouts.admin')

@section('title', 'Transaksi - Admin WarungKu')

@section('content')
<main class="flex-grow-1 p-4" style="background: #fafafa; min-height: 100vh;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Laporan Transaksi</h4>
            <p class="text-muted small mb-0">Seluruh transaksi di platform WarungKu</p>
        </div>
        <div class="bg-white rounded-3 shadow-sm px-4 py-2 text-end">
            <small class="text-muted">Total Transaksi Selesai</small>
            <p class="fw-bold mb-0" style="color: #e65c00;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-3 border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-3 shadow-sm">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="px-4 py-3">Kode Order</th>
                    <th class="py-3">Pembeli</th>
                    <th class="py-3">Warung</th>
                    <th class="py-3">Total</th>
                    <th class="py-3">Pembayaran</th>
                    <th class="py-3">Status</th>
                    <th class="py-3">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="px-4 py-3">
                            <p class="fw-bold mb-0 small">#{{ $order->order_code }}</p>
                        </td>
                        <td class="py-3">
                            <p class="mb-0 small">{{ $order->user->name }}</p>
                            <small class="text-muted">{{ $order->user->email }}</small>
                        </td>
                        <td class="py-3">
                            <small class="fw-bold">{{ $order->warung->name }}</small>
                        </td>
                        <td class="py-3">
                            <small class="fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</small>
                        </td>
                        <td class="py-3">
                            <small class="text-muted">{{ $order->payment_type ?? '-' }}</small>
                        </td>
                        <td class="py-3">
                            @php
                                $badgeColor = match($order->status) {
                                    'pending'      => 'warning text-dark',
                                    'dibayar'      => 'info text-dark',
                                    'diproses'     => 'primary',
                                    'siap_diambil' => 'success',
                                    'selesai'      => 'secondary',
                                    'dibatalkan'   => 'danger',
                                    default        => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeColor }} rounded-pill px-3">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </td>
                        <td class="py-3">
                            <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <div style="font-size: 2rem;">📋</div>
                            <p class="mt-2 mb-0">Belum ada transaksi</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</main>
@endsection