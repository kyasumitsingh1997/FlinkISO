<?php 
	// echo $this->Html->script(array('jquery.min','bootstrap.min')); 
	// echo $this->Html->css(array('bootstrap.min'));
	// echo $this->fetch('script'); 
	// echo $this->fetch('css'); 
?>
<style>
.modal-dialog {
	width: 80%
}
</style>
<div class="modal fade text-default" id="task_approval_model_window<?php echo $record_id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">View / Upload Files</h4>
			</div>
			<div class="modal-body">
				<table class="table table-striped table-hover table-bordered table-responsive ">
					<tr>
						<th>File Name</th>
						<th>Version</th>
						<th>Comment</th>
						<th>By</th>
						<th>Prepared By</th>
						<th>Approved By</th>
						<th>Created</th>
						<th>Edit</th>
					</tr>
					<?php foreach($task_approval_files as $file):
			            if($file['FileUpload']['file_status'] == 0)echo "<tr class='danger text-danger'>";
						else echo "<tr>";
							$webroot = "/ajax_multi_upload";
							$fullPath = Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id'). DS . $file['FileUpload']['file_dir'];
							//$displayPath = '../files/'. $this->Session->read('User.company_id').'/'. str_replace(DS , '/', $file['FileUpload']['file_dir']);
						        $displayPath = base64_encode(str_replace(DS , '/', $file['FileUpload']['id']));
							$baseEncFile = base64_encode($fullPath);
							$delUrl = "$webroot/file_uploads/delete/".$file['FileUpload']['id'];
						?>
								
							<td><?php echo $this->Html->image('../ajax_multi_upload/img/fileicons/'.$file['FileUpload']['file_type'].'.png'); ?>
								<?php 
										if($file['FileUpload']['file_status'] == 1)echo $this->Html->link($file['FileUpload']['file_details'].'.'.$file['FileUpload']['file_type'],array(
			        						'controller' => 'file_uploads',
			        						'action' => 'view_media_file',
			        						'full_base' => $displayPath
			    						),array('target'=>'_blank','escape'=>TRUE)); 
										
										else echo "<s>".$file['FileUpload']['file_details'].'.'.$file['FileUpload']['file_type']."</s>";		
							?></td>
							<td><?php echo $file['FileUpload']['version']; ?></td>
							<td><?php echo $file['FileUpload']['comment']; ?></td>
							<td><?php echo $file['CreatedBy']['name']; ?></td>
							<td><?php echo $file['PreparedBy']['name']; ?></td>
							<td><?php echo $file['ApprovedBy']['name']; ?></td>
							<td><?php
			            		if($file['FileUpload']['file_status'] == 0)echo "Deleted ". $this->Time->niceShort($file['FileUpload']['created']);
			            		else echo $this->Time->niceShort($file['FileUpload']['modified']);
			        		?></td>
							<td width="">
			            		<div id="share_div_<?php echo $file['FileUpload']['id'];?>"></div>
			            			<script type="text/javascript">
			                    		$("document").ready(function() {
			                        		$("#share_<?php echo $file['FileUpload']['id'];?>").on('click',function(){
			                            		$("#share_div_<?php echo $file['FileUpload']['id'];?>").load("<?php echo Router::url('/', true); ?>file_uploads/share/<?php echo $file['FileUpload']['id'];?>"); });                                      
			              				});
			            			</script>
			            			<div class="btn-group dropdown">
			              				<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			                    			Actions <span class="caret"></span>
			                			</button>
			              				<ul class="dropdown-menu pull-right">
						                <?php 
						                    if($file['FileUpload']['file_status'] == 1){
						                        echo '<li>'.$this->Html->link('Edit Files',array('controller'=>'file_uploads','action'=>'edit',$file['FileUpload']['id'],$file['FileUpload']['system_table_id'],$this->request->params['pass'][0]),array('class'=>'')).'</li>'; 
						                        echo '<li>'.$this->Html->link('Delete Files',array('controller'=>'file_uploads','action'=>'delete_file',$file['FileUpload']['id']),array('class'=>'', 'escape'=>FALSE)).'</li>';
						                        echo '<li>'.$this->Html->link('Add Change Request',array('controller'=>'change_addition_deletion_requests','action'=>'lists',$file['FileUpload']['id'],'document'),array('class'=>'', 'escape'=>FALSE)).'</li>';
						                        echo '<li>'.$this->Html->link('Access Permissions','#upload_table',array('class'=>'', 'escape'=>FALSE, 'id'=>'share_'.$file['FileUpload']['id'])).'</li>';
						                    
						                    }elseif($file['FileUpload']['file_status'] == 2) {
						                        echo '<li>'.$this->Html->link('View',array('controller'=>'file_uploads','action'=>'view',$file['FileUpload']['id'],$file['FileUpload']['system_table_id'],$this->request->params['pass'][0]),array('class'=>'')).'</li>';                       
						                    }
						                	?>
			            				</ul>
			            			</div>
			            	</td>  
						</tr>
					<?php endforeach; ?>
				</table>
				<?php 
 				echo $this->Form->create('Upload', array('role' => 'form', 'class' => 'form')); ?>
				<?php
					echo $this->Upload->edit('upload', $this->Session->read('User.id') . '/task_statuses/' . $record_id,false);
					echo $this->Form->end();
				?>
				<p><strong>Note:</strong>Files uploaded here will be shared with all the users in the approval process for this record.</p>
			</div>
			<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<?php
	echo $this->Html->script(array(
		'plugins/jQuery/jQuery-2.2.0.min',
    	'plugins/jQueryUI/jquery-ui.min',
    	'js/bootstrap.min',
    	'dist/js/demo',
    	'dist/js/app.min',
    	));    
	// echo $this->fetch('script');
    // echo $this->Html->css(array('flinkiso'));
    // echo $this->fetch('css');
?>
<script>
$(document).ready(function(){
    
	$.ajaxSetup({cache:false});
	$('#task_approval_model_window<?php echo $record_id; ?>').modal();
	});


</script>
