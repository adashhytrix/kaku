<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="<?= config('CURRENT_LOCALE_DIRECTION') ?>">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1.0, user-scalable=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<title><?php echo $__env->yieldContent('head-title'); ?> : <?= getStoreSettings('name') ?></title>
	<!-- Custom fonts for this template-->
	<link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600,700&display=swap" rel="stylesheet">
	
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fuzzy+Bubbles:wght@400;700&display=swap" rel="stylesheet">
	<link rel="shortcut icon" href="<?= getStoreSettings('favicon_image_url') ?>" type="image/x-icon">
	<link rel="icon" href="<?= getStoreSettings('favicon_image_url') ?>" type="image/x-icon">
	<?php if(getStoreSettings('allow_recaptcha')): ?>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<?php endif; ?>

	<!-- Primary Meta Tags -->
	<meta name="title" content="<?php echo $__env->yieldContent('page-title'); ?>">
	<meta name="description" content="<?php echo $__env->yieldContent('description'); ?>">
	<meta name="keywordDescription" property="og:keywordDescription" content="<?php echo $__env->yieldContent('keywordDescription'); ?>">
	<meta name="keywordName" property="og:keywordName" content="<?php echo $__env->yieldContent('keywordName'); ?>">
	<meta name="keyword" content="<?php echo $__env->yieldContent('keyword'); ?>">
	<!-- Google Meta -->
	<meta itemprop="name" content="<?php echo $__env->yieldContent('page-title'); ?>">
	<meta itemprop="description" content="<?php echo $__env->yieldContent('description'); ?>">
	<meta itemprop="image" content="<?php echo $__env->yieldContent('page-image'); ?>">
	<!-- Open Graph / Facebook -->
	<meta property="og:type" content="website">
	<meta property="og:url" content="<?php echo $__env->yieldContent('page-url'); ?>">
	<meta property="og:title" content="<?php echo $__env->yieldContent('page-title'); ?>">
	<meta property="og:description" content="<?php echo $__env->yieldContent('description'); ?>">
	<meta property="og:image" content="<?php echo $__env->yieldContent('page-image'); ?>">
	<!-- Twitter -->
	<meta property="twitter:card" content="<?php echo $__env->yieldContent('twitter-card-image'); ?>">
	<meta property="twitter:url" content="<?php echo $__env->yieldContent('page-url'); ?>">
	<meta property="twitter:title" content="<?php echo $__env->yieldContent('page-title'); ?>">
	<meta property="twitter:description" content="<?php echo $__env->yieldContent('description'); ?>">
	<meta property="twitter:image" content="<?php echo $__env->yieldContent('page-image'); ?>">

	<!-- Custom styles for this template-->
	<?= __yesset([
		'dist/css/bootstrap-assets-app*.css',
		'dist/css/public-assets-app*.css',
		'dist/fa/css/all.min.css',
		"dist/css/vendorlibs-datatable.css",
		"dist/css/vendorlibs-photoswipe.css",
		"dist/css/vendorlibs-smartwizard.css",
		'dist/css/custom*.css',
		'dist/css/messenger*.css',
		'dist/css/login-register*.css'
	], true) ?>
    <style>
        body:not(.lw-ajax-form-ready) form.lw-ajax-form:before {
            content: "<?php echo e(__tr('please wait ...')); ?>";
        }
    </style>
	<?php echo $__env->yieldPushContent('header'); ?>
</head><?php /**PATH C:\xampp\htdocs\api-kaku.jurysoft.in (2)\api-kaku.jurysoft.in\resources\views/includes/header.blade.php ENDPATH**/ ?>