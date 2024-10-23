<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-200"><?= __tr('Add New Music') ?></h1>
	<!-- back button -->
	
	<!-- /back button -->
</div>
<!-- Page Heading -->

<!-- Start of Page Wrapper -->
<div class="row">
	<div class="col-xl-12 mb-4">
		<!-- card -->
		<div class="card mb-4">
			<!-- card body -->
			<div class="card-body">
				<!-- User add form -->
				<form class="lw-form" method="post" method="post" action="{{route('manage.music.musicstore')}}">
					<div class="form-group row">
						<!-- First Name -->
						<div class="col-sm-6 mb-3 mb-sm-0">
							<label for="lwFirstName"><?= __tr(' Name') ?></label>
							<input type="text" class="form-control form-control-user" name="name"  required minlength="3">
						</div>
						<!-- /First Name -->

						<!-- Last Name -->
						
						<!-- /Last Name -->
					</div>
					
					<!-- / status field -->
					<button type="submit" class="btn btn-primary lw-btn-block-mobile lw-ajax-form-submit-action"><?= __tr('Submit') ?></button>
				</form>
				<!-- /User add form -->
			</div>
			<!-- /card body -->
		</div>
		<!-- /card -->
	</div>
</div>
<!-- End of Page Wrapper -->