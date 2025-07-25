<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Form Daftar - Koperasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #ffffff;
        }

        .register-box {
        max-width: 400px;
        margin: 13px auto;
        margin-bottom:80px;
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 0 25px rgba(0,0,0,0.2);
        }

        footer {
        background-color: #4caf50;
        color: white;
        text-align: center;
        padding: 10px;
        bottom: 0;
        width: 100%;
        }

        .logo {
        padding: 20px;
        }

        .btn-success {
        background-color: #4caf50;
        border-color: #4caf50;
        }
    </style>
</head>
<body>
    <!-- Logo Koperasi -->
    <div class="logo text-start ms-4">
        <img src="{{ asset('image/koperasi.png')}}" alt="Logo" width="40">
        <span class="fw-bold fs-5 ms-2">KOPERASI</span>
    </div>

    <!-- Form Daftar -->
    <div class="register-box">
        <h4 class="text-center mb-4 fw-bold">Daftar</h4>
        <form action="{{ route('register.submit') }}" method="POST">
            @if (session('error'))
                <p style="color: red;">{{ session('error') }}</p>
            @endif
            @csrf
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Masukkan nama" required />
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Masukkan nama" required />
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Masukkan username" required />
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Masukkan email" required />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password" required />
            </div>
            <input type="hidden" name="role" value="anggota">
            <button type="submit" class="btn btn-success w-100">Daftar</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>