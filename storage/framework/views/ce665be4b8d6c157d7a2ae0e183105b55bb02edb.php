<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><?php echo e(get_phrase('Likes Balance')); ?></h5>
                    <a href="<?php echo e(route('like_balance.topup')); ?>" class="btn btn-primary btn-sm"><?php echo e(get_phrase('Top Up Balance')); ?></a>
                </div>
                
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>
                    
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>
                    
                    <div class="text-center mb-4">
                        <h2><?php echo e($userLikeBalance ? number_format($userLikeBalance->balance, 2) : 0); ?></h2>
                        <p class="text-muted"><?php echo e(get_phrase('Current Likes Balance')); ?></p>
                    </div>
                    
                    <div class="p-4 bg-light rounded shadow-sm text-center">
                        <?php if(!empty($todayUserTransactions) && !empty($todayAllAmount)): ?>
                            <h4>Bugun</h4>
                            <h5 class="text-success fw-bold mb-3">
                                <?php echo e($todayUserTransactions); ?> ota-ona va ziyoli insonlar bolalarning bilimini qo‘llab-quvvatladi!
                            </h5>
                            <p class="fs-5 text-dark mb-2">
                                <strong><?php echo e($todayAllAmount); ?> so‘m</strong> like xarididan tushgan mablag‘ to‘liq
                                <span class="text-primary fw-semibold">bepul sovg‘alarga</span> sarflanadi.
                            </p>
                            <p class="fs-5 text-muted fst-italic">
                                Har bir like – bilimga qo‘shilgan qiymat!
                            </p>
                            <div class="mt-3">
                                <span class="btn2 text-success btn-lg">
                                    Siz ham qo‘shiling – farzandingiz bilimini rag‘batlantiring!
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><?php echo e(get_phrase('Transaction History')); ?></h5>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th><?php echo e(get_phrase('Date')); ?></th>
                                <th><?php echo e(get_phrase('Type')); ?></th>
                                <th><?php echo e(get_phrase('Description')); ?></th>
                                <th class="text-end"><?php echo e(get_phrase('Amount')); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($transaction->created_at->format('d.m.Y H:i')); ?></td>
                                    <td>
                                        <?php if($transaction->type == 'deposit'): ?>
                                            <span class="badge bg-success"><?php echo e(get_phrase('Deposit')); ?></span>
                                        <?php elseif($transaction->type == 'like_purchase'): ?>
                                            <span class="badge bg-primary"><?php echo e(get_phrase('Like Purchase')); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?php echo e($transaction->type); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($transaction->description); ?></td>
                                    <td class="text-end <?php echo e($transaction->amount > 0 ? 'text-success' : 'text-danger'); ?>">
                                        <?php echo e($transaction->amount > 0 ? '+' : ''); ?><?php echo e(number_format($transaction->amount, 2)); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center"><?php echo e(get_phrase('No transactions')); ?></td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($transactions->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH D:\OSPanel\home\boqiy-a.local\public\resources\views/frontend/like_balance/index.blade.php ENDPATH**/ ?>