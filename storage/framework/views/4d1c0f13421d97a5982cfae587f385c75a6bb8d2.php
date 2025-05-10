<?php
    $isUpdate = isset($freeProduct) && !empty($freeProduct);
?>
<div class="main_content">
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h4><?php echo e(get_phrase('Create New Free Product')); ?></h4>
                    <a href="<?php echo e(route('admin.marketplace.free.products')); ?>" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> <?php echo e(get_phrase('Back to Products')); ?>

                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="eSection-wrap ">
                <form method="POST" action="<?php echo e($isUpdate && isset($freeProduct) ? route('admin.marketplace.free.products.update', ['id' => $freeProduct->id]) : route('admin.marketplace.free.products.store')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <label class="col-md-2 text-end">
                            Product
                            <span class="required text-danger">*</span>
                        </label>
                        <div class="col-md-6">
                            <select name="product_type_id" class="form-select eForm-control select2" <?php if($isUpdate && isset($freeProduct)): ?> disabled <?php endif; ?> required>
                                <?php
                                    $product_type_id = old('product_type_id') ?: ($isUpdate && isset($freeProduct) ? $freeProduct->product_type_id : null);
                                ?>
                                <?php if(!empty($product_types) && count($product_types)): ?>
                                    <?php $__currentLoopData = $product_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $selected = $product_type_id == $product_type->id ? 'selected' : '';
                                        ?>
                                        <option value="<?php echo e($product_type->id); ?>" <?php echo e($selected); ?>>
                                            <?php echo e($product_type->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
    
                            <?php if($isUpdate && isset($freeProduct)): ?>
                                <input type="hidden" name="product_type_id" value="<?php echo e($freeProduct->product_type_id); ?>">
                            <?php endif; ?>
    
                            <?php $__errorArgs = ['product_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <label for="text_for_sponsor" class="form-label col-md-2 text-end">
                            Sponsor
                            <span class="required text-danger">*</span>
                        </label>
                        <div class="col-md-6">
                            <select name="sponsor_id"
                                    class="form-select eForm-control select2"
                                    <?php if($isUpdate && isset($freeProduct) && $freeProduct->is_payment_sponsor): ?>
                                    disabled
                                    <?php endif; ?>
                                    required>
                                <?php
                                    $sponsor_id = old('sponsor_id') ?: ($isUpdate && isset($freeProduct) ? $freeProduct->sponsor_id : null);
                                ?>
                                <?php if(!empty($users) && count($users)): ?>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $selected = $sponsor_id == $user->id ? 'selected' : '';
                                        ?>
                                        <option value="<?php echo e($user->id); ?>" <?php echo e($selected); ?>> <?php echo e($user->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                            <?php if($isUpdate && isset($freeProduct) && $freeProduct->is_payment_sponsor): ?>
                                <input type="hidden" name="sponsor_id" value="<?php echo e($freeProduct->sponsor_id); ?>">
                            <?php endif; ?>
                            <?php $__errorArgs = ['sponsor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
    
                    <div class="row mt-4">
                        <label for="text_for_sponsor" class="form-label col-md-2 text-end">
                            Qabul qiluvchi
                        </label>
                        <div class="col-md-6">
                            <select name="receiver_id"
                                    class="form-select eForm-control select2"
                                    <?php if($isUpdate && isset($freeProduct) && $freeProduct->is_sold): ?>
                                        disabled
                                    <?php endif; ?>
                            >
                                <option value="">Tanlanmadi</option>
                                <?php
                                    $receiver_id = old('receiver_id') ?: ($isUpdate && isset($freeProduct) ? $freeProduct->receiver_id : null);
                                ?>

                                <?php if(!empty($users) && count($users)): ?>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $selected = $receiver_id == $user->id ? 'selected' : '';
                                        ?>
                                        <option value="<?php echo e($user->id); ?>" <?php echo e($selected); ?>> <?php echo e($user->name . ' email: ' . $user->email); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                            <?php if($isUpdate && isset($freeProduct) && $freeProduct->is_sold): ?>
                                <input type="hidden" name="receiver_id" value="<?php echo e($freeProduct->receiver_id); ?>">
                            <?php endif; ?>
                            <?php $__errorArgs = ['receiver_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
    
                    <div class="row mt-4">
                        <label for="deadline_hour" class="form-label col-md-2 text-end">
                            Topshirilgandan keyingi ko'rinish vaqti
                        </label>
                        <div class="col-md-6">
                            <input class="form-control" type="number" min="1" step="1"  id="deadline_hour" name="deadline_hour"
                                   value="<?php echo e(old('deadline_hour') ?? ($isUpdate && isset($freeProduct->deadline_hour) ? $freeProduct->deadline_hour : 36)); ?>">
                            <?php $__errorArgs = ['receiver_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="row mt-4 form-check my-4">
                        <div class="col-md-6 offset-2">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                   id="is_active"
                                   value="1"
                                    <?php echo e(old('is_active', $isUpdate && isset($freeProduct) ? $freeProduct->is_active : 0) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="is_active">
                                Bepul tovarlar ro'yxatida turish
                            </label>
                        </div>

                    </div>

                    <div class="row mt-4">
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary"><?php echo e(get_phrase('Save')); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startSection('scripts'); ?>
<script>
    $(document).ready(function() {
        // Image preview
        $('#multiple_files').on('change', function() {
            $('#image_preview').html('');
            if (this.files) {
                $.each(this.files, function(i, file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#image_preview').append('<div class="position-relative"><img src="' + e.target.result + '" alt="Preview" class="img-thumbnail" style="height: 100px; width: 100px; object-fit: cover;"></div>');
                    }
                    reader.readAsDataURL(file);
                });
            }
        });
    });
</script>
<?php $__env->stopSection(); ?> <?php /**PATH D:\OSPanel\home\boqiy-a.local\public\resources\views/backend/admin/marketplace/free_product_create.blade.php ENDPATH**/ ?>