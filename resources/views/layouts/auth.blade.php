<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perpustakaan Digital')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            position: relative;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Decorative background blobs */
        body::before {
            content: '';
            position: fixed;
            top: -120px;
            right: -120px;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -100px;
            left: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.07) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.7);
            border-radius: 20px;
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.06),
                0 2px 8px rgba(0, 0, 0, 0.04);
            width: 100%;
            max-width: 440px;
            padding: 44px 40px;
            position: relative;
            z-index: 1;
            animation: fadeIn 0.5s ease forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-brand {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-brand .icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            overflow: hidden;
        }

        .auth-brand .icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .auth-brand .icon i {
            font-size: 26px;
            color: #fff;
        }

        .auth-brand h4 {
            margin: 0;
            font-weight: 700;
            font-size: 20px;
            color: #0f172a;
        }

        .auth-brand p {
            margin: 4px 0 0;
            font-size: 13px;
            color: #94a3b8;
            font-weight: 400;
        }

        .form-label {
            font-weight: 500;
            font-size: 13px;
            color: #475569;
            margin-bottom: 6px;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            background: rgba(255, 255, 255, 0.7);
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
            background: #fff;
        }

        .form-control::placeholder {
            color: #cbd5e1;
        }

        .form-select {
            border-radius: 10px;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s ease;
        }

        .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }

        .btn-login {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            border: none;
            color: #fff;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            font-size: 14px;
            width: 100%;
            font-family: 'Inter', sans-serif;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.25);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #4338ca, #4f46e5);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.35);
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .auth-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #94a3b8;
        }

        .auth-footer a {
            color: #6366f1;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .auth-footer a:hover {
            color: #4f46e5;
            text-decoration: underline;
        }

        /* Alert overrides */
        .alert {
            border-radius: 10px;
            font-size: 13px;
            border: none;
        }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
        }

        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
        }

        @media (max-width: 480px) {
            .auth-card {
                margin: 16px;
                padding: 32px 24px;
            }
        }
    </style>
</head>

<body>
    <div class="auth-card">
        <div class="auth-brand">
            <div class="icon"><img src="{{ asset('img/logo.png') }}" alt="Logo Perpustakaan"></div>
            <h4>Perpustakaan Digital</h4>
            <p>Sekolah Digital</p>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size:13px;">
                <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size: 11px;"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size:13px;">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size: 11px;"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>