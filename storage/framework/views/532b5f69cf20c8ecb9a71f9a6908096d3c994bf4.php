<?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-6 col-sm-6 col-lg-6 mb-3 <?php if(str_contains(url()->current(), '/products')): ?> single-item-countable <?php endif; ?>">
        <div class="card product m_product">
            <a href="<?php echo e(route('sponsorProductView',$product->id)); ?>" class="thumbnail-196-196 mb-3">
                <img src="<?php echo e(route('showProductTypeFile', ['product_type_id' => $product->id])); ?>"
                     alt="<?php echo e($product->name); ?>"
                     class="img-fluid rounded"
                     style="max-width: 100%; height: auto;">

            </a>
            
            <div class="p-8 mt-5">
                <h3 class="h6 my-3"><a href="<?php echo e(route('single.product', $product->id)); ?>"><?php echo e(ellipsis($product->name, 18)); ?></a></h3>
                <a href="<?php echo e(route('sponsorProductView',$product->id)); ?>" class="btn common_btn d-block"><?php echo e($product->price_for_sponsor); ?> so`m</a>
            </div>
        </div>
    </div>
    
    <?php if(isset($search)&&!empty($search)): ?>
        <?php if($key == 2): ?>
            <?php break; ?>
        <?php endif; ?>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH /home/asilbek/Downloads/boqiy-uz/boqiy.uz-master/web/resources/views/frontend/marketplace/sponsor_product_list.blade.php ENDPATH**/ ?>