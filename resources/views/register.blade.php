<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Book ERA</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --navy-dark: #020710;
            --navy-light: #0d1e36;
            --accent-yellow: #ffd60a;
            --accent-hover: #ffc300;
            --text-white: #ffffff;
            --text-muted: rgba(255, 255, 255, 0.6);
            --glass-bg: rgba(13, 30, 54, 0.4);
            --glass-border: rgba(255, 255, 255, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, sans-serif;
        }

        body {
            background: var(--navy-dark);
            background-image: radial-gradient(circle at top right, rgba(13, 30, 54, 0.5) 0%, var(--navy-dark) 40%, #020710 100%);
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            overflow-x: hidden;
            position: relative;
        }

        /* Decorative Background Elements */
        body::before, body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            filter: blur(120px);
            z-index: -1;
            opacity: 0.15;
        }

        body::before {
            top: -150px;
            left: -150px;
            background: var(--accent-yellow);
        }

        body::after {
            bottom: -150px;
            right: -150px;
            background: #4361ee;
        }

        .login-container {
            width: 100%;
            max-width: 500px;
            z-index: 10;
        }

        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .login-card:hover {
            border-color: rgba(255, 214, 10, 0.2);
        }

        .login-header {
            padding: 40px 40px 10px;
            text-align: center;
        }

        .brand-logo {
            width: 56px;
            height: 56px;
            background: rgba(255, 214, 10, 0.1);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: var(--accent-yellow);
            font-size: 1.75rem;
            border: 1px solid rgba(255, 214, 10, 0.2);
        }

        .login-header h2 {
            color: var(--text-white);
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }

        .login-header p {
            color: var(--text-white);
            font-size: 0.9rem;
        }

        .login-body {
            padding: 20px 40px 40px;
        }

        .form-label {
            color: var(--text-white);
            font-weight: 600;
            font-size: 0.8rem;
            margin-bottom: 6px;
            display: block;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .form-control, .form-select {
            width: 100%;
            background: rgba(2, 7, 16, 0.4) !important;
            border: 1px solid var(--glass-border) !important;
            border-radius: 12px !important;
            padding: 12px 16px 12px 48px !important;
            color: var(--text-white) !important;
            font-size: 0.9rem !important;
            transition: all 0.3s ease !important;
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right 1rem center !important;
            background-size: 16px 12px !important;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(2, 7, 16, 0.6) !important;
            border-color: var(--accent-yellow) !important;
            box-shadow: 0 0 0 4px rgba(255, 214, 10, 0.1) !important;
            outline: none;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1rem;
            transition: color 0.3s;
            pointer-events: none;
            z-index: 5;
        }

        .form-control:focus + .input-icon, .form-select:focus + .input-icon {
            color: var(--accent-yellow);
        }

        .role-selector {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }

        .role-option {
            flex: 1;
            position: relative;
        }

        .role-option input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .role-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .role-card:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 214, 10, 0.3);
            transform: translateY(-2px);
        }

        .role-option input:checked + .role-card {
            background: rgba(255, 214, 10, 0.15);
            border-color: var(--accent-yellow);
            box-shadow: 0 0 20px rgba(255, 214, 10, 0.1);
        }

        .role-card i {
            font-size: 1.25rem;
            color: var(--text-muted);
            margin-bottom: 4px;
            display: block;
        }

        .role-option input:checked + .role-card i {
            color: var(--accent-yellow);
        }

        .role-card span {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--text-muted);
        }

        .role-option input:checked + .role-card span {
            color: var(--text-white);
        }

        .register-btn {
            width: 100%;
            padding: 14px;
            background: var(--accent-yellow);
            color: var(--navy-dark);
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 10px 0 20px;
        }

        .register-btn:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 214, 10, 0.3);
        }

        .signup-link {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .signup-link a {
            color: var(--accent-yellow);
            text-decoration: none;
            font-weight: 700;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        /* Animations */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card { animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="brand-logo">
                    <i class="bi bi-book-half"></i>
                </div>
                <h2>Join Book ERA</h2>
                <p class="text-white">Become part of our premium literary community</p>
            </div>
            
            <div class="login-body">
                <form method="POST" action="{{ route('register.post') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Full Name</label>
                            <div class="input-group-custom">
                                <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                                <i class="fas fa-user input-icon"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Email Address</label>
                            <div class="input-group-custom">
                                <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                                <i class="fas fa-envelope input-icon"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Password</label>
                            <div class="input-group-custom">
                                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                                <i class="fas fa-lock input-icon"></i>
                            </div>
                        </div>
                    </div>

                    <label class="form-label">I want to be a:</label>
                    <div class="role-selector">
                        <label class="role-option">
                            <input type="radio" name="role" value="reader" checked>
                            <div class="role-card">
                                <i class="fas fa-book-open"></i>
                                <span>Reader</span>
                            </div>
                        </label>
                        <label class="role-option">
                            <input type="radio" name="role" value="writer">
                            <div class="role-card">
                                <i class="fas fa-pen-nib"></i>
                                <span>Writer</span>
                            </div>
                        </label>
                    </div>

                    <button type="submit" class="register-btn">
                        Create My Account
                    </button>

                    <div class="signup-link">
                        Already have an account? <a href="{{ route('login') }}">Sign In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        @if($errors->any())
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                icon: 'error',
                title: 'Registration Error',
                text: '{{ $errors->first() }}',
                background: '#0d1e36',
                color: '#fff',
                iconColor: '#ffd60a'
            });
        @endif
    </script>
</body>

</html>
