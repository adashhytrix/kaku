<div class="row">
	<div class="col-xl-12">
		<!-- card -->
		<div class="card mb-4">
			<!-- card body -->
			<div class="card-body">
				<ul class="nav nav-tabs">
					

					
				
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
									<?= __tr('Username') ?>
								</th>
                                <th class="lw-dt-nosort text-white">
									<?= __tr('Email') ?>
								</th>
                                <th class="lw-dt-nosort text-white">
									<?= __tr('Impressed') ?>
								</th><th class="lw-dt-nosort text-white">
									<?= __tr('Comment') ?>
								</th>
                                
							</tr>
						</thead>
						<!-- /table headings -->
						<tbody class="lw-datatable-photoswipe-gallery">
                            <?php $__currentLoopData = $postfeedback; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=> $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                
                           
                            <tr>
                                <td class="text-white"><?php echo e($k+1); ?></td>
                                <td class="text-white"><?php echo e($p->user_name); ?></td>
                                <td class="text-white"><?php echo e($p->email); ?></td>

                                <td class="text-white"><?php echo e(str_replace('"', '', $p->impressed)); ?></td>
                                <td class="text-white"><?php echo e(str_replace('"', '', $p->comment)); ?></td>


                                
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
	
	
<?php /**PATH /home/creden/api-kaku.jurysoft.in/resources/views/user/feedback/list.blade.php ENDPATH**/ ?>