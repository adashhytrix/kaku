@section('page-title', __tr("Manage User Uploads"))
@section('head-title', __tr("Manage User Uploads"))
@section('keywordName', strip_tags(__tr("Manage User Uploads")))
@section('keyword', strip_tags(__tr("Manage User Uploads")))
@section('description', strip_tags(__tr("Manage User Uploads")))
@section('keywordDescription', strip_tags(__tr("Manage User Uploads")))
@section('page-image', getStoreSettings('logo_image_url'))
@section('twitter-card-image', getStoreSettings('logo_image_url'))
@section('page-url', url()->current())

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-200"><?= __tr("Manage User Uploads") ?></h1>
</div>



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

	<a class="btn btn-danger btn-sm  lw-ajax-link-action-via-confirm"  data-callback-params="{{ json_encode(['datatableId' => '#lwManageUserPhotosTable']) }}" data-confirm="#lwPhotoDeleteContainer" data-method="post" data-action="<%= __tData.deleteImageUrl %>" data-callback="onSuccessAction" href data-method="post"><i class="fas fa-trash-alt"></i> <?= __tr('Delete') ?></a>
</script>
<!-- Pages Action Column -->

<!-- Title Column -->
<script type="text/_template" id="titleTemplate">

	<a target="_blank" href="<%= __tData.profile_url %>"><%= __tData.full_name %></a> 
</script>
<!-- Title Column -->

@lwPush('appScripts')
<script>
	// Perform actions after delete / restore / block
	var onSuccessAction = function(response, params) {
		reloadDT(params.datatableId);
	}
</script>
@lwPushEnd