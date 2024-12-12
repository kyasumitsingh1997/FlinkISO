<div id="evidences_ajax">
	<?php echo $this->Session->flash();?>	
	<div class="nav panel panel-default">
		<div class="evidences form col-md-8">
			<h4><?php echo __('Edit Evidence'); ?>		
				<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
			</h4>
			<?php echo $this->Form->create('Evidence',array('role'=>'form','class'=>'form', 'type' => 'file')); ?>
			<div class="row">
	        	<div class="col-md-12">
	        		<h4><?php echo __('New files for approvals '); ?></h4>
	            	<?php	            		
						$folder_name = str_replace(' ','',$keys[$this->request->data['Evidence']['model_name']]);
						$folder_path = $foler_path;
						$folder = new Folder($folder_path);
						$all_files = $folder->find();
						if($all_files){
							echo "<ul class='list-group'>";
							foreach($all_files as $file){						
								$file_path = $folder_path . DS . $file;
								echo "<li class='list-group-item'><h5>" . 
								$this->Html->link($file, array(
						            'controller' => 'file_uploads',
						            'action' => 'view_document_file',
						            'file_name' => $file,
						            'full_base' => base64_encode(str_replace(Configure::read('MediaPath').'files'. DS . $this->Session->read('User.company_id'). DS ,'',$file_path)),
						        ),array('target'=>'_blank','escape'=>TRUE)).
								" <small> Last Updated on ". date('Y-m-d h:i:s',filemtime($file_path)) ."</small></h5></li>";
								}
							echo "</ul>";						
						}
	            	?>
					</div>
					<div class="col-md-12">
	            		<h4><?php echo __('Add new file for approval'); ?></h4>            
	            			<?php echo $this->Form->file('document', array('class'=>'btn btn-lg btn-default')); ?>
	        		</div>
	        		<?php
	        			foreach($special as $key => $value){
	        				$models[]= $key;	
	        			}
	        			echo "<div class='col-md-6'>". $this->Form->input('model_name_show',array(
							'options'=>$models,'default'=>$this->request->data['Evidence']['model_name'],'label'=>'Model Name','disabled'));
						
						echo $this->Form->hidden('model_name',array('options'=>$models)) . '</div>'; 
						
						echo "<div class='col-md-6'>". $this->Form->input('record_show',array(
							'options'=>$records,'default'=>$this->request->data['Evidence']['record'],'label'=>'Record','disabled'));
						
						echo $this->Form->hidden('record',array('value'=>$this->request->data['Evidence']['record'])) . '</div>';	
						
						if($this->request->data['Evidence']['record_type'] >= 0){
							echo "<div class='col-md-6'>". $this->Form->input('record_type_show',array(
							'options'=>$record_types,'default'=>($this->request->data['Evidence']['record_type']),'label'=>'Record Type','disabled')) . 
							
							$this->Form->hidden('record_type',array('value'=>$this->request->data['Evidence']['record_type'])) . '</div>'; 							
						}
						echo "<div class='col-md-12'>".$this->Form->input('description',array()) . '</div>'; 

	        			echo $this->Form->input('id');
						echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
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
					}
				?>
					<?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id')); ?>
					<?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
					<?php echo $this->Form->end(); ?>
					<?php echo $this->Js->writeBuffer();?>
				</div>
				<?php if($existing_files){ ?>
					<div class="col-md-12">
						<h3>Existing Files</h3>
							<table class="table table-striped table-hover table-bordered ">
	    						<tr>
	    							<th>File Name</th>
	    							<th>Version</th>
	    							<th>Comment</th>
	    							<th>By</th>
	        						<th>Prepared By</th>
	        						<th>Approved By</th>
	        						<th>Created</th>
	    						</tr>
	    						<?php foreach($existing_files as $file):
	    							if($file['FileUpload']['file_status'] == 0)echo "<tr class='danger text-danger src_".str_replace(' ','_', Inflector::Humanize($this->request->params['pass'][0]))."'>";
	    								else echo "<tr class='src_".str_replace(' ','_', Inflector::Humanize($this->request->params['pass'][0]))."'>";
	    									$webroot = "/ajax_multi_upload";
	            						$fullPath = Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id'). DS . $file['FileUpload']['file_dir'];
	    								$displayPath = base64_encode(str_replace(DS , '/', $file['FileUpload']['id']));
	    								$baseEncFile = base64_encode($fullPath);
	    								$delUrl = "$webroot/file_uploads/delete/".$file['FileUpload']['id'];
	    							?>
	           						<td><?php echo $this->Html->image('../ajax_multi_upload/img/fileicons/'.$file['FileUpload']['file_type'].'.png'); ?> 
	            					<?php 
	    								if($file['FileUpload']['file_status'] == 1)echo $this->Html->link($file['FileUpload']['file_details'].'.'.$file['FileUpload']['file_type'], array(
								            'controller' => 'file_uploads',
								            'action' => 'view_media_file',
								            'file_name' => $file,
								            'full_base' => $displayPath
								        ),array('target'=>'_blank','escape'=>TRUE)); 
					    				else echo "<s>".$file['FileUpload']['file_details'].'.'.$file['FileUpload']['file_type']."</s>";		
	    							?>
	           						</td>              
	           						<td><?php echo $file['FileUpload']['version']; ?></td>
	           						<td><?php echo $file['FileUpload']['comment']; ?></td> 
	           						<td><?php echo $file['CreatedBy']['name']; ?></td>  
	    							<td><?php echo $file['PreparedBy']['name']; ?></td>  
	    							<td><?php echo $file['ApprovedBy']['name']; ?></td>          
	           						<td>
	            						<?php
	                						if($file['FileUpload']['file_status'] == 0)echo "Deleted ". $this->Time->niceShort($file['FileUpload']['created']);
	                						else echo $this->Time->niceShort($file['FileUpload']['modified']);
	            						?>
	            					</td>                                       
	        					</tr>
	    					<?php endforeach; ?>
	    				</table>
	    			</div>
				<?php } ?>
			</div>
<script> $("[name*='date']").datepicker({
	changeMonth: true,
	changeYear: true,
	format: 'yyyy-mm-dd',
      autoclose:true,
	}); 
</script>
	<div class="col-md-4">
		<p><?php echo $this->element('helps'); ?></p>
	</div>
	</div>
	<?php echo $this->Js->get('#list');?>
	<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#evidences_ajax')));?>
	<?php echo $this->Js->writeBuffer();?>
</div>
<script>
	$.validator.setDefaults();
	$().ready(function() {
		$('#EvidenceEditForm').validate();
		$("#submit-indicator").hide();
		$("#submit_id").click(function(){
		if($('#EvidenceEditForm').valid()){
			$("#submit_id").prop("disabled",true);
			$("#submit-indicator").show();
			$('#EvidenceEditForm').submit();
		}});
	});
</script>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
