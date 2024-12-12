<?php echo $this->Session->flash();?>
<?php

// Configure::write('debug',1);
// debug($this->request->params['named']);

// echo $this->request->params['named']['project_id'].'<br />';
// echo $this->request->params['named']['current_project_id'];
// echo $this->Html->script(array(
//     // 'js/bootstrap.min',
//     // 'js/npm',
//     // 'plugins/jQuery/jQuery-2.2.0.min',
//     // 'plugins/jQueryUI/jquery-ui.min',
//     'jquery-form.min',
//     'jquery.validate.min',
//     // 'js/bootstrap.min',
//     // 'validation',
//     // 'chosen.min',
//     // 'tooltip.min',
//     // 'plugins/daterangepicker/moment.min',
//     // 'jquery.datepicker',    
//     // 'plugins/daterangepicker/daterangepicker',
//     // 'plugins/datepicker/bootstrap-datepicker',
// ));
// echo $this->fetch('script');
?>
<div id="allMembers<?php echo $this->request->params['named']['project_id']?>">
<script type="text/javascript">

$(document).ready(function(){
  $("#meminput_<?php echo $this->request->params['named']['project_id']?>").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#memtable_<?php echo $this->request->params['named']['project_id']?> tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});



	function checkallboxes(t){
		// $().ready(function(){			
			// $('#select_all_<?php echo $this->request->params['named']['current_project_id']?>').on('click', function() { 
				// alert('aaaa');
			    if (!t.checked) {
			    	$('.checkselect<?php echo $pop?>').find(':checkbox').each(function(){
						$(this).prop('checked', false);
					});
			    }else{
			    	$('.checkselect<?php echo $pop?>').find(':checkbox').each(function(){
						$(this).prop('checked', true);
					});
			    }
			// });
		// });
		}
	</script>	

<input type="text" id="meminput_<?php echo $this->request->params['named']['project_id']?>" name="meminput" class="form-control" placeholder="Type name to search"><br />

<?php echo $this->Form->create('ProjectEmployee',array(
                    'controller'=>'project_employees','action'=>'add_members',
                    'id'=>'ProjectEmployee-'. $this->request->params['named']['project_id'], 
                    'default'=>false
                  ),array('role'=>'form','class'=>'form','default'=>false)
          ); ?>	
<table class="table table-responsive table-condensed table-bordered" id="memtable_<?php echo $this->request->params['named']['project_id']?>" >
      <tr>
        <thead>
      	<th><?php echo $this->Form->input('select_all'.$this->request->params['named']['project_id'],array(
      		'onClick'=>'checkallboxes(this);',
      	 'id'=>'select_all_'.$this->request->params['named']['project_id'], 'style'=>'margin-top:-6px','multiple', 'type' =>'checkbox','label'=>false));

      	echo $this->Form->hidden('project_id',array('default'=>$this->request->params['named']['project_id']));
      	echo $this->Form->hidden('current_project_id',array('default'=>$this->request->params['named']['current_project_id']));
      	echo $this->Form->hidden('milestone_id',array('default'=>$this->request->params['named']['milestone_id']));
      	 ?></th>
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
      </thead>
      <?php 
      $m = 1;
      // Configure::write('debug',1);
      // debug($allMembers);
      $i = 0;
      foreach ($allMembers as $employee) { 
        if($employee['Employee']['curr_project'])$procheckclass  = 'warning';
        else $procheckclass  = '';
        // echo ">> " . $employee['Employee']['tl'];
        // echo ">> " . $employee['Employee']['pm'];
        ?>
        <tbody>
        <tr id="remove_<?php echo $employee['Employee']['id']?><?php echo $m?>" class="<?php echo $procheckclass?>">
        	<td width="50px" class="checkselect<?php echo $pop?>">
        		<?php echo $this->Form->input('emp_id',array(
	        			'name'=>'data[ProjectEmployee][emp_id]['.$m.']', 
                'id'=>'ProjectEmployeeEmpId'.$m,
	        			'style'=>'margin-top:-6px',  
	        			'value'=>$employee['Employee']['id'],  
	        			'type' =>'checkbox',
	        			'label'=>false
        			)
        		);?>
        	</td>
        	<td><?php echo $m;?></td>
            <td>
            	<?php
                    echo $this->Html->link($employee['Employee']['name'],"javascript:void(0);",
                        array(
                          // 'class'=>'btn btn-xs btn-default',
                          'onclick'=>'openmodel(
                            "employees",
                            "view",
                            "'.$employee['Employee']['id'].'",
                            null,
                            null,
                            null,
                            null
                          )'
                        ));                                     
                  ?>
            	
            		


            	</td>
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
                <?php if($employee['Employee']['curr_project']) { 
                	if($employee['Employee']['release_request'] > 0){ ?>
                		<span class="btn btn-xs btn-info">Request Sent</span>
                	<?php }else{ ?>
                    <?php if($this->request->params['named']['project_id'] != $this->request->params['named']['current_project_id']){ ?>
                      <a href="javascript:void(0)" class="btn btn-xs btn-warning" id="<?php echo $employee['Employee']['id'];?><?php echo $pop?>_btn_release">Request Release</a> 
                    <?php }else{ 
                      // Configure::write('debug',1);
                      // debug($employee);
                      ?>
                      <a href="javascript:void(0)" onClick = "releaseemp('<?php echo $employee['Employee']['pro_emp_id']?>','<?php echo $employee['Employee']['id']?>','<?php echo $m?>')" class="btn btn-xs btn-warning">Remove</a> 
                      <!-- <a href="javascript:void(0)" class="btn btn-xs btn-default" id="<?php echo $employee['Employee']['id'];?><?php echo $pop?>_btn_remove">Remove User</a>  -->
                    <?php } ?>
                		
                	<?php } ?>
                
                  
                <?php }else{ ?>
                  <a href="javascript:void(0)" class="btn btn-xs btn-success" id="<?php echo $employee['Employee']['id'];?><?php echo $pop?>_btn">Add</a>                              
                <?php } ?>
                  
                  
              
              </div>
            </td>
          </tr>
          </tbody>
          <script type="text/javascript">
            $().ready(function(){
              $("#<?php echo $employee['Employee']['id'];?><?php echo $pop?>_btn").on('click',function(){
                // alert('a');
                  $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_employee_to_project/project_id:<?php echo $current_project_id;?>/employee_id:<?php echo $employee['Employee']['id']?>/milestone_id:<?php echo $milestone['Milestone']['id']?>" , function(data,response) {
                            if(data == 'true'){
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
                  $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/send_release_request/current_project_id:<?php echo $employee["Employee"]["curr_project"];?>/new_project_id:<?php echo $current_project_id;?>/employee_id:<?php echo $employee['Employee']['id']?>/request_from_id:<?php echo $this->Session->read('User.employee_id')?>/project_employee_id:<?php echo $employee['Employee']['pro_emp_id']?>" , function(data) {
                  //           console.log(data);
                            $("#<?php echo $employee['Employee']['id'];?><?php echo $pop?>_btn_release").html('Request Sent');
                            $("#<?php echo $employee['Employee']['id']?><?php echo $pop?>_btn_release").addClass('success');
                            return false;
                      });
              });
            });


          </script>
      <?php  $i++; $m++;} ?>
      <tr>
      	<td colspan="11">
      		<?php echo $this->Form->submit('Submit',array('onClick'=>'subfunc()', 'id'=>'memSubmit_'.$this->request->params['named']['project_id'], 'div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#allMembers'.$this->request->params['named']['project_id'],'async' => 'false')); ?>
			<?php echo $this->Form->end(); ?>
			<?php echo $this->Js->writeBuffer();?>
      	</td>
      </tr>
</table>
<script type="text/javascript">
		function subfunc(){
			// alert('asas');
		
            $('#ProjectEmployee-<?php echo $this->request->params['named']['project_id']?>').ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?>project_employees/add_members",
                type: 'POST',
                target: '#allMembers<?php echo $this->request->params['named']['project_id']?>',
                beforeSend: function(){
                   $("#memSubmit").prop("disabled",true);
                    // $("#submit-indicator").show();
                },
                complete: function() {
                   $("#memSubmit").removeAttr("disabled");
                   // $("#submit-indicator").hide();
                },
                error: function(request, status, error) {                    
                    alert('Action failed!');
                }
	    });
      };      
        
</script>
</div>