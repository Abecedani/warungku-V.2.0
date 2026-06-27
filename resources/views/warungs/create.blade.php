<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        body { background: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .card { border-radius: 20px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .btn-orange { background-color: #e65c00; color: white; }
        .btn-orange:hover { background-color: #cc5200; color: white; }
    </style>
</head>
<body class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card p-4">
                    <h3 class="fw-bold mb-4" style="color: #e65c00;">Daftarkan Warungmu</h3>
                    
                    <form action="{{ route('warungs.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="fw-bold">Nama Warung</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: Warung Berkah" required>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Lokasi Detail</label>
                            <input type="text" name="location_detail" class="form-control" placeholder="Contoh: Kantin FISIP UNRAM" required>
                        </div>
                        <div class="mb-4">
                            <label class="fw-bold">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Ceritakan keunggulan warungmu..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-orange w-100 fw-bold py-2">DAFTARKAN WARUNG</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
