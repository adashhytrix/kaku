<div class="row">
	<div class="col-xl-12">
		<!-- card -->
		<div class="card mb-4">
			<!-- card body -->
			<div class="card-body">
				<ul class="nav nav-tabs">
					

					
					<li class="nav-item">
						<a  class="nav-link lw-ajax-link-action lw-action-with-url nav-link "
							href="<?php echo e(route('manage.intrest.intrestadd')); ?>">
							<?= __tr('Add Intrest') ?>
						</a>
					</li>
					<!-- /Blocked Tab -->
				</ul>
				<!-- table start -->
				<div class="lw-nav-content">
					<table class="table table-hover" id="lwManageUsersTable">
						<!-- table headings -->
						<thead>
							<tr>
                                <th class="lw-dt-nosort text-white">
									<?= __tr('SL#') ?>
								</th>
								<th class="lw-dt-nosort text-white">
									<?= __tr('Name') ?>
								</th>
                                <th class="lw-dt-nosort text-white">
									<?= __tr('Action') ?>
								</th>
							</tr>
						</thead>
						<!-- /table headings -->
						<tbody class="lw-datatable-photoswipe-gallery">
                            <?php $__currentLoopData = $intrest; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=> $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                
                           
                            <tr>
                                <td class="text-white"><?php echo e($k+1); ?></td>
                                <td class="text-white"><?php echo e($p->name); ?></td>
                                <td class="text-white">
                                    <a class="btn btn-danger " href="<?php echo e(route('manage.intrest.intrestdelete',$p->id)); ?>">Delete</a>
                                </td>
                               </tr>
                               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
					</table>
					<div>
						<!-- table end -->
					</div>
					<!-- /card body -->
				</div>
				<!-- /card -->
			</div>
		</div>
	
	
<?php /**PATH /home/creden/api-kaku.jurysoft.in/resources/views/user/intrest/list.blade.php ENDPATH**/ ?>