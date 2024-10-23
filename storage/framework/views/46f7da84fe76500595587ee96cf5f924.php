<?php $__env->startSection('page-title', __tr("Settings")); ?>
<?php $__env->startSection('head-title', __tr("Settings")); ?>
<?php $__env->startSection('keywordName', strip_tags(__tr("Settings"))); ?>
<?php $__env->startSection('keyword', strip_tags(__tr("Settings"))); ?>
<?php $__env->startSection('description', strip_tags(__tr("Settings"))); ?>
<?php $__env->startSection('keywordDescription', strip_tags(__tr("Settings"))); ?>
<?php $__env->startSection('page-image', getStoreSettings('logo_image_url')); ?>
<?php $__env->startSection('twitter-card-image', getStoreSettings('logo_image_url')); ?>
<?php $__env->startSection('page-url', url()->current()); ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-200"><?= __tr('Settings') ?></h1>
</div>
<!-- Page Heading -->
<?php $pageType = request()->pageType ?>
<div class="row">
    <div class="col-12">
        <!-- card start -->
        <div class="card">
            <!-- card body -->
            <div class="card-body">
                <!-- include related view -->
                <?php echo $__env->make('configuration.'. $pageType, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <!-- /include related view -->
            </div>
            <!-- /card body -->
        </div>
        <!-- card start -->
    </div>
</div><?php /**PATH /home/creden/api-kaku.jurysoft.in/resources/views/configuration/settings.blade.php ENDPATH**/ ?>