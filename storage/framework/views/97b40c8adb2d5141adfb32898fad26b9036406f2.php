<div class="profile-wrap">
    <div class="profile-cover bg-white">
        <div class="profile-header" style="background-image: url('<?php echo e(get_cover_photo($user->cover_photo)); ?>');">
           <div class="cover-btn-group">
                <button
                onclick="showCustomModal('<?php echo e(route('load_modal_content', ['view_path' => 'frontend.profile.edit_profile'])); ?>', '<?php echo e(get_phrase('Edit your profile')); ?>');"
                class="edit-cover btn " data-bs-toggle="modal" data-bs-target="#edit-profile"><i
                    class="fa fa-pencil"></i><?php echo e(get_phrase('Edit Profile')); ?></button>
                <button  onclick="showCustomModal('<?php echo e(route('load_modal_content', ['view_path' => 'frontend.profile.edit_cover_photo'])); ?>', '<?php echo e(get_phrase('Update your cover photo')); ?>');"
                    class="edit-cover btn n_edit"><i class="fa fa-camera"></i><?php echo e(get_phrase('Edit Cover Photo')); ?></button>
            </div>
        </div>
            <div class="n_profile_tab">
                 <div class="n_main_tab">
                    <div class="profile-avatar d-flex align-items-center">
                        <div class="avatar avatar-xl"><img class="rounded-circle"
                                src="<?php echo e(get_user_image($user->photo, 'optimized')); ?>" alt=""></div>
                        <div class="avatar-details">
                            <h3 class="n_font"><?php echo e($user->name); ?></h3>
                            <?php if($user->profile_status == 'lock'): ?>
                            <span class="lock_shield"><i class="fa-solid fa-shield"></i> <?php echo e(get_phrase('You locked your profile')); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="n_tab_follow ">
                        <?php
                        $friends = DB::table('friendships')
                            ->where(function ($query) {
                                $query->where('accepter', Auth()->user()->id)->orWhere('requester', Auth()->user()->id);
                            })
                            ->where('is_accepted', 1)
                            ->orderBy('friendships.importance', 'desc');
                        ?>
                    </div>
                 </div>
            </div>
    </div>
    
    <div class="profile-content mt-3">
        <div class="row gx-3">
            <div class="col-lg-12 col-sm-12">
                <!-- Wallet Card -->
                <div class="card wallet-card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-wallet mr-2"></i> <?php echo e(get_phrase('My Wallet')); ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="wallet-balance text-center mb-4">
                            <h2 class="balance-value"><?php echo e(number_format($wallet->balance, 2)); ?></h2>
                            <p class="text-muted"><?php echo e(get_phrase('Wallet Balance')); ?></p>
                        </div>
                        
                        <div class="wallet-info mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-card bg-light p-3 rounded">
                                        <i class="fas fa-coins text-warning"></i>
                                        <h5><?php echo e(get_phrase('How to earn')); ?></h5>
                                        <p><?php echo e(get_phrase('Get credits for creating posts in the social network')); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-card bg-light p-3 rounded">
                                        <i class="fas fa-shopping-cart text-success"></i>
                                        <h5><?php echo e(get_phrase('How to use')); ?></h5>
                                        <p><?php echo e(get_phrase('Get products with credits')); ?></p>
                                        <a href="<?php echo e(route('allproducts')); ?>" class="btn btn-primary"><?php echo e(get_phrase('Marketplace')); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="recent-transactions">
                            <h5 class="mb-3"><i class="fas fa-history mr-2"></i> <?php echo e(get_phrase('Recent Transactions')); ?></h5>
                            
                            <?php if(count($transactions) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th><?php echo e(get_phrase('Date')); ?></th>
                                            <th><?php echo e(get_phrase('Type')); ?></th>
                                            <th><?php echo e(get_phrase('Description')); ?></th>
                                            <th class="text-right"><?php echo e(get_phrase('Amount')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($transaction->created_at->format('d.m.Y H:i')); ?></td>
                                            <td>
                                                <?php if($transaction->type == 'post_creation'): ?>
                                                    <span class="badge badge-success"><?php echo e(get_phrase('Post Creation')); ?></span>
                                                <?php elseif($transaction->type == 'deposit'): ?>
                                                    <span class="badge badge-primary"><?php echo e(get_phrase('Deposit')); ?></span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary"><?php echo e($transaction->type); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($transaction->description); ?></td>
                                            <td class="text-right <?php echo e($transaction->amount > 0 ? 'text-success' : 'text-danger'); ?>">
                                                <?php echo e($transaction->amount > 0 ? '+' : ''); ?><?php echo e(number_format($transaction->amount, 2)); ?>

                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="d-flex justify-content-center mt-4">
                                <?php echo e($transactions->links()); ?>

                            </div>
                            
                            <div class="text-center mt-3">
                                <a href="<?php echo e(route('wallet.transactions')); ?>" class="btn btn-outline-primary"><?php echo e(get_phrase('View All Transactions')); ?></a>
                            </div>
                            <?php else: ?>
                            <div class="alert alert-info">
                                <?php echo e(get_phrase('You have no transactions yet. Create a post to earn credits!')); ?>

                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- End Wallet Card -->
                
               

                <?php echo $__env->make('frontend.main_content.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>
</div>

<?php echo $__env->make('frontend.profile.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<style>
.wallet-card {
    border-radius: 15px;
    overflow: hidden;
}
.wallet-balance {
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 10px;
}
.balance-value {
    font-size: 3rem;
    font-weight: bold;
    color: #28a745;
}
.info-card { 
    height: 100%;
    border-radius: 10px;
    transition: all 0.3s;
}
.info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.info-card i {
    font-size: 2rem;
    margin-bottom: 10px;
}
</style>

<?php /**PATH D:\OSPanel\home\boqiy-a.local\public\resources\views/frontend/wallet/index.blade.php ENDPATH**/ ?>