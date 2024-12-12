<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<div id="projectQueryResponses_ajax">
<?php echo $this->Session->flash();?>	

	<div class="row">
		<div class="col-md-12">
			<div class="box box-warning  resizable">
	            <div class="box-header with-border"><h4>Query Details</h4>
	                <div class="btn-group box-tools pull-right">
	                    <button type="button" class="btn btn-xs btn-default" data-widget="collapse"><i class="fa fa-plus"></i></button>
	                </div>
	            </div>
	            <div class="box-body" style="padding: 0px">
			        <table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
						<tr>
						<th><?php echo __('name'); ?></th>
						<th><?php echo __('Type'); ?></th>
						<th><?php echo __('From'); ?></th>
						<th><?php echo __('To'); ?></th>
						<th><?php echo __('query'); ?></th>
						<th><?php echo __('Current Status'); ?></th>
						
					</tr>
						<?php if($projectQuery){ ?>
							
								<tr>
									<td><?php echo h($projectQuery['ProjectQuery']['name']); ?>&nbsp;</td>
									<td><?php echo $projectQuery['QueryType']['name'] ?></td>
									<td><?php echo $projectQuery['Employee']['name'] ?></td>
									<td><?php echo h($PublishedEmployeeList[$projectQuery['ProjectQuery']['sent_to']]); ?>&nbsp;</td>
									<td><?php echo h($projectQuery['ProjectQuery']['query']); ?>&nbsp;</td>
									<td><?php echo h($projectQuery['ProjectQuery']['current_status']); ?>&nbsp;</td>
									
								</tr>
								<tr>
									<td colspan="6">
										<h3>Files</h3>
			<?php 
		$folder_path = WWW_ROOT.'img'. DS . 'files' . DS . $this->Session->read('User.company_id'). DS . 'qurery_file' . DS . $projectQuery['ProjectQuery']['id'];
		// echo $folder_path;                    
		                    // $file_folder_path = 'InternalAudit' . DS .  $this->request->data['InternalAudit']['id'];
		                    // Configure::write('debug',1);
		                    $dir = new Folder($folder_path);
		                    $all_files = $dir->find();
		                    
		                    if($all_files){
		                    echo "<div class='row'>";
		                    foreach($all_files as $file){                       
		                    	$ffile = New File($file);
		                    	// debug($ffile);
		                    	$ff =$ffile->info();
		                    	$file_path = $file_folder_path . DS . $file;
		                    	if($ff['extension'] == 'jpg' || $ff['extension'] == 'jpeg' || $ff['extension'] == 'png' || $ff['extension'] == 'gif'){		                    		
		                    		echo "<div class='col-md-4'>" . $this->Html->image('files' . DS . $this->Session->read('User.company_id'). DS . 'qurery_file' . DS . $projectQuery['ProjectQuery']['id'] . DS .$file,array('fullBase' => true,'class'=>'img-responsive img-rounded')) . "</div>";
		                    	}else{

		                    	}

		                    	
							}
							echo "</div>";
							echo "<h4>Download Files</h4>";
							echo "<ul class='list-group'>";
		                    foreach($all_files as $file){                       
		                    	$ffile = New File($file);
		                    	$ff =$ffile->info();
		                    	$file_path = $file_folder_path . DS . $file;
		                    	if($ff['extension'] == 'jpg' || $ff['extension'] == 'jpeg' || $ff['extension'] == 'png' || $ff['extension'] == 'gif'){

		                    	}else{
		                    		echo "<li class='list-group-item'><h5>" . 
				                        $this->Html->link($file, array(
				                            'controller' => 'file_uploads',
				                            'action' => 'view_document_file',
				                            'file_name' => $file,
				                            'full_base' => base64_encode(str_replace(Configure::read('MediaPath').'files'. DS . $this->Session->read('User.company_id'). DS ,'',$file_path)),
				                        ),array('target'=>'_blank','escape'=>TRUE)).
				                        "</h5></li>";
		                    	}
							}
		                    echo "</ul>";
		                }
		                ?>
									</td>
								</tr>
							<?php }else{ ?>
								<tr><td colspan="7">No results found</td></tr>
							<?php } ?>

					</table>

					<h3>Responses</h3>
					<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
						<tr>
							<th><?php echo __('Name'); ?></th>
							<th><?php echo __('Level'); ?></th>
							<th><?php echo __('By'); ?></th>
							<th><?php echo __('To'); ?></th>
							<th><?php echo __('Response'); ?></th>
							<th><?php echo __('Sent To Client'); ?></th>
							<th><?php echo __('Client Response'); ?></th>							
						</tr>
						<?php if($projectQueryResponses){ ?>
							<?php foreach ($projectQueryResponses as $projectQueryResponse): ?>
								<tr>
									<td><?php echo h($projectQueryResponse['ProjectQueryResponse']['name']); ?>&nbsp;</td>
									<td><?php echo h($projectQueryResponse['ProjectQueryResponse']['level']); ?>&nbsp;</td>
									<td><?php echo h($PublishedEmployeeList[$projectQueryResponse['ProjectQueryResponse']['raised_by']]); ?>&nbsp;</td>
									<td><?php echo $projectQueryResponse['Employee']['name']; ?></td>
									<td><?php echo h($projectQueryResponse['ProjectQueryResponse']['response']); ?>&nbsp;</td>
									<td><?php echo h($projectQueryResponse['ProjectQueryResponse']['sent_to_client']); ?>&nbsp;</td>
									<td><?php echo h($projectQueryResponse['ProjectQueryResponse']['client_response']); ?>&nbsp;</td>
									
								</tr>
							<?php endforeach; ?>
							<?php }else{ ?>
								<tr><td colspan="7">No results found</td></tr>
							<?php } ?>
					</table>
		        </div>
		    </div>
		</div>
	</div>


	<div class="nav">
		<div class="projectQueryResponses form col-md-12">
			<h4>Add Project Query Response</h4>
			<?php echo $this->Form->create('ProjectQueryResponse',array('type'=>'file','role'=>'form','class'=>'form','default'=>false)); ?>
			<div class="row">
			<fieldset>
					<?php
					echo "<div class='col-md-6'>".$this->Form->input('name',array('label'=>'Response Title')) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->hidden('project_query_id',array('default'=>$projectQuery['ProjectQuery']['id'])) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('level',array('default'=>0, 'options'=>array(1,2,3))) . '</div></div>'; 
					echo "<div class='row'><div class='col-md-6'>".$this->Form->input('raised_by',array('default'=>$this->Session->read('User.employee_id'), 'options'=>$PublishedEmployeeList)) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('employee_id',array('label'=>'Send To', 'options'=>$PublishedEmployeeList)) . '</div>'; 
					echo "<div class='col-md-12'>".$this->Form->input('response',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('sent_to_client',array('type'=>'checkbox')) . '</div>'; 
					echo "<div class='col-md-12'>".$this->Form->input('client_response',array()) . '</div>'; 

					echo "<div class='col-md-12 pull-left'><p><br /><br />" . $this->Form->file('Files.error_file_1') ,"</p></div>";
					echo "<div class='col-md-12 pull-left'><p>" . $this->Form->file('Files.error_file_2') ,"</p></div>";
					echo "<div class='col-md-12 pull-left'><p>" . $this->Form->file('Files.error_file_3') ,"</p></div>";
					?>
			</fieldset>
			<?php
			    echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
			    echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
			    echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
			?>
		</div>
		<div class="">
<?php

		if ($showApprovals && $showApprovals['show_panel'] == true) {
			echo $this->element('approval_form');
		} else {
			echo $this->Form->input('publish', array('label' => __('Publish')));
		}?>
<?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#projectQueryResponses_ajax','async' => 'false')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer();?>
		</div>
	</div>
<script> 
	$("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat:'yy-mm-dd',
    }); 
</script>
<div class="col-md-12 hide">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<script>
    $.validator.setDefaults({
    	ignore: null,
    	errorPlacement: function(error, element) {
            if (
                
								$(element).attr('name') == 'data[ProjectQueryResponse][project_query_id]' ||
								$(element).attr('name') == 'data[ProjectQueryResponse][employee_id]')
						{	
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax",
                type: 'POST',
                target: '#main',
                beforeSend: function(){
                   $("#submit_id").prop("disabled",true);
                    $("#submit-indicator").show();
                },
                complete: function() {
                   $("#submit_id").removeAttr("disabled");
                   $("#submit-indicator").hide();
                },
                error: function(request, status, error) {                    
                    alert('Action failed!');
                }
	    			});
        }
    });
		$().ready(function() {
    	$("#submit-indicator").hide();
        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");
        
        $('#ProjectQueryResponseAddAjaxForm').validate({
            rules: {
									"data[ProjectQueryResponse][project_query_id]": {
                    	greaterThanZero: true,
									},
									"data[ProjectQueryResponse][employee_id]": {
                    	greaterThanZero: true,
									},
                
            }
        }); 

				$('#ProjectQueryResponseProjectQueryId').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
						$(this).next().next('label').remove();
					}
				});
				$('#ProjectQueryResponseEmployeeId').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
						$(this).next().next('label').remove();
					}
				});       
    });
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
