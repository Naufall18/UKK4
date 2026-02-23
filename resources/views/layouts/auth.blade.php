<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perpustakaan Digital')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e8ecf1 0%, #d5dde5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .auth-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            padding: 40px 36px;
        }

        .auth-brand {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-brand .icon {
            width: 60px;
            height: 60px;
            background: #2c3e50;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .auth-brand .icon i {
            font-size: 28px;
            color: #fff;
        }

        .auth-brand h4 {
            margin: 0;
            font-weight: 700;
            color: #2c3e50;
        }

        .auth-brand p {
            margin: 4px 0 0;
            font-size: 14px;
            color: #888;
        }

        .form-label {
            font-weight: 500;
            font-size: 14px;
            color: #555;
        }

        .form-control {
            border-radius: 8px;
            padding: 10px 14px;
            border: 1px solid #ddd;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.15);
        }

        .btn-login {
            background: #3498db;
            border: none;
            color: #fff;
            border-radius: 8px;
            padding: 11px;
            font-weight: 600;
            width: 100%;
            transition: background 0.2s;
        }

        .btn-login:hover {
            background: #2980b9;
            color: #fff;
        }

        .auth-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #888;
        }

        .auth-footer a {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="auth-card">
        <div class="auth-brand">
            <div class="icon"><i class="bi bi-book"></i></div>
            <h4>Perpustakaan Digital</h4>
            <p>Sekolah Digital</p>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size:14px;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size:14px;">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>