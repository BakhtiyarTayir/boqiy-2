<div class="main_content">
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h4><?php echo e(get_phrase('Marketplace Products')); ?></h4>
                    <a href="<?php echo e(route('admin.marketplace.products.create')); ?>" class="btn btn-primary">
                        <i class="bi bi-plus"></i> <?php echo e(get_phrase('Add New Product')); ?>

                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="eSection-wrap">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="btn-group">
                            <a href="<?php echo e(route('admin.marketplace.products')); ?>" class="btn btn-outline-primary <?php echo e($page_name == 'marketplace_products' ? 'active' : ''); ?>">
                                <?php echo e(get_phrase('All Products')); ?>

                            </a>
                            <a href="<?php echo e(route('admin.marketplace.products.featured')); ?>" class="btn btn-outline-success <?php echo e($page_name == 'marketplace_featured_products' ? 'active' : ''); ?>">
                                <?php echo e(get_phrase('Active Products')); ?>

                            </a>
                            <a href="<?php echo e(route('admin.marketplace.products.out-of-stock')); ?>" class="btn btn-outline-danger <?php echo e($page_name == 'marketplace_out_of_stock_products' ? 'active' : ''); ?>">
                                <?php echo e(get_phrase('Out of Stock')); ?>

                            </a>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <form action="<?php echo e(route('admin.marketplace.products')); ?>" method="GET" class="d-flex justify-content-end">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="<?php echo e(get_phrase('Search by title or description')); ?>" value="<?php echo e(request('search')); ?>">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <?php if(count($products) > 0): ?>
                    <div class="table-responsive">
                        <table class="table eTable">
                            <thead>
                            <tr>
                                <th width="60px" class="text-center">#</th>
                                <th width="100px" class="text-center"><?php echo e(get_phrase('Image')); ?></th>
                                <th style="width: 120px"><?php echo e(get_phrase('Nomi')); ?></th>
                                <th width="120px">Narx (sponsor)</th>
                                <th width="120px">Narx  (hammaga)</th>
                                <th width="120px"><?php echo e(get_phrase('Created')); ?></th>
                                <th width="150px" class="text-center"><?php echo e(get_phrase('Actions')); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                    <td class="text-center">
                                        <div class="product-image">
                                            <img src="<?php echo e(route('showProductTypeFile', ['product_type_id' => $product->id])); ?>" alt="<?php echo e($product->name); ?>" width="80" height="80" class="img-thumbnail"  style="object-fit: cover;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark"><?php echo e($product->name); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark"><?php echo e($product->price_for_sponsor); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark"><?php echo e($product->price_for_every_one); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-dark"><?php echo e($product->created_date); ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="<?php echo e(route('admin.marketplace.products.edit', $product->id)); ?>" class="btn btn-sm btn-primary" title="<?php echo e(get_phrase('Edit')); ?>">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="<?php echo e(route('admin.marketplace.products.destroy', $product->id)); ?>" class="btn btn-sm btn-danger" onclick="return confirm('<?php echo e(get_phrase('Are you sure you want to delete this product?')); ?>')" title="<?php echo e(get_phrase('Delete')); ?>">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty_box center">
                        <img class="mb-3" width="150px" src="<?php echo e(asset('public/assets/images/empty_box.png')); ?>" alt="">
                        <br>
                        <span class=""><?php echo e(get_phrase('No products found')); ?></span>
                        <br>
                        <a href="<?php echo e(route('admin.marketplace.products.create')); ?>" class="btn btn-primary mt-3">
                            <i class="bi bi-plus-circle"></i> <?php echo e(get_phrase('Add First Product')); ?>

                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel"><?php echo e(get_phrase('Confirm Delete')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php echo e(get_phrase('Are you sure you want to delete this product?')); ?>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(get_phrase('Cancel')); ?></button>
                <a href="#" id="deleteConfirmBtn" class="btn btn-danger"><?php echo e(get_phrase('Delete')); ?></a>
            </div>
        </div>
    </div>
</div>

<?php $__env->startSection('styles'); ?>
    <style>
        .product-image {
            position: relative;
            overflow: hidden;
            border-radius: 6px;
            transition: transform 0.3s;
        }
        
        .product-image:hover {
            transform: scale(1.05);
        }
        
        .btn-group .btn {
            margin: 0 2px;
        }
        
        .img-thumbnail {
            border: 2px solid #eee;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            // Delete product handling
            $('.delete-product').on('click', function() {
                var deleteUrl = $(this).data('url');
                $('#deleteConfirmBtn').attr('href', deleteUrl);
                $('#deleteConfirmationModal').modal('show');
            });
        });
    </script>
<?php $__env->stopSection(); ?><?php /**PATH /home/asilbek/Downloads/boqiy-uz/boqiy.uz-master/web/resources/views/backend/admin/marketplace/products.blade.php ENDPATH**/ ?>