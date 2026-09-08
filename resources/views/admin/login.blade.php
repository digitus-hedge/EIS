<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #1e1e2d, #3b3b58);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background: #fff;
            padding: 40px 35px;
            border-radius: 10px;
            width: 340px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 8px;
            color: #1e1e2d;
        }

        .login-box p.subtitle {
            text-align: center;
            color: #888;
            font-size: 13px;
            margin-bottom: 25px;
        }

        .error {
            background: #fdecea;
            color: #c0392b;
            font-size: 13px;
            padding: 10px 12px;
            border-radius: 6px;
            margin-bottom: 18px;
            border: 1px solid #f5c6cb;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            color: #444;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i.field-icon {
            position: absolute;
            left: 12px;
            color: #999;
            font-size: 15px;
        }

        .input-wrapper input {
            width: 100%;
            padding: 10px 12px 10px 38px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s ease;
        }

        .input-wrapper input:focus {
            border-color: #3b3b58;
        }

        /* Extra right padding only for password field to make room for eye icon */
        .input-wrapper input#password {
            padding-right: 38px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            color: #999;
            cursor: pointer;
            font-size: 15px;
            user-select: none;
        }

        .toggle-password:hover {
            color: #3b3b58;
        }

        .login-box button {
            width: 100%;
            padding: 11px;
            background: #3b3b58;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            margin-top: 8px;
            transition: background 0.2s ease;
        }

        .login-box button:hover {
            background: #2b2b42;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Admin Login</h2>
        <p class="subtitle">Sign in to access your dashboard</p>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-wrapper">
                    <i class="bi bi-envelope field-icon"></i>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="admin@example.com"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="bi bi-lock field-icon"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                    >
                    <i class="bi bi-eye-slash toggle-password" id="togglePassword"></i>
                </div>
            </div>

            <button type="submit">Login</button>
        </form>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    </script>
</body>
</html>