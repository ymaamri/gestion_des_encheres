{{-- /resources/views/layouts/guest.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BidMaster') }} - @yield('title', 'Authentification')</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Bootstrap 5 + Material Dashboard CSS -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link id="pagestyle" href="{{ asset('assets/css/material-dashboard.css') }}" rel="stylesheet" />

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 1rem;
        }

        .auth-card {
            max-width: 480px;
            width: 100%;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            background: white;
            overflow: hidden;
        }

        .auth-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
            text-align: center;
            color: white;
        }

        .auth-header h3 {
            font-weight: 800;
            margin: 0;
            font-size: 1.8rem;
        }

        .auth-header p {
            opacity: 0.9;
            margin-top: 0.5rem;
            font-size: 0.9rem;
        }

        .auth-body {
            padding: 2rem;
        }

        .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 0.75rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .auth-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .auth-link:hover {
            text-decoration: underline;
        }

        .input-group-text {
            background: transparent;
            border: 1px solid #e2e8f0;
            border-right: none;
            border-radius: 16px 0 0 16px;
        }

        .input-group .form-control {
            border-radius: 0 16px 16px 0;
        }

        .alert {
            border-radius: 16px;
            font-size: 0.9rem;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="auth-card">
        <div class="auth-header">
            <i class="material-symbols-rounded" style="font-size: 48px;">gavel</i>
            <h3>BidMaster</h3>
            <p>La marketplace d'enchères premium</p>
        </div>
        <div class="auth-body">
            @if(session('status'))
                <div class="alert alert-success mb-4">
                    <i class="material-symbols-rounded me-1" style="font-size: 18px;">check_circle</i>
                    {{ session('status') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <i class="material-symbols-rounded me-1" style="font-size: 18px;">error</i>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    @stack('scripts')
</body>

</html>