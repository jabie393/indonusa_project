<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesi Aktif Terdeteksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            max-width: 400px;
            text-align: center;
        }
        h2 {
            margin-bottom: 15px;
            color: #333;
        }
        p {
            color: #555;
            margin-bottom: 25px;
        }
        button {
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-primary {
            background: #2563eb;
            color: white;
        }
        .btn-secondary {
            background: #e5e7eb;
            color: #333;
        }
        form {
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Sesi Aktif Ditemukan</h2>
        <p>Akun kamu sudah login di perangkat lain.<br>
        Apakah kamu ingin melanjutkan dan menendang sesi lama?</p>

        <form action="{{ route('auth.continue-session') }}" method="POST">
            @csrf
            <button type="submit" class="btn-primary">Lanjutkan</button>
        </form>

        <form action="{{ route('auth.cancel-login') }}" method="POST" style="margin-left: 10px;">
            @csrf
            <button type="submit" class="btn-secondary">Tunggu</button>
        </form>
    </div>
</body>
</html>
