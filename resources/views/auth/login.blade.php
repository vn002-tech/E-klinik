<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Klinik</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* === CSS VARIABLES === */
        :root {
            --primary: #16A34A; /* Green */
            --primary-hover: #15803D;
            --error: #DC2626;
            --success: #16A34A;
            --bg-body: #F0FDF4; /* Light green bg */
            --bg-card: #FFFFFF;
            --text-main: #111827;
            --text-muted: #6B7280;
            --border: #D1D5DB;
            --border-focus: #16A34A;
            --chip-bg: #E5E7EB;
            --chip-text: #4B5563;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-body);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background-color: var(--bg-card);
            width: 100%;
            max-width: 400px;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 24px;
        }

        .header h1 {
            color: var(--primary);
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .header p {
            color: var(--text-muted);
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            color: var(--text-main);
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: var(--primary-hover);
        }

        .error-message {
            color: var(--error);
            font-size: 13px;
            margin-top: 4px;
        }
        
        .alert-error {
            background-color: #FEE2E2;
            color: var(--error);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="header">
            <img src="/assets/images/logo.png" alt="E-Klinik Logo" style="height: 60px; margin-bottom: 10px; object-fit: contain;">
            <h1>E-Klinik</h1>
            <p>Silakan masuk ke akun Anda</p>
        </div>
        
        @if ($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <input type="text" id="username" name="username" class="form-input" value="{{ old('username') }}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>
            
            <button type="submit" class="btn-submit">Masuk</button>
        </form>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="/register" style="color: var(--primary); text-decoration: none; font-size: 14px; font-weight: 600;">Belum punya akun? Daftar</a>
        </div>
    </div>

</body>
</html>
