<div class="row">
	<div class="col-md-12">
		<div class="box box-primary ">
	            <div class="box-header with-border"><h4>All Member's board</h4>
	                <div class="btn-group box-tools pull-right">
	                    <button type="button" class="btn btn-xs btn-default" data-widget="collapse"><i class="fa fa-plus"></i></button>
	                </div>
	            </div>
	            <div class="box-body" style="padding: 0px">
	              <?php // print_r($PublishedEmployeeList);?>
	                <table class="table table-responsive table-condensed table-bordered draggable">
	                  <tr>
	                  	<th>#</th>
	                    <th>Member</th>
	                    <th>Department</th>
	                    <th>Designation</th>                  
	                    <th>Project</th>
	                    <th>TL</th>
	                    <th>PL</th>
	                    <th>Locked From</th>
	                    <th>Locked Till</th>
	                    <th></th>
	                  </tr>
	                  <?php 
	                  $m = 0;
	                  // Configure::write('debug',1);
	                  // debug($allMembers);
	                  foreach ($allMembers as $employee) { 
	                    if($employee['Employee']['curr_project'])$procheckclass  = 'warning';
	                    else $procheckclass  = '';
	                    // echo ">> " . $employee['Employee']['tl'];
	                    // echo ">> " . $employee['Employee']['pm'];
	                    ?>
	                    <tr id="<?php echo $employee['Employee']['id']?><?php echo $pop?>" class="<?php echo $procheckclass?>">
	                    	<td><?php echo $m; $m++;?></td>
	                        <td><?php echo $employee['Employee']['name'];?></td>
	                        <td><?php echo $employee['Department']['name'];?></td>
	                        <td><?php echo $employee['Designation']['name'];?></td>
	                        <td>
	                        	<?php 
	                        	if($employee['Employee']['curr_project']){
	                        		echo $allProjects[$employee['Employee']['curr_project']];	
	                        	}elseif($employee['Employee']['pro_res']){
	                        		echo $allProjects[$employee['Employee']['pro_res']];	
	                        	}
	                        	?>
	                        	<!-- <?php echo $employee['Employee']['curr_project']?></td> -->
	                        <?php if($employee['Employee']['curr_project'] && ($employee['Employee']['tl'] || $employee['Employee']['pm'])){ ?>
	                        	<td><?php echo $PublishedEmployeeList[$employee['Employee']['tl']];?></td>
	                        	<td><?php echo $PublishedEmployeeList[$employee['Employee']['pm']];?></td>
	                        <?php }elseif($employee['Employee']['curr_project'] && ($employee['Employee']['tl'] = -1 || $employee['Employee']['pm'] = -1)){ ?>
	                        	<td colspan="2" class="success">Task Not Assigned</td>
	                        <?php }else{ ?> 
	                        	<td></td>
	                        	<td></td>
	                        <?php } ?>
	                        
	                        <td><?php echo $employee['Employee']['locked_from'];?></td>
	                        <td><?php echo $employee['Employee']['locked_till'];?></td>
	                        <td>
	                          <div class="btn-group">
	                            <?php if($employee['Employee']['curr_project']) { ?>
	                              <a href="javascript:void(0)" class="btn btn-xs btn-warning" id="<?php echo $employee['Employee']['id'];?><?php echo $pop?>_btn_release">Request Release</a> 
	                            <?php }else{ ?>
	                              <!-- <a href="javascript:void(0)" class="btn btn-xs btn-success" id="<?php echo $employee['Employee']['id'];?><?php echo $pop?>_btn">Add</a>                               -->
	                            <?php } ?>
	                              
	                              
	                          
	                          </div>
	                        </td>
	                      </tr>
	                      <script type="text/javascript">
	                        $().ready(function(){
	                          $("#<?php echo $employee['Employee']['id'];?><?php echo $pop?>_btn").on('click',function(){
	                            // alert('a');
	                              $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_employee_to_project/project_id:<?php echo $project['Project']['id'];?>/employee_id:<?php echo $employee['Employee']['id']?>/milestone_id:<?php echo $milestone['Milestone']['id']?>" , function(data,response) {
	                                        if(data == true){
	                                        	$("#<?php echo $employee['Employee']['id'];?><?php echo $pop?>_btn").html('Added');
		                                        $("#<?php echo $employee['Employee']['id']?><?php echo $pop?>").addClass('success');
		                                        return false;	
	                                        }else{
	                                        	$("#<?php echo $employee['Employee']['id'];?><?php echo $pop?>_btn").html('Failed To Added');
		                                        $("#<?php echo $employee['Employee']['id']?><?php echo $pop?>").addClass('danger');
	                                        }
	                                        
	                                  });
	                          });

	                          $("#<?php echo $employee['Employee']['id'];?><?php echo $pop?>_btn_release").on('click',function(){
	                            // alert('a');
	                              $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/send_release_request/current_project_id:<?php echo $employee["Employee"]["curr_project"];?>/new_project_id:<?php echo $project['Project']['id']?>/employee_id:<?php echo $employee['Employee']['id']?>/request_from_id:<?php echo $this->Session->read('User.employee_id')?>/project_employee_id:<?php echo $employee['Employee']['pro_emp_id']?>" , function(data) {
	                              //           console.log(data);
	                                        $("#<?php echo $employee['Employee']['id'];?><?php echo $pop?>_btn_release").html('Request Sent');
	                                        $("#<?php echo $employee['Employee']['id']?><?php echo $pop?>_btn_release").addClass('success');
	                                        return false;
	                                  });
	                          });
	                        });


	                      </script>
	                  <?php } ?>
				</table>
			</div>
		</div>
	</div>
</div>