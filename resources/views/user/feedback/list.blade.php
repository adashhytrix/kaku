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
                                {{-- <th class="lw-dt-nosort text-white">
									<?= __tr('Action') ?>
								</th> --}}
							</tr>
						</thead>
						<!-- /table headings -->
						<tbody class="lw-datatable-photoswipe-gallery">
                            @foreach ($postfeedback as $k=> $p)
                                
                           
                            <tr>
                                <td class="text-white">{{$k+1}}</td>
                                <td class="text-white">{{$p->user_name}}</td>
                                <td class="text-white">{{$p->email}}</td>

                                <td class="text-white">{{ str_replace('"', '', $p->impressed) }}</td>
                                <td class="text-white">{{ str_replace('"', '', $p->comment) }}</td>


                                {{-- <td class="text-white">
                                    <a class="btn btn-danger " href="{{route('manage.intrest.intrestdelete',$p->id)}}">Delete</a>
                                </td> --}}
                               </tr>
                               @endforeach
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
	
	
