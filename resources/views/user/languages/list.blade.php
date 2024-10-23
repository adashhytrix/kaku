<div class="row">
	<div class="col-xl-12">
		<!-- card -->
		<div class="card mb-4">
			<!-- card body -->
			<div class="card-body">
				<ul class="nav nav-tabs">
					

					
					<li class="nav-item">
						<a  class="nav-link lw-ajax-link-action lw-action-with-url nav-link "
							href="{{route('manage.intrest.intrestadd')}}">
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
                                <th class="lw-dt-nosort">
									<?= __tr('SL#') ?>
								</th>
								<th class="lw-dt-nosort">
									<?= __tr('Name') ?>
								</th>
                                <th class="lw-dt-nosort">
									<?= __tr('Action') ?>
								</th>
							</tr>
						</thead>
						<!-- /table headings -->
						<tbody class="lw-datatable-photoswipe-gallery">
                           {{-- @foreach ($intrest as $k=> $p)
                                
                           
                            <tr>
                                <td>{{$k+1}}</td>
                                <td>{{$p->name}}</td>
                                <td>
                                    <a class="btn btn-danger " href="{{route('manage.intrest.intrestdelete',$p->id)}}">Delete</a>
                                </td>
                               </tr>
                               @endforeach  --}}
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
	
	
