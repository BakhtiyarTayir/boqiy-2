<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><?php echo e(get_phrase('Top up balance')); ?></h5>
                </div>

                <div class="card-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('like_balance.process_topup')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <div class="form-group mb-4">
                            <label for="amount" class="form-label"><?php echo e(get_phrase('Amount')); ?></label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="amount" name="amount" value="<?php echo e(old('amount', 1000)); ?>" min="1000" step="1000" required>
                                <span class="input-group-text"><?php echo e(get_phrase('sum')); ?></span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <h6><?php echo e(get_phrase('Select amount')); ?>:</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-primary amount-btn" data-amount="5000">5000 <?php echo e(get_phrase('sum')); ?></button>
                                <button type="button" class="btn btn-outline-primary amount-btn" data-amount="10000">10000 <?php echo e(get_phrase('sum')); ?></button>
                                <button type="button" class="btn btn-outline-primary amount-btn" data-amount="30000">30000 <?php echo e(get_phrase('sum')); ?></button>
                                <button type="button" class="btn btn-outline-primary amount-btn" data-amount="35000">35000 <?php echo e(get_phrase('sum')); ?></button>
                                <button type="button" class="btn btn-outline-primary amount-btn" data-amount="60000">60000 <?php echo e(get_phrase('sum')); ?></button>
                                <button type="button" class="btn btn-outline-primary amount-btn" data-amount="120000">100000 <?php echo e(get_phrase('sum')); ?></button>
                                <button type="button" class="btn btn-outline-primary amount-btn" data-amount="150000">120000 <?php echo e(get_phrase('sum')); ?></button>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h6><?php echo e(get_phrase('Payment method')); ?>:</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="payme" value="payme" checked>
                                <label class="form-check-label" for="payme">
                                    <?php echo e(get_phrase('Payme')); ?>

                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group text-end">
                            <button type="submit" class="btn btn-primary"><?php echo e(get_phrase('Top up')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const amountBtns = document.querySelectorAll('.amount-btn');
        const amountInput = document.getElementById('amount');
        
        amountBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const amount = this.getAttribute('data-amount');
                amountInput.value = amount;
                
                // Remove active class from all buttons
                amountBtns.forEach(b => b.classList.remove('btn-primary', 'text-white'));
                amountBtns.forEach(b => b.classList.add('btn-outline-primary'));
                
                // Add active class to current button
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-primary', 'text-white');
            });
        });
    });
</script>
<?php $__env->stopPush(); ?> <?php /**PATH /home/asilbek/Downloads/boqiy-uz/boqiy.uz-master/web/resources/views/frontend/like_balance/topup.blade.php ENDPATH**/ ?>