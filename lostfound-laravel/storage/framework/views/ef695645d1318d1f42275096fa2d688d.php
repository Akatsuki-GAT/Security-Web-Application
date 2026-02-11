<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://public-frontend-cos.metadl.com/mgx/img/favicon.png" type="image/png">
    <title>Login - Lost&Found Hub</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="logo">
                    <i class="fas fa-search"></i>
                    <span>Lost&Found Hub</span>
                </div>
                <h1>Welcome to the Sign-in Page</h1>
                <p>Sign in to your account to continue</p>
            </div>

            <form action="/signin" id="login-form" class="auth-form" method="POST">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="signin_username">Username or Email</label>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="login" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="signin_password">Password</label>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="signinpassword" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-full">
                    <span class="btn-text">Sign In</span>
                    <i class="fas fa-spinner fa-spin btn-loading" style="display: none;"></i>
                </button>
                <?php if(session('error')): ?>
                <div class="form-message" id="form-message">
                    <?php echo e(session ('error')); ?>

                </div>
                <?php endif; ?>
            </form>
            
            <!---->
            <div class="auth-footer">
                <p>Don't have an account? <a href="<?php echo e(url('/register')); ?>">Sign up here</a></p>
                <!--<p><a href="<?php echo e(url('/index')); ?>">← Back to Home</a></p>-->
            </div>
        </div>
    </div>

    <script>
        const messageDiv = document.getElementById('form-message');
        messageDiv.classList.add('error');
        // Login form handling
       /* document.getElementById('login-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');
            const messageDiv = document.getElementById('form-message');
            
            // Show loading state
            submitBtn.disabled = true;
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline-block';
            messageDiv.textContent = '';
            messageDiv.className = 'form-message';
            
            try {
                const formData = new FormData(this);
                const response = await fetch('auth/login.php', {
                    method: 'POST',
                    body: formData
                });
                
                /*const result = await response.json();*/
                
                /*if (result.success) {
                    messageDiv.textContent = result.message;
                    messageDiv.classList.add('success');
                    
                    // Redirect after short delay
                    setTimeout(() => {
                        window.location.href = result.redirect || 'index.html';
                    }, 1000);
                } else {
                    messageDiv.textContent = result.message;
                    messageDiv.classList.add('error');
                }
            } *//*catch (error) {
                messageDiv.textContent = 'An error occurred. Please try again.';
                messageDiv.classList.add('error');
           /* } finally {
                // Reset button state
                submitBtn.disabled = false;
                btnText.style.display = 'inline-block';
                btnLoading.style.display = 'none';
            }
        });*/

        // Password toggle function
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.parentElement.querySelector('.password-toggle i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html><?php /**PATH C:\xampp\htdocs\lostfound-laravel\resources\views/login.blade.php ENDPATH**/ ?>