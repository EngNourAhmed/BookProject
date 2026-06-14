<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Book ERA</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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
            padding: 20px;
            position: relative;
            overflow-x: hidden;
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
            max-width: 380px;
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
            transform: translateY(-8px);
            border-color: rgba(255, 214, 10, 0.2);
        }

        .login-header {
            padding: 30px 30px 15px;
            text-align: center;
        }

        .brand-logo {
            width: 64px;
            height: 64px;
            background: rgba(255, 214, 10, 0.1);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: var(--accent-yellow);
            font-size: 2.2rem;
            border: 1px solid rgba(255, 214, 10, 0.2);
        }

        .login-header h2 {
            color: var(--text-white);
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 8px;
        }

        .login-header p {
            color: var(--text-white);
            font-size: 0.95rem;
        }

        .login-body {
            padding: 15px 30px 35px;
        }

        .form-label {
            color: var(--text-white);
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 8px;
            display: block;
            opacity: 0.8;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 24px;
        }

        .form-control {
            width: 100%;
            background: rgba(2, 7, 16, 0.4) !important;
            border: 1px solid var(--glass-border) !important;
            border-radius: 12px !important;
            padding: 14px 16px 14px 48px !important;
            color: var(--text-white) !important;
            font-size: 0.95rem !important;
            transition: all 0.3s ease !important;
        }

        /* Autofill Styling */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px rgba(2, 7, 16, 0.65) inset !important;
            -webkit-text-fill-color: var(--text-white) !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        .form-control:focus {
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
            font-size: 1.1rem;
            transition: color 0.3s;
            pointer-events: none;
            z-index: 5;
        }

        .form-control:focus + .input-icon {
            color: var(--accent-yellow);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            font-size: 0.85rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            color: var(--text-muted);
            cursor: pointer;
        }

        .remember-me input {
            margin-right: 8px;
            accent-color: var(--accent-yellow);
        }

        .forgot-password {
            color: var(--accent-yellow);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }

        .forgot-password:hover {
            text-decoration: underline;
            color: var(--accent-hover);
        }

        .login-btn {
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
            margin-bottom: 24px;
        }

        .login-btn:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 214, 10, 0.3);
        }

        .divider {
            text-align: center;
            margin: 24px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--glass-border);
            z-index: 1;
        }

        .divider span {
            background: #0d1e36;
            padding: 0 16px;
            color: var(--text-muted);
            font-size: 0.8rem;
            position: relative;
            z-index: 2;
            border-radius: 4px;
        }

        .social-login {
            display: flex;
            gap: 12px;
            margin-bottom: 30px;
        }

        .social-btn {
            flex: 1;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            color: var(--text-white);
            font-size: 1.25rem;
            transition: all 0.2s;
            text-decoration: none;
        }

        .social-btn:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .signup-link {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .signup-link a {
            color: var(--accent-yellow);
            text-decoration: none;
            font-weight: 700;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        .alert {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 24px;
            font-size: 0.9rem;
            display: none;
            align-items: center;
            gap: 10px;
        }

        /* Animations */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card { animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1); }

        @media (max-width: 576px) {
            body {
                padding: 16px 10px;
            }
            .login-header {
                padding: 30px 20px 10px;
            }
            .login-body {
                padding: 10px 20px 30px;
            }
            .login-card {
                border-radius: 18px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="brand-logo">
                    <i class="bi bi-book-half"></i>
                </div>
                <h2>Book ERA</h2>
                <p class="text-white">Enter your credentials to access the library</p>
            </div>
            
            <div class="login-body">
                <!-- Error Message -->
                <div class="alert" id="errorMessage">
                    <i class="fas fa-exclamation-circle text-danger"></i> 
                    <span>Invalid credentials. Please try again.</span>
                </div>

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="form-group-custom">
                        <label class="form-label">Email Address</label>
                        <div class="input-group-custom">
                            <input type="email" name="email" class="form-control" placeholder="name@company.com" required>
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label">Password</label>
                        <div class="input-group-custom">
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            <i class="fas fa-lock input-icon"></i>
                        </div>
                    </div>

                    <button type="submit" class="login-btn">
                        Sign In to Dashboard
                    </button>

                    <div class="signup-link">
                        Don't have an account? <a href="{{ route('register') }}">Create one</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- JavaScript المخصص -->
    <script>
        @if($errors->any() || session('error'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                icon: 'error',
                title: 'Login Error',
                text: '{{ $errors->first() ?? session('error') }}',
                background: '#0d1e36',
                color: '#fff',
                iconColor: '#ffd60a'
            });
        @endif

        @if(session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                background: '#0d1e36',
                color: '#fff',
                iconColor: '#ffd60a'
            });
        @endif
        
        // تأثيرات إضافية عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            // إضافة تأثير اهتزاز بسيط للبطاقة
            const loginCard = document.querySelector('.login-card');
            loginCard.style.transform = 'scale(0.9)';

            setTimeout(function() {
                loginCard.style.transition = 'transform 0.5s';
                loginCard.style.transform = 'scale(1)';
            }, 100);
        });
    </script>
</body>

</html>