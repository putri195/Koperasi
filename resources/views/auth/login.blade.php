<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Koperasi</title>
</head>
<body>
    <h1>Login ke Sistem Koperasi</h1>
    @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif
    <p>Silakan login menggunakan akun Keycloak Anda.</p>
    <a href="{{ route('login.keycloak') }}">Login dengan Keycloak</a>
</body>
</html>