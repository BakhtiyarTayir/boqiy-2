<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<?php
		$system_name = \App\Models\Setting::where('type', 'system_name')->value('description');
		$system_favicon = \App\Models\Setting::where('type', 'system_fav_icon')->value('description');
	?>
	<title><?php echo e($system_name); ?></title>
	
	<!-- CSRF Token for ajax for submission -->
	<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
	
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<link rel="shortcut icon" href="<?php echo e(get_system_logo_favicon($system_favicon, 'favicon')); ?>" />
	
	<!-- Font Awesome CSS -->
	<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/fontawesome/all.min.css')); ?>">
	<!-- CSS Library -->
	
	<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/owl.carousel.min.css')); ?>">
	
	<!-- Style css -->
	<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/nice-select.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/plyr/plyr.css')); ?>">
	<link href="<?php echo e(asset('assets/frontend/leafletjs/leaflet.css')); ?>" rel="stylesheet">
	
	<link href="<?php echo e(asset('assets/frontend/css/plyr_cdn_dw.css')); ?>" rel="stylesheet">
	<link href="<?php echo e(asset('assets/frontend/css/tagify.css')); ?>" rel="stylesheet">
	
	<link href="<?php echo e(asset('assets/frontend/uploader/file-uploader.css')); ?>" rel="stylesheet">
	<link href="<?php echo e(asset('assets/frontend/css/jquery-rbox.css')); ?>" rel="stylesheet">
	
	<!-- paid content start -->
	<link rel="apple-touch-icon" href="images/favicon.png" />
	<link rel="shortcut icon" href="images/favicon.ico" />
	<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/paid-content/css/style.css')); ?>" />
	<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/addon_layout.css')); ?>" />
	<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/paid-content/css/new_scss/new_style.css')); ?>" />
	<!-- paid content end -->
	
	<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/bootstrap.min.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/style.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/gallery/justifiedGallery.min.css')); ?>">
	<link href="<?php echo e(asset('assets/frontend/toaster/toaster.css')); ?>" rel="stylesheet">
	<link rel="stylesheet" type="text/css"
	      href="<?php echo e(asset('assets/frontend/summernote-0.8.18-dist/summernote-lite.min.css')); ?>" />
	<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/own.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/pc.style.css')); ?>" />
	
	
	<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/fundraiser/css/style_make.css')); ?>" />
	<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/fundraiser/css/custom_style.css')); ?>" />
	<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/fundraiser/css/new-style.css')); ?>" />
	<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/fundraiser/css/new-responsive.css')); ?>" />
	
	
	<!-- New -->
	<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/fundraiser/css/new_scss/new_style.css')); ?>" />
	
	<link rel="apple-touch-icon" href="<?php echo e(asset('assets/frontend/css/fundraiser/images/favicon.png')); ?>" />
	
	
	
	<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/daterangepicker.css')); ?>">
	
	
	<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/fundraiser/css/custom_new.css')); ?>" />
	
	
	<?php if(addon_status('job') == 1): ?>
		<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/job/style.css')); ?>" />
	<?php endif; ?>
	
	<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/fundraiser/css/custom_responsive.css')); ?>" />
	
	<!-- Likes System CSS -->
	<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/likes.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('assets/frontend/css/custom.css')); ?>">
	
	<script src="<?php echo e(asset('assets/frontend/js/jquery-3.6.0.min.js')); ?>"></script>
</head>
<?php if(Session::get('theme_color')): ?>
	<?php
		$theme_color = Session::get('theme_color');
		if ($theme_color === 'dark') {
			$image = asset('assets/frontend/images/white_sun.svg');
		} else {
		 
			$image = asset('assets/frontend/images/white_moon.svg');
		}
	?>
<?php else: ?>
	<?php
		$theme_color = 'default';
		$image = asset('assets/frontend/images/white_moon.svg');
	?>
<?php endif; ?>

<?php
	$themeColor = App\Models\Setting::where('type', 'theme_color')->value('description');
?>
<body class="<?php echo e($themeColor); ?> <?php echo e($theme_color); ?>">
<?php $user_info = Auth()->user() ?>

<?php if(1): ?>
	<?php echo $__env->make('auth.layout.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

<!-- Main Start -->
<main class="main my-4 mt-12 no-login-page">
	<div class="container">
		<div class="row">
			<div class="col-lg-3">
				<div style="position: sticky; top: 100px">
					<?php echo $__env->make('frontend.left_navigation', ['isNoLogin' => true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
				</div>
			</div>

			<!-- Content Section Start -->
			<div class="col-lg-6 col-sm-12 order-3 order-lg-2" style="max-height: calc(100vh - 120px);; overflow-y: auto;">
				<?php echo $__env->make('frontend.main_content.posts', ['type' => 'user_post'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

				<?php echo $__env->make('frontend.main_content.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
			</div>
		</div> <!-- row end -->
	
	</div> <!-- container end -->
</main>
<!-- Main End -->
<?php echo $__env->make('frontend.mobile_navigation', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!--Javascript
    ========================================================-->
<script src="<?php echo e(asset('assets/frontend/js/bootstrap.bundle.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/js/owl.carousel.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/js/venobox.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/js/timepicker.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/js/jquery.datepicker.min.js')); ?>"></script>


<script src="<?php echo e(asset('assets/frontend/js/jquery.nice-select.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/plyr/plyr.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/jquery-form/jquery.form.min.js')); ?>"></script>

<script src="<?php echo e(asset('assets/frontend/leafletjs/leaflet.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/leafletjs/leaflet-search.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/toaster/toaster.js')); ?>"></script>

<script src="<?php echo e(asset('assets/frontend/gallery/jquery.justifiedGallery.min.js')); ?>"></script>

<script src="<?php echo e(asset('assets/frontend/js/jQuery.tagify.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/js/jquery-rbox.js')); ?>"></script>


<script src="<?php echo e(asset('assets/frontend/js/plyr_cdn_dw.js')); ?>"></script>

<script src="<?php echo e(asset('js/share.js')); ?>"></script>

<script src="<?php echo e(asset('assets/frontend/uploader/file-uploader.js')); ?>"></script>

<script src="<?php echo e(asset('assets/frontend/summernote-0.8.18-dist/summernote-lite.min.js')); ?>"></script>



<script src="<?php echo e(asset('assets/frontend/css/fundraiser/js/custom_btn.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/css/fundraiser/js/new-script.js')); ?>"></script>





<script src="<?php echo e(asset('assets/frontend/paid-content/js/select2.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/paid-content/js/ckeditor.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/paid-content/js/jquery-tjgallery.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/paid-content/js/custom.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/paid-content/js/script.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/js/addon_layout.js')); ?>"></script>


<script src="<?php echo e(asset('assets/frontend/js/moment.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/frontend/js/daterangepicker.min.js')); ?>"></script>



<script src="<?php echo e(asset('assets/frontend/js/initialize.js')); ?>"></script>

<?php echo $__env->make('frontend.common_scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->make('frontend.toaster', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->make('frontend.initialize', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('modals.redirectRegisterModal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script>
	"use strict";

	$(document).ready(function() {
		$('[name=tag]').tagify({
			duplicates: false
		});
	});
</script>

<script>
	$(document).ready(function(){
		$('#dark').click(function() {
			$('.webgl body').toggleClass('test');
		});
	});
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>

</body>

</html>

<script>
	$(document).ready(function() {
		setTimeout(function() {
			showRegisterModal();
		}, 15000); // 1000 millisekund = 5 sekund
	});

	$('.no-login-page .no-login-post-gift').on('click', function (e) {
		showRegisterModal();

		e.preventDefault();
	});
	
	$('.no-login-page .no-login-post-like').on('click', function (e) {
		showRegisterModal();
		
		e.preventDefault();
	});
	
	$('.no-login-page .no-login-post-comment').on('click', function (e) {
		showRegisterModal();
		
		e.preventDefault();
	});

	$('.no-login-page .no-login-post-share').on('click', function (e) {
		showRegisterModal();
		
		e.preventDefault();
	});

	function showRegisterModal() {
		$("#registerModalAlert").modal({
			backdrop: 'static', // Tashqi joy bosilganda yopilmaydi
			keyboard: false     // ESC bosilganda yopilmaydi
		});
		$('#registerModalAlert').modal('show');
	}
</script><?php /**PATH /home/asilbek/Downloads/boqiy-uz/boqiy.uz-master/web/resources/views/no-login.blade.php ENDPATH**/ ?>