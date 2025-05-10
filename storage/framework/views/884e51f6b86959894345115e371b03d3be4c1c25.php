<?php if(0): ?>
<div class="main_content">
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h4><?php echo e(get_phrase('Create New Product')); ?></h4>
                    <a href="<?php echo e(route('admin.marketplace.products')); ?>" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> <?php echo e(get_phrase('Back to Products')); ?>

                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="eSection-wrap">
                <form method="POST" action="<?php echo e(route('admin.marketplace.products.store')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="eForm-layouts">
                                <div class="mb-3">
                                    <label for="title" class="form-label"><?php echo e(get_phrase('Title')); ?> <span class="required">*</span></label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="title" name="title" value="<?php echo e(old('title')); ?>" required>
                                    <?php $__errorArgs = ['title'];
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

                                <div class="mb-3">
                                    <label for="description" class="form-label"><?php echo e(get_phrase('Description')); ?></label>
                                    <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description" name="description" rows="5"><?php echo e(old('description')); ?></textarea>
                                    <?php $__errorArgs = ['description'];
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

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="user_id" class="form-label"><?php echo e(get_phrase('Seller')); ?> <span class="required">*</span></label>
                                            <select class="form-select <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="user_id" name="user_id" required>
                                                <option value=""><?php echo e(get_phrase('Select Seller')); ?></option>
                                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($user->id); ?>" <?php echo e(old('user_id') == $user->id ? 'selected' : ''); ?>><?php echo e($user->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <?php $__errorArgs = ['user_id'];
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
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="location" class="form-label"><?php echo e(get_phrase('Location')); ?></label>
                                            <input type="text" class="form-control <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="location" name="location" value="<?php echo e(old('location')); ?>">
                                            <?php $__errorArgs = ['location'];
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
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label"><?php echo e(get_phrase('Price')); ?> <span class="required">*</span></label>
                                            <input type="number" class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="price" name="price" value="<?php echo e(old('price')); ?>" min="0" step="0.01" required>
                                            <?php $__errorArgs = ['price'];
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
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="currency" class="form-label"><?php echo e(get_phrase('Currency')); ?></label>
                                            <select class="form-select <?php $__errorArgs = ['currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="currency" name="currency">
                                                <option value=""><?php echo e(get_phrase('Select Currency')); ?></option>
                                                <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($currency->id); ?>" <?php echo e(old('currency') == $currency->id ? 'selected' : ''); ?>><?php echo e($currency->name); ?> (<?php echo e($currency->symbol); ?>)</option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <?php $__errorArgs = ['currency'];
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
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category" class="form-label"><?php echo e(get_phrase('Category')); ?></label>
                                            <select class="form-select <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="category" name="category">
                                                <option value=""><?php echo e(get_phrase('Select Category')); ?></option>
                                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($category->id); ?>" <?php echo e(old('category') == $category->id ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <?php $__errorArgs = ['category'];
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
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="brand" class="form-label"><?php echo e(get_phrase('Brand')); ?></label>
                                            <select class="form-select <?php $__errorArgs = ['brand'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="brand" name="brand">
                                                <option value=""><?php echo e(get_phrase('Select Brand')); ?></option>
                                                <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($brand->id); ?>" <?php echo e(old('brand') == $brand->id ? 'selected' : ''); ?>><?php echo e($brand->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <?php $__errorArgs = ['brand'];
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
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="condition" class="form-label"><?php echo e(get_phrase('Condition')); ?> <span class="required">*</span></label>
                                            <select class="form-select <?php $__errorArgs = ['condition'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="condition" name="condition" required>
                                                <option value=""><?php echo e(get_phrase('Select Condition')); ?></option>
                                                <option value="new" <?php echo e(old('condition') == 'new' ? 'selected' : ''); ?>><?php echo e(get_phrase('New')); ?></option>
                                                <option value="used" <?php echo e(old('condition') == 'used' ? 'selected' : ''); ?>><?php echo e(get_phrase('Used')); ?></option>
                                            </select>
                                            <?php $__errorArgs = ['condition'];
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
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label"><?php echo e(get_phrase('Status')); ?> <span class="required">*</span></label>
                                            <select class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="status" name="status" required>
                                                <option value=""><?php echo e(get_phrase('Select Status')); ?></option>
                                                <option value="1" <?php echo e(old('status') == '1' ? 'selected' : ''); ?>><?php echo e(get_phrase('Active')); ?></option>
                                                <option value="0" <?php echo e(old('status') == '0' ? 'selected' : ''); ?>><?php echo e(get_phrase('Out of Stock')); ?></option>
                                            </select>
                                            <?php $__errorArgs = ['status'];
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
                                </div>

                                <div class="mb-3">
                                    <label for="buy_link" class="form-label"><?php echo e(get_phrase('Buy Link')); ?></label>
                                    <input type="url" class="form-control <?php $__errorArgs = ['buy_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="buy_link" name="buy_link" value="<?php echo e(old('buy_link')); ?>">
                                    <small class="text-muted"><?php echo e(get_phrase('Optional. Add an external link where customers can directly purchase this product (e.g. a link to an online store).')); ?></small>
                                    <?php $__errorArgs = ['buy_link'];
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
                        </div>

                        <div class="col-md-4">
                            <div class="eForm-layouts">
                                <div class="mb-3">
                                    <label for="multiple_files" class="form-label"><?php echo e(get_phrase('Product Images')); ?></label>
                                    <input type="file" class="form-control <?php $__errorArgs = ['multiple_files'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="multiple_files" name="multiple_files[]" multiple accept="image/*">
                                    <small class="text-muted"><?php echo e(get_phrase('You can select multiple images. The first image will be used as the main image.')); ?></small>
                                    <?php $__errorArgs = ['multiple_files'];
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
                                <div id="image_preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary"><?php echo e(get_phrase('Create Product')); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php
    $isUpdate = !empty($productType);
?>
<div class="main_content">
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h4><?php echo e(get_phrase('Create New Product')); ?></h4>
                    <a href="<?php echo e(route('admin.marketplace.products')); ?>" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> <?php echo e(get_phrase('Back to Products')); ?>

                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="eSection-wrap ">
                <form method="POST" action="<?php echo e($isUpdate ? route('admin.marketplace.products.update', ['id' => $productType->id]) : route('admin.marketplace.products.store')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <label class="col-md-2 text-end">
                            Nomi
                            <span class="required text-danger">*</span>
                        </label>
                        <div class="col-md-6">
                            <input type="text"
                                   class="form-control"
                                   id="name"
                                   name="name"
                                   value="<?php echo e(old('name') ? old('name') : ($isUpdate ? $productType->name : '')); ?>" required>
                            <?php $__errorArgs = ['name'];
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
                            Text (sponsor)
                            <span class="required text-danger">*</span>
                        </label>
                        <div class="col-md-6">
                             <textarea class="form-control"
                                       id="text_for_sponsor"
                                       name="text_for_sponsor"
                                       rows="5"
                                       required
                                       tabindex="2"><?php echo e(old('text_for_sponsor') ? old('text_for_sponsor') : ($isUpdate ? $productType->text_for_sponsor : '')); ?></textarea>
    
                            <?php $__errorArgs = ['text_for_sponsor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
    
                    <div class="row mt-4">
                        <label for="text_for_sponsor" class="form-label col-md-2 text-end">
                            Text (hammaga)
                            <span class="required text-danger">*</span>
                        </label>
                        <div class="col-md-6">
                             <textarea class="form-control"
                                       id="text_for_every_one"
                                       name="text_for_every_one"
                                       rows="5"
                                       tabindex="2"
                                       required
                             ><?php echo e(old('text_for_every_one') ? old('text_for_every_one') : ($isUpdate ? $productType->text_for_every_one : '')); ?></textarea>
                            <?php $__errorArgs = ['text_for_every_one'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <label class="col-md-2 text-end">
                            Narx (sponsor)
                            <span class="required text-danger">*</span>
                        </label>
                        <div class="col-md-6">
                            <input class="form-control" type="number"
                                   id="price_for_sponsor"
                                   name="price_for_sponsor"
                                   min="0" step="0.01" required
                                   value="<?php echo e(old('price_for_sponsor') ? old('price_for_sponsor') : ($isUpdate ? $productType->price_for_sponsor : '')); ?>"
                            >
                        </div>
                    </div>
                    <div class="row mt-4">
                        <label class="col-md-2 text-end">
                            Narx (hammaga)
                            <span class="required text-danger">*</span>
                        </label>
                        <div class="col-md-6">
                            <input type="number"
                                   class="form-control"
                                   id="price_for_every_one"
                                   name="price_for_every_one"
                                   min="0" step="0.01" required
                                   value="<?php echo e(old('price_for_every_one') ? old('price_for_every_one') : ($isUpdate ? $productType->price_for_every_one : '')); ?>"
                            >
                        </div>
                    </div>
                    <div class="row mt-4">
                        <label for="multiple_files" class="form-label col-md-2 text-end">Image</label>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <input type="file"
                                       class="form-control"
                                       id="image"
                                       name="image"
                                       accept="image/png, image/jpg, image/jpeg">
                                <?php if(0): ?>
                                    <small class="text-muted"><?php echo e(get_phrase('You can select multiple images. The first image will be used as the main image.')); ?></small>
                                <?php endif; ?>
                                <?php $__errorArgs = ['multiple_files'];
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
                            <div id="image_preview" class="d-flex flex-wrap gap-2 mt-2"></div>
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
<?php $__env->stopSection(); ?> <?php /**PATH D:\OSPanel\home\boqiy-a.local\public\resources\views/backend/admin/marketplace/product_create.blade.php ENDPATH**/ ?>