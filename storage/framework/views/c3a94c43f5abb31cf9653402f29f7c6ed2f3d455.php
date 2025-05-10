<?php $__currentLoopData = $child_comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child_comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php $user_comment_reacts = json_decode($child_comment->user_reacts, true); ?>
    <!-- Comment item START -->
    <li class="comment-item n_comment_item c_details mb-0" id="comment_<?php echo e($child_comment->comment_id); ?>">
        <div class="d-flex justify-content-between">
            <div class="d-flex">
                <!-- Avatar -->
                <div class="">
                    <a href="#!" class="h-39"><img class="rounded-circle h-39" src="<?php echo e(get_user_image($child_comment->photo, 'optimized')); ?>" alt="Profile photo"></a>
                </div>
                <div class="avatar-info ms-2">
                    
                    
                    <div class="comment-details n_comment_details">
                        <div class="comment-content bg-secondary">
                            <h4 class="ava-nave"><?php echo e($child_comment->name); ?></h4>
                            <p><?php echo e($child_comment->description); ?></p>
                            <a href="javascript:void(0)" id="comment_reacts<?php echo $child_comment->comment_id; ?>">
                                <?php echo $__env->make('frontend.main_content.comment_reacts', ['comment_react' => true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </a>
                        </div>

                        <ul class="nav">
                            <li class="nav-item">
                                <p class="f-13">
                                    <?php echo e(date_formatter($child_comment->updated_at, 2)); ?>

                                </p>
                            </li>
                            <li class="nav-item post-react">
                                <a class="nav-link" href="javascript:void(0)" onclick="myCommentReact('like', 'toggle', <?php echo e($child_comment->comment_id); ?>)" id="my_comment_reacts<?php echo $child_comment->comment_id; ?>">
                                    <?php echo $__env->make('frontend.main_content.comment_reacts', ['my_react' => true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </a>

                                <ul class="react-list">
                                    <li><a href="javascript:void(0)" onclick="myCommentReact('like', 'update', <?php echo e($child_comment->comment_id); ?>)"><img src="<?php echo e(asset('storage/images/like.svg')); ?>" class="" alt="Like" style="margin-right: 1px;"></a>
                                    </li>
                                    <li><a href="javascript:void(0)" onclick="myCommentReact('love', 'update', <?php echo e($child_comment->comment_id); ?>)"><img src="<?php echo e(asset('storage/images/love.svg')); ?>" alt="Love" style="width: 30px; margin-top: 2px;"></a>
                                    </li>
                                    <li><a href="javascript:void(0)" onclick="myCommentReact('haha', 'update', <?php echo e($child_comment->comment_id); ?>)"><img src="<?php echo e(asset('storage/images/haha.svg')); ?>" alt="Haha"></a>
                                    </li>
                                    <li><a href="javascript:void(0)" onclick="myCommentReact('sad', 'update', <?php echo e($child_comment->comment_id); ?>)"><img src="<?php echo e(asset('storage/images/sad.svg')); ?>" class="mx-1" alt="Sad"></a>
                                    </li>
                                    <li><a href="javascript:void(0)" onclick="myCommentReact('angry', 'update', <?php echo e($child_comment->comment_id); ?>)"><img src="<?php echo e(asset('storage/images/angry.svg')); ?>" alt="Angry"></a>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                    </div>
                    
                </div>
            </div>

            <?php if(!empty(Auth()->user()) && Auth()->user()->id == $child_comment->user_id): ?>
                <div class="post-controls dropdown dotted">
                    <a class="dropdown-toggle" href="#" id="navbarDropdown"
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a href="javascript:void(0)" onclick="confirmAction('<?php echo route('comment.delete', ['comment_id' => $child_comment->comment_id]); ?>', true)" class="dropdown-item"><i class="fa fa-trash me-1"></i> <?php echo e(get_phrase('Delete Comment')); ?></a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>

    </li>
    <!-- Comment item END -->
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php /**PATH D:\OSPanel\home\boqiy-a.local\public\resources\views/frontend/main_content/child_comments.blade.php ENDPATH**/ ?>