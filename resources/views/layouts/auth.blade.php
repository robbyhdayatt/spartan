<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SPARTAN') }} | Login</title>
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    
    <style>
        .login-page {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            display: flex;
            align-items: center;
            max-width: 950px;
            width: 100%;
            gap: 3rem;
        }
        
        .login-brand {
            flex: 1;
            text-align: center;
            color: white;
        }
        
        .login-form-container {
            flex: 1;
            max-width: 420px;
        }
        
        .login-box {
            width: 100%;
        }
        
        .card {
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            border: none;
        }
        
        .login-card-body {
            border-radius: 15px;
            padding: 2.5rem;
        }
        
        .login-logo img {
            max-width: 150px; /* Ukuran logo di dalam lingkaran */
            height: 150px;
            background: white;
            border-radius: 50%; /* Membuat frame menjadi lingkaran */
            padding: 10px; /* Jarak antara logo dan border lingkaran */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 1.5rem;
            object-fit: contain;
        }
        
        .login-brand-title {
            color: white;
            font-weight: 300;
            margin-top: 0;
            margin-bottom: 0.5rem;
            font-size: 3rem;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }
        
        .login-brand-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 18px;
            font-weight: 300;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            line-height: 1.4;
        }
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                gap: 2rem;
            }
            .login-brand {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-container">
        <div class="login-brand">
            <div class="login-logo">
                <img src="{{ asset('images/spartan-logo.png') }}" alt="SPARTAN Logo">
            </div>
            <h1 class="login-brand-title"><b>SPARTAN</b></h1>
            <p class="login-brand-subtitle">Sparepart Transaction<br>& Accounting Network</p>
        </div>
        
        <div class="login-form-container">
            <div class="login-box">
                <div class="card">
                    <div class="card-body login-card-body">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
</body>
</html>