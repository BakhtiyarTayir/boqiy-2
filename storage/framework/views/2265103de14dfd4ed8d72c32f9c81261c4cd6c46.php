
<div class="profile-wrap">
    <div class="profile-cover  bg-white">
        <div class="profile-header" style="background-image: url('<?php echo e(route('showUserPhoto', $user_info->id)); ?>'); height: 300px">

        </div>
        <div class="n_profile_tab">
            <div class="n_main_tab">
                <div class="profile-avatar d-flex align-items-center">
                    <div class="avatar avatar-xl"><img class="rounded-circle"
                                                       src="<?php echo e(route('showUserPhoto', $user_info->id)); ?>" alt=""></div>
                    <div class="avatar-details">
                        <?php
                            $user_name = auth()->user()->name;
                        ?>
                        <h3 class="n_font"><?php echo e($user_name); ?></h3>
                        <?php if(auth()->user()->profile_status == 'lock'): ?>
                            <span class="lock_shield"><i class="fa-solid fa-shield"></i> <?php echo e(get_phrase('You locked your profile')); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="n_tab_follow ">
                    <?php
                        if (0) {
                            $friends = DB::table('friendships')
                                ->where(function ($query) {
                                    $query->where('accepter', Auth()->user()->id)->orWhere('requester', Auth()->user()->id);
                                })
                                ->where('is_accepted', 1)
                                ->orderBy('friendships.importance', 'desc');
                        }
                    ?>
                    
                    
                </div>
            </div>
            <nav class="profile-nav">
                <ul class="nav align-items-center justify-content-start">
                    <li class="nav-item <?php if(Route::currentRouteName() == 'profile'): ?> active <?php endif; ?>"><a
                                href="<?php echo e(route('profile')); ?>" class="nav-link"><?php echo e(get_phrase('Timeline')); ?></a></li>
                    <?php if(0): ?>
                        <li class="nav-item <?php if(Route::currentRouteName() == 'profile.friends'): ?> active <?php endif; ?>"><a
                                    href="<?php echo e(route('profile.friends')); ?>" class="nav-link"><?php echo e(get_phrase('Friends')); ?></a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item <?php if(Route::currentRouteName() == 'profile.photos' || Route::currentRouteName() == 'album.details.list'): ?> active <?php endif; ?>"><a
                                href="<?php echo e(route('profile.photos')); ?>" class="nav-link"><?php echo e(get_phrase('Photo')); ?></a>
                    </li>
                    <li class="nav-item <?php if(Route::currentRouteName() == 'profile.videos'): ?> active <?php endif; ?>"><a
                                href="<?php echo e(route('profile.videos')); ?>" class="nav-link"><?php echo e(get_phrase('Video')); ?></a>
                    </li>
                    <li class="nav-item <?php if(Route::currentRouteName() == 'profile.savePostList'): ?> active <?php endif; ?>"><a
                                href="<?php echo e(route('profile.savePostList')); ?>" class="nav-link"><?php echo e(get_phrase('Saved Posts')); ?></a>
                    </li>
                    <li class="nav-item <?php if(Route::currentRouteName() == 'profile.savePostList'): ?> active <?php endif; ?>">
                        <a href="<?php echo e(route('profileEdit', ['id' => auth()->user()->id])); ?>" class="nav-link"><?php echo e(get_phrase('Edit')); ?></a>
                    </li>
                    <li class="nav-item <?php if(Route::currentRouteName() == 'profile.checkins_list'): ?> active <?php endif; ?>">
                        <div class="post-controls dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false"><?php echo e(get_phrase('More')); ?>

                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">

                                <li>
                                    <a class="dropdown-item"  href="<?php echo e(route('profile.checkins_list')); ?>"> <?php echo e(get_phrase('Check-ins')); ?>

                                    </a>
                                </li>
                            </ul>
                        </div>

                    </li>
                </ul>


                <div class="post-controls dropdown dotted profile_tab_set">
                    <a class="dropdown-toggle" href="#" id="navbarDropdown"
                       role="button" data-bs-toggle="dropdown"
                       aria-expanded="false">
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li>
                            <?php if(auth()->user()->profile_status == 'lock'): ?>
                                <a class="dropdown-item" href="<?php echo e(route('profile.profileUnlock')); ?>"> <i class="fa-solid fa-lock-open"></i><?php echo e(get_phrase('UnLock Profile')); ?>

                                </a>
                            <?php else: ?>
                                <a class="dropdown-item"  href="<?php echo e(route('profile.profileLock')); ?>"> <i class="fa-solid fa-lock"></i><?php echo e(get_phrase('lock Profile')); ?>

                                </a>
                            <?php endif; ?>
                        </li>

                    </ul>
                </div>
            </nav>
        </div>

    </div>
    <div class="profile-content mt-3">
        <div class="row gx-3">
            <div class="col-lg-12 col-sm-12">

                <?php if(Route::currentRouteName() == 'profile.friends'): ?>
                    <?php echo $__env->make('frontend.profile.friends', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php elseif(Route::currentRouteName() == 'profile.photos'): ?>
                    <?php echo $__env->make('frontend.profile.photos', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <?php elseif(Route::currentRouteName() == 'album.details.list'): ?>
                    <?php echo $__env->make('frontend.profile.single_album_list_details', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <?php elseif(Route::currentRouteName() == 'profile.videos'): ?>
                    <?php echo $__env->make('frontend.profile.videos', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <?php elseif(Route::currentRouteName() == 'profile.savePostList'): ?>
                    <?php echo $__env->make('frontend.profile.savePostList', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <?php elseif(Route::currentRouteName() == 'profileEdit'): ?>
                    <?php echo $__env->make('frontend.profile.editProfile', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <?php elseif(Route::currentRouteName() == 'profile.checkins_list'): ?>
                    <?php echo $__env->make('frontend.profile.checkins_list', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <?php else: ?>
                    <?php echo $__env->make('frontend.main_content.create_post', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <div id="profile-timeline-posts">
                        <?php echo $__env->make('frontend.main_content.posts', ['type' => 'user_post'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>

                    <?php echo $__env->make('frontend.main_content.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endif; ?>
            </div>
            <!-- COL END -->
            
        </div>
    </div>
    <!-- Profile content End -->
</div>

<?php echo $__env->make('frontend.profile.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH /home/asilbek/Downloads/boqiy-uz/boqiy.uz-master/web/resources/views/frontend/profile/index.blade.php ENDPATH**/ ?>