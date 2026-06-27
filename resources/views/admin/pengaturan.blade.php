@extends('layouts.admin')

@section('title', 'Pengaturan - Admin WarungKu')

@section('content')
    <main class="flex-grow-1 p-4" style="background: #fafafa; min-height: 100vh;">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0">Pengaturan Sistem</h4>
                <p class="text-muted small mb-0">Konfigurasi platform WarungKu</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success rounded-3 border-0 shadow-sm mb-4">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.pengaturan.update') }}">
            @csrf

            {{-- Identitas Aplikasi --}}
            <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                <h6 class="fw-bold mb-3">🏷️ Identitas Aplikasi</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Nama Platform</label>
                        <input type="text" name="platform_name" class="form-control rounded-3"
                            value="{{ $settings['platform_name'] }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Footer Text</label>
                        <input type="text" name="footer_text" class="form-control rounded-3"
                            value="{{ $settings['footer_text'] }}">
                    </div>
                </div>
            </div>

            {{-- Operasional --}}
            <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                <h6 class="fw-bold mb-3">⚙️ Operasional Warung</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Batas Maksimal Warung</label>
                        <input type="number" name="max_warungs" class="form-control rounded-3"
                            value="{{ $settings['max_warungs'] }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Status Platform</label>
                        <div class="d-flex align-items-center gap-3 mt-2">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="maintenance_mode" value="1"
                                    id="maintenanceToggle" {{ $settings['maintenance_mode'] == '1' ? 'checked' : '' }}>
                                <label class="form-check-label small" for="maintenanceToggle">
                                    Maintenance Mode
                                </label>
                            </div>
                            @if($settings['maintenance_mode'] == '1')
                                <span class="badge bg-danger rounded-pill">Aktif</span>
                            @else
                                <span class="badge bg-success rounded-pill">Normal</span>
                            @endif
                        </div>
                        <small class="text-muted">Aktifkan saat platform sedang maintenance.</small>
                    </div>
                </div>
            </div>

            {{-- Kontak & Bantuan --}}
            <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                <h6 class="fw-bold mb-3">📞 Kontak & Bantuan</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">WhatsApp Admin</label>
                        <div class="input-group">
                            <span class="input-group-text rounded-start-3">+62</span>
                            <input type="text" name="contact_wa" class="form-control rounded-end-3" placeholder="8123456789"
                                value="{{ $settings['contact_wa'] }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Email Admin</label>
                        <input type="email" name="contact_email" class="form-control rounded-3"
                            placeholder="admin@warungku.ac.id" value="{{ $settings['contact_email'] }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Link FAQ</label>
                        <input type="text" name="faq_link" class="form-control rounded-3" placeholder="https://..."
                            value="{{ $settings['faq_link'] }}">
                    </div>
                </div>
            </div>

            {{-- API Keys --}}
            <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                <h6 class="fw-bold mb-3">🔑 API Keys</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">API Key Email</label>
                        <input type="text" name="api_key_email" class="form-control rounded-3 font-monospace"
                            placeholder="sk-..." value="{{ $settings['api_key_email'] }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">API Key WhatsApp</label>
                        <input type="text" name="api_key_wa" class="form-control rounded-3 font-monospace"
                            placeholder="wa-..." value="{{ $settings['api_key_wa'] }}">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-orange rounded-3 px-5">
                    <i class="bi bi-save me-2"></i>Simpan Pengaturan
                </button>
            </div>

        </form>

    </main>
@endsection