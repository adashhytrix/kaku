<?php $__env->startSection('page-title', __tr("Manage User Uploads")); ?>
<?php $__env->startSection('head-title', __tr("Manage User Uploads")); ?>
<?php $__env->startSection('keywordName', strip_tags(__tr("Manage User Uploads"))); ?>
<?php $__env->startSection('keyword', strip_tags(__tr("Manage User Uploads"))); ?>
<?php $__env->startSection('description', strip_tags(__tr("Manage User Uploads"))); ?>
<?php $__env->startSection('keywordDescription', strip_tags(__tr("Manage User Uploads"))); ?>
<?php $__env->startSection('page-image', getStoreSettings('logo_image_url')); ?>
<?php $__env->startSection('twitter-card-image', getStoreSettings('logo_image_url')); ?>
<?php $__env->startSection('page-url', url()->current()); ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-200"><?= __tr("Manage User Uploads") ?></h1>
</div>
<!-- /Page Heading -->

<div class="row">
	<div class="col-xl-12">
		<!-- card -->
		<div class="card mb-4">
			<!-- card body -->
			<div class="card-body">
				<!-- table start -->
				<?php if (isset($component)) { $__componentOriginal71c6471fa76ce19017edc287b6f4508c = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.lw.datatable','data' => ['id' => 'lwManageUserPhotosTable','url' => route('manage.user.read.photos_list')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('lw.datatable'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'lwManageUserPhotosTable','url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('manage.user.read.photos_list'))]); ?>
                    <th data-template="#usersProfilePictureTemplate"  data-name="_uid"><?= __tr('Image') ?></th>
                        <th data-template="#titleTemplate" data-orderable="true"  data-name="first_name"><?= __tr('Full Name') ?></th>
                        <th data-template="#imageTypeTemplate" data-orderable="false" data-name="type"><?= __tr('Type') ?></th>
						<th data-order-type="desc" data-order-by="true" data-orderable="true"  data-name="updated_at"><?= __tr('Created On') ?></th>
                        <th data-template="#actionColumnTemplate" name="null"><?= __tr('Action') ?></th>
						<tbody class="lw-datatable-photoswipe-gallery"></tbody>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal71c6471fa76ce19017edc287b6f4508c)): ?>
<?php $component = $__componentOriginal71c6471fa76ce19017edc287b6f4508c; ?>
<?php unset($__componentOriginal71c6471fa76ce19017edc287b6f4508c); ?>
<?php endif; ?>
				<!-- table end -->
			</div>
			<!-- /card body -->
		</div>
		<!-- /card -->
	</div>
</div>
<!-- User Soft delete Container -->
<div id="lwPhotoDeleteContainer" style="display: none;">
	<h3><?= __tr('Are You Sure!') ?></h3>
	<strong><?= __tr('You want to delete this Photo') ?></strong>
</div>
<!-- User Soft delete Container -->

<script type="text/_template" id="usersProfilePictureTemplate">
	<%  if(__tData.type == 'photo') { %>
		<img class="lw-datatable-profile-picture lw-dt-thumbnail lw-photoswipe-gallery-img lw-lazy-img" src="<?= noThumbImageURL() ?>" data-src="<%= __tData.profile_image %>">
	<%  } else if(__tData.type == 'profile') {  %>
		<img class="lw-datatable-profile-picture lw-dt-thumbnail lw-photoswipe-gallery-img lw-lazy-img" src="<?= noThumbImageURL() ?>" data-src="<%= __tData.profile_image %>">
	<%  } else if(__tData.type == 'cover') {  %>
		<img class="lw-datatable-profile-picture lw-dt-thumbnail lw-photoswipe-gallery-img lw-lazy-img" src="<?= noThumbCoverImageURL() ?>" data-src="<%= __tData.profile_image %>">
	<%  }  %>
</script>
<script type="text/_template" id="imageTypeTemplate">
	<%  if(__tData.type == 'photo') { %>
		Uploaded Photo
	<%  } else if(__tData.type == 'profile') {  %>
		Profile Photo
	<%  } else if(__tData.type == 'cover') {  %>
		Cover Photo
	<%  }  %>

</script>

<!-- Pages Action Column -->
<script type="text/_template" id="actionColumnTemplate">

	<a class="btn btn-danger btn-sm  lw-ajax-link-action-via-confirm"  data-callback-params="<?php echo e(json_encode(['datatableId' => '#lwManageUserPhotosTable'])); ?>" data-confirm="#lwPhotoDeleteContainer" data-method="post" data-action="<%= __tData.deleteImageUrl %>" data-callback="onSuccessAction" href data-method="post"><i class="fas fa-trash-alt"></i> <?= __tr('Delete') ?></a>
</script>
<!-- Pages Action Column -->

<!-- Title Column -->
<script type="text/_template" id="titleTemplate">

	<a target="_blank" href="<%= __tData.profile_url %>"><%= __tData.full_name %></a> 
</script>
<!-- Title Column -->

    <?php if(!request()->ajax()): $__env->startPush('appScripts'); endif; ?>
<script>
	// Perform actions after delete / restore / block
	var onSuccessAction = function(response, params) {
		reloadDT(params.datatableId);
	}
</script>
<?php if(!request()->ajax()): $__env->stopPush(); endif; ?><?php /**PATH /home/creden/api-kaku.jurysoft.in/resources/views/user/photos/list.blade.php ENDPATH**/ ?>