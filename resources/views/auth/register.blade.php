<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - News CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #1A237E;
            --primary-light: #303F9F;
            --accent: #FFC107;
            --background: #FFFFFF;
            --text-dark: #2c3e50;
        }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(135deg, rgba(26, 35, 126, 0.85) 0%, rgba(48, 63, 159, 0.85) 100%), 
                        url('https://source.unsplash.com/random/1920x1080/?news,media,technology') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
        }

        .register-container {
            width: 100%;
            max-width: 440px;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .register-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            text-align: center;
            padding: 2.5rem 2rem;
            border: none;
        }

        .register-logo {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .card-body {
            padding: 2.5rem 2rem;
        }

        /* PERBAIKAN FORM FLOATING */
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem 3rem 1rem 3rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
            height: auto;
            width: 100%;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(26, 35, 126, 0.1);
            background: white;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            z-index: 3;
        }

        .form-label {
            position: absolute;
            left: 3rem;
            top: 1rem;
            color: #6c757d;
            transition: all 0.3s ease;
            pointer-events: none;
            background: transparent;
            padding: 0 0.25rem;
            z-index: 2;
        }

        .form-control:focus + .form-label,
        .form-control:not(:placeholder-shown) + .form-label {
            top: -0.5rem;
            left: 0.8rem;
            font-size: 0.875rem;
            color: var(--primary);
            background: white;
            z-index: 4;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            z-index: 3;
        }

        .btn-register {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26, 35, 126, 0.3);
        }

        .login-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }

        /* Role Selection Styles */
        .role-selection {
            margin-bottom: 1.5rem;
        }

        .role-options {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .role-option {
            flex: 1;
            text-align: center;
        }

        .role-input {
            display: none;
        }

        .role-label {
            display: block;
            padding: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .role-input:checked + .role-label {
            border-color: var(--primary);
            background: rgba(26, 35, 126, 0.05);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 35, 126, 0.1);
        }

        .role-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--primary);
        }

        .role-name {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .role-desc {
            font-size: 0.75rem;
            color: #6c757d;
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 2rem 1.5rem;
            }
            
            .card-header {
                padding: 2rem 1.5rem;
            }
            
            .register-logo {
                font-size: 2.5rem;
            }
            
            .role-options {
                flex-direction: column;
            }
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }

        .is-valid {
            border-color: #198754 !important;
        }

        .invalid-feedback {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="card-header">
                <div class="register-logo">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h2 class="h3 mb-2">Buat Akun Baru</h2>
                <p class="mb-0 opacity-75">Bergabung dengan komunitas pembaca kami</p>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf

                    <!-- Name -->
                    <div class="form-group">
                        <i class="input-icon fas fa-user"></i>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name') }}" required autofocus
                               placeholder=" ">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <i class="input-icon fas fa-envelope"></i>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required
                               placeholder=" ">
                        <label for="email" class="form-label">Alamat Email</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Role Selection -->
                    <div class="role-selection">
                        <label class="form-label">Pilih Peran</label>
                        <div class="role-options">
                            <div class="role-option">
                                <input type="radio" id="role_editor" name="role" value="editor" class="role-input" 
                                       {{ old('role') == 'editor' ? 'checked' : '' }} required>
                                <label for="role_editor" class="role-label">
                                    <div class="role-icon">
                                        <i class="fas fa-edit"></i>
                                    </div>
                                    <div class="role-name">Editor</div>
                                    <div class="role-desc">Edit & Upload Berita</div>
                                </label>
                            </div>
                            <div class="role-option">
                                <input type="radio" id="role_admin" name="role" value="admin" class="role-input"
                                       {{ old('role') == 'admin' ? 'checked' : '' }}>
                                <label for="role_admin" class="role-label">
                                    <div class="role-icon">
                                        <i class="fas fa-cogs"></i>
                                    </div>
                                    <div class="role-name">Admin</div>
                                    <div class="role-desc">Akses Penuh Sistem</div>
                                </label>
                            </div>
                        </div>
                        @error('role')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <i class="input-icon fas fa-lock"></i>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required placeholder=" ">
                        <label for="password" class="form-label">Password</label>
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <i class="input-icon fas fa-lock"></i>
                        <input id="password_confirmation" type="password" class="form-control" 
                               name="password_confirmation" required placeholder=" ">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <button type="submit" class="btn btn-register">
                        <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                    </button>

                    <div class="login-link">
                        <p class="mb-0">Sudah punya akun? 
                            <a href="{{ route('login') }}">Login di sini</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.parentNode.querySelector('.password-toggle i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Real-time validation
        document.getElementById('registerForm').addEventListener('input', function(e) {
            const input = e.target;
            if (input.value.trim() !== '') {
                input.classList.add('is-valid');
                input.classList.remove('is-invalid');
            } else {
                input.classList.remove('is-valid');
            }
        });

        // Role selection styling
        document.querySelectorAll('.role-input').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.role-label').forEach(label => {
                    label.style.borderColor = '#e9ecef';
                    label.style.background = '#f8f9fa';
                    label.style.transform = 'translateY(0)';
                    label.style.boxShadow = 'none';
                });
                
                if (this.checked) {
                    const label = this.nextElementSibling;
                    label.style.borderColor = 'var(--primary)';
                    label.style.background = 'rgba(26, 35, 126, 0.05)';
                    label.style.transform = 'translateY(-2px)';
                    label.style.boxShadow = '0 4px 12px rgba(26, 35, 126, 0.1)';
                }
            });
        });

        // Initialize role selection display
        document.addEventListener('DOMContentLoaded', function() {
            const checkedRole = document.querySelector('.role-input:checked');
            if (checkedRole) {
                const label = checkedRole.nextElementSibling;
                label.style.borderColor = 'var(--primary)';
                label.style.background = 'rgba(26, 35, 126, 0.05)';
                label.style.transform = 'translateY(-2px)';
                label.style.boxShadow = '0 4px 12px rgba(26, 35, 126, 0.1)';
            }
        });
    </script>
</body>
</html>