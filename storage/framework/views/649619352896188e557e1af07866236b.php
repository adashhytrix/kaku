<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0 text-gray-200"><?= __tr('Add New User') ?></h1>
	<!-- back button -->
</div>

<!-- Start of Page Wrapper -->
<div class="row">
	<div class="col-xl-12 mb-4">
		<!-- card -->
		<div class="card mb-4">
			<!-- card body -->
			<div class="card-body">
				<!-- User add form -->
				<form class="lw-form" method="post" action="<?php echo e(route('manage.intrest.intreststore')); ?>" enctype="multipart/form-data">
					<?php echo csrf_field(); ?> <!-- Add CSRF token -->
					
					<div class="form-group row">
						<!-- Interests Text Area -->
						<div class="col-sm-6 mb-3 mb-sm-0">
							<label for="lwInterests"><?= __tr('Interests') ?></label>
							<textarea class="form-control" name="interests" placeholder="Enter interests separated by commas"></textarea>
						</div>

						<!-- CSV File Upload -->
						<div class="col-sm-6 mb-3 mb-sm-0">
							<label for="csvFile"><?= __tr('Upload CSV') ?></label>
							<input type="file" class="form-control" name="csv_file" accept=".csv">
							<small class="form-text text-muted"><?= __tr('Upload a CSV file with interests, one per line.') ?></small>
						</div>
					</div>

					<!-- Submit Button -->
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
<?php /**PATH /home/creden/api-kaku.jurysoft.in/resources/views/user/intrest/add.blade.php ENDPATH**/ ?>