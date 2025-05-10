<?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-6 col-sm-6 col-lg-6 mb-3 <?php if(str_contains(url()->current(), '/products')): ?> single-item-countable <?php endif; ?>">
        <div class="card product m_product">
            <a href="<?php echo e(route('single.product',$product->id)); ?>" class="thumbnail-196-196 mb-3">
                <img src="<?php echo e(route('showProductTypeFile', ['product_type_id' => $product->product_type_id])); ?>" alt="<?php echo e($product->name); ?>" style="max-width: 300px;">
            </a>
            <div class="p-8 mt-4">
                <h3 class="h6 mt-3">
                    <a href="<?php echo e(route('single.product', $product->id)); ?>">
                        <?php echo e(ellipsis($product->name, 18)); ?>

                    </a>
                    <?php if($product->is_sold): ?>
                        <span class="badge bg-warning text-dark ml-2">Sovg‘a topshirilgan</span>
                    <?php endif; ?>
                </h3>
                
                <a href="<?php echo e(route('single.product',$product->id)); ?>" class="btn common_btn d-block mt-2">
                    <?php echo e($product->price_for_every_one); ?> so`m
                </a>
                
                <?php if(!$product->is_anonymous_sponsor): ?>
                    <hr class="mt-3">
                    <h5>Homiy</h5>
                    <div class="pb-author align-items-center mt-3">
                        <a href="<?php echo e(route('user.profile.view', $product->sponsor_id)); ?>">
                            <img class="user_image_proifle_height"
                                 src="<?php echo e(route('showUserPhoto', $product->sponsor_id)); ?>"
                                 alt="<?php echo e($product->sponsor_name); ?>"
                                 style="width: 20%; height: auto"
                                 onerror="this.onerror=null;this.src='<?php echo e(asset('images/default-user.png')); ?>';">
                        
                        </a>
                        <div class="pb-info mt-1">
                            <a href="<?php echo e(route('user.profile.view', $product->sponsor_id)); ?>" class="h6 text-primary">
                                <?php echo e($product->sponsor_name); ?>

                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php if(isset($search)&&!empty($search)): ?>
        <?php if($key == 2): ?>
            <?php break; ?>
        <?php endif; ?>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH D:\OSPanel\home\boqiy-a.local\public\resources\views/frontend/marketplace/product-single.blade.php ENDPATH**/ ?>