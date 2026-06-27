<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - WarungKu</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e65c00, #F9D423);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .card {
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .btn-daftar {
            background-color: #e65c00;
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
        }
        .btn-daftar:hover { background-color: #cc5200; color: white; }
        .link-orange { color: #e65c00; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card p-4">

                    <div class="text-center mb-4">
                        <img src="{{ asset('assets/WarungKu-Logo.png') }}" alt="Logo" width="60">
                        <h2 class="fw-bold mt-2 mb-0">Daftar Akun</h2>
                        <p class="text-muted small">Selamat datang di WarungKu</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <input type="hidden" name="role" value="{{ request('role', 'pembeli') }}">

                        <div class="mb-3">
                            <label class="small fw-bold mb-1">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control form-control-lg" required>
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold mb-1">Email</label>
                            <input type="email" name="email" class="form-control form-control-lg" required>
                        </div>

                        @foreach (['password' => 'Password', 'password_confirmation' => 'Konfirmasi Password'] as $field => $label)
                            <div class="mb-3">
                                <label class="small fw-bold mb-1">{{ $label }}</label>
                                <div class="input-group">
                                    <input type="password" name="{{ $field }}" id="{{ $field }}"
                                        class="form-control form-control-lg" required>
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('{{ $field }}')">
                                        <i class="bi bi-eye" id="{{ $field }}-icon"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-daftar mb-3">DAFTAR SEKARANG</button>
                    </form>

                    <div class="text-center small">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="link-orange">Masuk di sini</a>
                        <br>
                        @if(request('role') === 'penjual')
                            <a href="{{ route('register', ['role' => 'pembeli']) }}" class="text-secondary text-decoration-none">
                                Daftar sebagai Pembeli
                            </a>
                        @else
                            <a href="{{ route('register', ['role' => 'penjual']) }}" class="text-secondary text-decoration-none">
                                Daftar sebagai Pemilik Warung
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = document.getElementById(id + '-icon');
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            icon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
        }
    </script>
</body>
</html>
