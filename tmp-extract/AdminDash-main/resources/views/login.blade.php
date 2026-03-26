<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - أدمن داش</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --dark-color: #1d3557;
            --light-color: #f8f9fa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Tajawal', sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            direction: rtl;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
        }

        .login-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .login-header h2 {
            margin: 0;
            font-weight: 700;
        }

        .login-header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }

        .login-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-color);
        }

        .form-control {
            width: 100%;
            padding: 12px 45px 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            transition: all 0.3s;
            background-color: #f8f9fa;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            background-color: white;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 40px;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input {
            margin-left: 8px;
        }

        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.3s;
        }

        .forgot-password:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 20px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .divider {
            position: relative;
            text-align: center;
            margin: 25px 0;
        }

        .divider::before {
            content: "";
            position: absolute;
            top: 50%;
            right: 0;
            left: 0;
            height: 1px;
            background-color: #e0e0e0;
        }

        .divider span {
            background-color: white;
            padding: 0 15px;
            color: #6c757d;
            position: relative;
        }

        .social-login {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .social-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            transition: all 0.3s;
            text-decoration: none;
        }

        .social-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .facebook {
            background-color: #3b5998;
        }

        .twitter {
            background-color: #1da1f2;
        }

        .google {
            background-color: #db4437;
        }

        .signup-link {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
        }

        .signup-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .signup-link a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .alert {
            border-radius: 10px;
            padding: 12px 15px;
            margin-bottom: 20px;
            display: none;
        }

        .alert-danger {
            background-color: rgba(247, 37, 133, 0.1);
            border: 1px solid rgba(247, 37, 133, 0.3);
            color: var(--warning-color);
        }

        /* تأثيرات للعناصر */
        .form-group {
            animation: fadeIn 0.5s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        .form-group:nth-child(1) {
            animation-delay: 0.1s;
        }

        .form-group:nth-child(2) {
            animation-delay: 0.2s;
        }

        .remember-forgot {
            animation: fadeIn 0.5s ease-out 0.3s forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        .login-btn {
            animation: fadeIn 0.5s ease-out 0.4s forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* تخصيص زر التبديل بين وضع النهار والليل */
        .theme-toggle {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .theme-toggle:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(30deg);
        }

        /* الوضع الليلي */
        body.dark-mode {
            background: linear-gradient(135deg, #1d3557, #0d1b2a);
        }

        .dark-mode .login-card {
            background-color: #2d3748;
            color: #e2e8f0;
        }

        .dark-mode .form-control {
            background-color: #4a5568;
            border-color: #4a5568;
            color: #e2e8f0;
        }

        .dark-mode .form-control:focus {
            background-color: #4a5568;
            border-color: var(--primary-color);
            color: #e2e8f0;
        }

        .dark-mode .form-label {
            color: #e2e8f0;
        }

        .dark-mode .divider span {
            background-color: #2d3748;
            color: #a0aec0;
        }

        .dark-mode .signup-link {
            color: #a0aec0;
        }

        /* التجاوب مع الشاشات الصغيرة */
        @media (max-width: 480px) {
            .login-body {
                padding: 20px;
            }

            .remember-forgot {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <!-- زر تبديل الوضع -->
    <button class="theme-toggle" id="themeToggle">
        <i class="fas fa-moon"></i>
    </button>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2><i class="fas fa-chart-line"></i> أدمن داش</h2>
                <p>مرحباً بعودتك! يرجى تسجيل الدخول إلى حسابك</p>
            </div>
            <div class="login-body">
                <!-- رسالة الخطأ -->
                <div class="alert alert-danger" id="errorMessage">
                    <i class="fas fa-exclamation-circle"></i> البريد الإلكتروني أو كلمة المرور غير صحيحة
                </div>

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" required>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">كلمة المرور</label>
                        <input type="password" name="password" class="form-control" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>

                    <div class="remember-forgot">
                        <div class="remember-me">
                            <input type="checkbox" id="remember">
                            <label for="remember">تذكرني</label>
                        </div>
                        <a href="#" class="forgot-password">نسيت كلمة المرور؟</a>
                    </div>

                    <button type="submit" class="login-btn">تسجيل الدخول</button>
                </form>

                <div class="divider">
                    <span>أو تسجيل الدخول باستخدام</span>
                </div>

                <div class="social-login">
                    <a href="#" class="social-btn facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-btn twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-btn google">
                        <i class="fab fa-google"></i>
                    </a>
                </div>

                <div class="signup-link">
                    ليس لديك حساب؟ <a href="#">إنشاء حساب جديد</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript المخصص -->
    <script>
        // تبديل وضع الليل والنهار
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;

        themeToggle.addEventListener('click', function() {
            body.classList.toggle('dark-mode');

            if (body.classList.contains('dark-mode')) {
                themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            } else {
                themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            }
        });

        // التحقق من صحة نموذج تسجيل الدخول
        const loginForm = document.getElementById('loginForm');
        const errorMessage = document.getElementById('errorMessage');

        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // محاكاة عملية تسجيل الدخول
            if (email && password) {
                // هنا عادةً ستكون هناك طلب AJAX للخادم
                // لمثالنا، سنقوم بمحاكاة تسجيل دخول ناجح بعد ثانيتين

                // إظهار تحميل على الزر
                const submitBtn = loginForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري تسجيل الدخول...';
                submitBtn.disabled = true;


                // في حالة الفشل، إظهار رسالة الخطأ
                // errorMessage.style.display = 'block';
            } else {
                errorMessage.style.display = 'block';

                // إخفاء رسالة الخطأ بعد 5 ثوانٍ
                setTimeout(function() {
                    errorMessage.style.display = 'none';
                }, 5000);
            }
        });

        // إخفاء رسالة الخطأ عند البدء في الكتابة
        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                errorMessage.style.display = 'none';
            });
        });

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