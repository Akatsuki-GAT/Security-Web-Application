<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://public-frontend-cos.metadl.com/mgx/img/favicon.png" type="image/png">
    <title>Register - Lost&Found Hub</title>
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
                <h1>Create Account</h1>
                <p>Join our community to help reunite lost items</p>
            </div>
            
            <form action="/submit" method="POST">
                <?php echo csrf_field(); ?>
                <div class="form-row">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input name="username" type="text" placeholder="Username" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-group">
                            <i class="fas fa-envelope"></i>
                            <input name="email" type="email" placeholder="Email" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <div class="input-group">
                        <i class="fas fa-id-card"></i>
                        <input name="full_name" type="text" placeholder="Jane Doe" patern="[A-Za-z]" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number (Optional)</label>
                    <div class="input-group">
                        <i class="fas fa-phone"></i>
                        <input name="contact" type="tel" placeholder="Contact No." pattern="^[0-9+#]+$">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input name ="password"
                                type="password"
                                placeholder="Password"

                                enz-enable
                                enz-show-strength-meter
                                enz-theme="default"
                                enz-min-password-strength="6"

                                enz-css-success-class="enz-success"
                                enz-css-fail-class="enz-fail"

                            />
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </button>
<!--This is where Enzoic is used...-->
                            
                        </div>
                    </div>

                    
                </div>

                <button type="submit" class="btn btn-primary btn-full">
                    <span class="btn-text">Create Account</span>
                    <i class="fas fa-spinner fa-spin btn-loading" style="display: none;"></i>
                </button>

                <div class="form-message" id="form-message"></div>
            </form>

            <div class="auth-footer">
                <p>Already have an account? <a href="<?php echo e(url('/login')); ?>">Sign in here</a></p>
                
            </div>
        </div>
    </div>


<script>
     if (
            typeof Enzoic === 'undefined' ||
            typeof Enzoic.currentPasswordScore === 'undefined'
        ) {
            messageDiv.textContent = 'Password strength service unavailable.';
            messageDiv.className = 'form-message error';
            return;
        }

            // Enzoic password strength check
            if (Enzoic.currentPasswordScore < Enzoic.PASSWORD_STRENGTH.Strong) {
                messageDiv.textContent = 'Password is not STRONG enough';
                messageDiv.className = 'form-message error';
                return;
            }

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
<script src="https://cdn.enzoic.com/js/enzoic.min.js" data-enzoic-dev-mode="true"></script>

    
    
</body>
</html><?php /**PATH C:\xampp\htdocs\lostfound-laravel\resources\views/register.blade.php ENDPATH**/ ?>