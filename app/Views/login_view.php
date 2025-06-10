<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Manajemen Disiplin Hakim</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?= base_url("assets/img/bg.webp") ?>') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            max-width: 380px;
            width: 100%;
            padding: 25px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }
        .login-container:hover {
            transform: translateY(-5px);
        }
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo-container img {
            max-width: 100px;
            height: auto;
            transition: transform 0.3s ease;
        }
        .logo-container img:hover {
            transform: scale(1.1);
        }
        .login-title {
            text-align: center;
            margin-bottom: 20px;
            color: #1a252f;
            font-weight: 700;
            font-size: 1.5rem;
        }
        .form-control {
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ced4da;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-control:focus {
            border-color: #2c3e50;
            box-shadow: 0 0 8px rgba(44, 62, 80, 0.3);
        }
        .input-group-text {
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 6px 0 0 6px;
        }
        .password-wrapper {
            position: relative;
        }
        .password-wrapper .form-control {
            padding-right: 35px; /* Space for the eye icon */
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #2c3e50;
            cursor: pointer;
            background: none;
            border: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }
        .toggle-password:hover {
            color: #1a252f;
        }
        .btn-login {
            padding: 10px;
            background: linear-gradient(45deg, #2c3e50, #4a69bd);
            border: none;
            border-radius: 6px;
            width: 100%;
            font-weight: bold;
            font-size: 1rem;
            color: white;
            transition: background 0.3s ease, transform 0.3s ease;
        }
        .btn-login:hover {
            background: linear-gradient(45deg, #1a252f, #3b5998);
            transform: translateY(-2px);
        }
        .alert {
            border-radius: 6px;
            font-size: 0.85rem;
        }
        .login-footer {
            text-align: center;
            margin-top: 15px;
            font-size: 0.8rem;
            color: #6c757d;
        }
        .form-label {
            color: #2c3e50;
            font-weight: 500;
            font-size: 0.9rem;
        }
        @media (max-width: 576px) {
            .login-container {
                margin: 15px;
                padding: 15px;
            }
            .login-title {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-container">
            <img src="<?= base_url("assets/img/logo.png") ?>" alt="Logo PTA Makassar">
        </div>
        <h2 class="login-title">Sistem Manajemen Disiplin Hakim</h2>
        
        <?php if (session()->getFlashdata("msg")): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata("msg") ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?= base_url("login/auth") ?>">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="password-wrapper">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                    </div>
                    <button type="button" class="toggle-password"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-login">LOGIN</button>
        </form>
        
        <div class="login-footer">
            <p>© <?= date("Y"); ?> Pengadilan Tinggi Agama Makassar</p>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS for Password Toggle -->
    <script>
        document.querySelector('.toggle-password').addEventListener('click', function () {
            const passwordInput = document.querySelector('#password');
            const icon = this.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>