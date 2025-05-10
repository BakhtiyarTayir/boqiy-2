<?php echo $__env->make('auth.layout.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<!-- Main Start -->
<main class="main my-4 p-5">
    <div class="container register-page">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="login-img">
                    <img class="img-fluid" src="<?php echo e(asset('assets/frontend/images/login.png')); ?> " alt="">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="login-txt ms-0 ms-lg-5">
                    <h3><?php echo e(get_phrase('Register')); ?></h3>
                    
                    <form action="<?php echo e(route('register')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-group form-name">
                            <label for="name"><?php echo e(get_phrase('Full Name')); ?></label>
                            <input type="text" id="name" name="name" value="<?php echo e(old('name')); ?>" placeholder="<?php echo e(get_phrase('Your full name')); ?>">
                        </div>
                        <p class="text-danger"><?php echo e($errors->first('name')); ?></p>
                        
                        <?php if(0): ?>
                            <div class="form-group form-email">
                                <label for="email"><?php echo e(get_phrase('Email')); ?></label>
                                <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="<?php echo e(get_phrase('Enter your email address')); ?>">
                            </div>
                            <p class="text-danger"><?php echo e($errors->first('email')); ?></p>
                        <?php endif; ?>
                        
                        <div class="form-group form-phone">
                            <label for="phone"><?php echo e(get_phrase('Phone')); ?></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">+998</span>
                                </div>
                                <input type="tel" class="form-control" id="phone" name="phone" maxlength="9" placeholder="XX XXX XX XX" autocomplete="off" required>
                            </div>
                        </div>
                        <p class="text-danger"><?php echo e($errors->first('phone')); ?></p>
                        
                        <?php if(!empty(session('errorType')) && session('errorType') == 'hasPhone'): ?>
                            <p class="text-danger"><?php echo e(session('errorText')); ?></p>
                        <?php endif; ?>
                        
                        <div class="form-group form-pass">
                            <label for="password"><?php echo e(get_phrase('Password')); ?></label>
                            <input type="password" id="password" autocomplete="new-password" name="password" placeholder="********">
                        </div>
                        <p class="text-danger"><?php echo e($errors->first('password')); ?></p>
                        
                        <?php if(0): ?>
                            <input type="hidden" name="timezone" id="timezone" value="">
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" name="check1" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1"><?php echo e(get_phrase('I accept the')); ?>

                                    <a href="<?php echo e(route('term.view')); ?>"><?php echo e(get_phrase('Terms and Conditions')); ?></a></label>
                            </div>
                        <?php endif; ?>
                        
                        <input class="btn btn-primary my-3" type="submit" name="submit" id="submit" value="<?php echo e(get_phrase('Register')); ?>">
                    </form>
                
                </div>
            </div>
        </div>
    
    </div> <!-- container end -->
</main>
<!-- Main End -->



<?php echo $__env->make('auth.layout.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\OSPanel\home\boqiy-a.local\public\resources\views/auth/register.blade.php ENDPATH**/ ?>