<style type="text/css">
	.list-group-item:hover{cursor: move;}
	.addFiles, .removeFiles {min-height: 300px;border: 2px solid #000;}
	.addFiles:hover, .removeFiles:hover {background: #ccc;}
	.ui-state-highlight { height: 4.5em; line-height: 4.2em; list-style: none }
</style>
<?php

if( $evidence['Evidence']['record_type']!=-1 &&  $evidence['Evidence']['record_type']!=NULL){
       // $products = $this->get_model_list('products');     
        $products = $this->requestAction('App/get_model_list/Product/');

}

?>
<div id="evidences_ajax">
	<?php echo $this->Session->flash();?>	
	<div class="nav panel panel-default">
		<div class="evidences form col-md-8">
			<h4><?php echo __('View Evidence'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
				<?php /* echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info'));*/ ?>
				<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
				<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
			</h4>
			<p><br />You can view exiting files under this record, as well as new files that you have added. You can drag & drop any of the new files and link those files with the record.</p>		
			<?php echo $this->Form->create('Evidence',array('action'=>'arrange_files'),array('role'=>'form','class'=>'form')); ?>
			<table class="table table-responsive">
				<tr>
					<td><?php echo __('Model Name'); ?></td>
					<td><?php echo h($model); ?>&nbsp;</td>
				</tr>
				<tr>
					<td><?php echo __('Record'); ?></td>
					<td><?php echo h($record); ?>&nbsp;</td>
					<!--<?php 
						if( $evidence['Evidence']['record_type']!=-1 &&  $evidence['Evidence']['record_type']!=NULL){ ?>
							<td><?php echo $products[$evidence['Evidence']['record']]; ?> (<?php echo $evidence['RecordDetails']['name']; ?>)&nbsp;</td>
						<?php  }else{ ?>						
							<td><?php echo $evidence['RecordDetails']['name']; ?>&nbsp;</td>
					<?php }?> -->
				</tr>	
				<?php if(isset($record_types)){ ?> 
				<tr>
					<td><?php echo __('Record Type'); ?></td>
					<td><?php echo h($record_types[$evidence['Evidence']['record_type']]); ?>&nbsp;</td>
				</tr>

				<?php } ?>
				<tr>
					<td><?php echo __('Description'); ?></td>
					<td><?php echo h($evidence['Evidence']['description']); ?>&nbsp;</td>
				</tr>
				<tr>
					<td><?php echo __('Prepared By'); ?></td>
					<td><?php echo h($evidence['PreparedBy']['name']); ?>&nbsp;</td>
				</tr>
				<tr>
					<td><?php echo __('Approved By'); ?></td>
					<td><?php echo h($evidence['ApprovedBy']['id']); ?>&nbsp;</td>
				</tr>
				<tr>
					<td><?php echo __('Publish'); ?></td>
					<td>
						<?php if($evidence['Evidence']['publish'] == 1) { ?>
						<span class="fa fa-check"></span>
						<?php } else { ?>
						<span class="fa fa-ban"></span>
						<?php } ?>&nbsp;					
					&nbsp;</td>
				</tr>
				<!-- <tr>
					<td><?php echo __('Soft Delete'); ?></td>
					<td>
						<?php if($evidence['Evidence']['soft_delete'] == 1) { ?>
							<span class="fa fa-check"></span>
						<?php } else { ?>
							<span class="fa fa-ban"></span>
						<?php } ?>&nbsp;
					</td>
				</tr> -->
			</table> 
			<div class="row">  
				<div class="col-md-12"><h4>Drag & Drop files <small> "Drag & Drop files form the right panel to left. Those files will be automatically be added to the record.</small></h4><hr /></div>
				<div class="col-md-6" id="">
					<h4><?php echo __('Existing Files'); ?> <small>These files are already available under this record.</small></h4>            
            		<?php
            			$selected_files = NULL;
            			if($existing_files){
							echo "<ul id='addFiles' class='list-group connectedSortable'>";
							foreach($existing_files as $file){
								$selected_files .= $file['FileUpload']['file_details'].".". $file['FileUpload']['file_type'] . ' , '; 						
								echo "<li class='list-group-item  draggable_remove ui-state-default disabled' id='".$file['FileUpload']['file_details'].".". $file['FileUpload']['file_type']."'>";
								echo $file['FileUpload']['file_details']. ".". $file['FileUpload']['file_type'];
								echo "<small>  Last Updated on ". $file['FileUpload']['modified']. "</small></li>";
								}
								echo "</ul>";
						}else{
							echo "<ul id='addFiles' class='list-group connectedSortable'>";
							echo "<li class='list-group-item  draggable_add ui-widget-content' id='empty'><small></small></li>";
							echo "</ul>";
						}
					?>
        		</div>
				<div class="col-md-6" id="">
        			<h4><?php echo __('Link File With Records'); ?> <small>Select any of the following file and add that file to an original record.</small></h4>            
            		<?php            	
						$removed_files = NULL;
						//$folder_name = str_replace(' ','',$keys[$this->request->data['Evidence']['model_name']]);
						$folder = new Folder($folder_path);
						$all_files = $folder->find();
						
						if($all_files){
							echo "<ul id='removeFiles' class='list-group connectedSortable'>";
							foreach($all_files as $file){						
								$file_path = $folder_path . DS . $file;
								echo "<li class='list-group-item  draggable_add ui-widget-content' id='".$file."'>";
								echo $this->Html->link($file, array(
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
				<?php echo $this->Form->input('overwrite',array('type'=>'radio', 'div'=>array('class' => 'input radio no-padding'),	'default' => 1,
					'disabled'=>array(0,2),
					'options'=>array(
						0=>'Over write existing files?',
						1=>'Auto arrange  new files with versioning',
						2=>'Remove all exsiting files and Auro arrange new files')
					));?> 
			</div>
			<div class="col-md-6"></div>
			<div class="col-md-12"><hr /></div>
 		</div>
	<?php 
		echo $this->Form->hidden('selected_files',array());
		echo $this->Form->hidden('removed_files',array('value'=> $removed_files));
		echo $this->Form->hidden('model_name',array( 'type'=>'text', 'value'=>$evidence['Evidence']['model_name']));
		echo $this->Form->hidden('id',array( 'type'=>'text', 'value'=>$evidence['Evidence']['id']));
		echo $this->Form->hidden('record',array('value'=>$evidence['Evidence']['record']));
		 echo $this->Form->hidden('record_type',array('value'=>$evidence['Evidence']['record_type']));
		echo $this->Form->hidden('created_by',array('value'=>$evidence['Evidence']['created_by']));
		echo $this->Form->hidden('prepared_by',array( 'type'=>'text', 'value'=>$evidence['Evidence']['prepared_by']));
		echo $this->Form->hidden('approved_by',array( 'type'=>'text','value'=>$evidence['Evidence']['approved_by']));            
	?>
	<script type="text/javascript">
		$(function() {
			$( "#removeFiles" ).sortable({
    			update: function( event, ui ) {
    			var id = ui.item.attr("id");
    			var removed_files = $('#EvidenceRemovedFiles').val();
    			removed_files = removed_files.replace(id,'');
    			$('#EvidenceRemovedFiles').val(removed_files + ' , ' + id); 
				var selected_files = $('#EvidenceSelectedFiles').val();
    			selected_files = selected_files.replace(id,'');
    			$('#EvidenceSelectedFiles').val(selected_files);
			},
    		items: "li:not(.disabled)",
    		cursor: "move",
    		connectWith: ".connectedSortable",
    		placeholder: "ui-state-highlight",
    		dropOnEmpty: true,
    		}).disableSelection();
			
			$( "#addFiles" ).sortable({
    			update: function( event, ui ) {	
				var id = ui.item.attr("id");
    			var selected_files = $('#EvidenceSelectedFiles').val();
    			selected_files = selected_files.replace(id,'');
    			$('#EvidenceSelectedFiles').val(selected_files+ ' , ' + id); 
				var removed_files = $('#EvidenceRemovedFiles').val();
    			removed_files = removed_files.replace(id,'');
    			$('#EvidenceRemovedFiles').val(removed_files);
				$('.btn-info').attr('disabled',false);
  			},
			items: "li:not(.disabled)",
			cursor: "move",
			connectWith: ".connectedSortable",
			placeholder: "ui-state-highlight",
			dropOnEmpty: true,
    		}).disableSelection();
		});
	</script>
	<?php
		if($evidence['Evidence']['publish'] == 1){ 
			echo $this->Form->submit('Link Selected File With The Record',array('class'=>'btn btn-lg btn-info','disabled'));		
		}else{
			echo "<span class='btn brn-lg btn-warning'>You can not move files unless the record is approved or published.</span>";
			echo "<br /><br /><p><small>If this record has came to you for approval and if you wish to add multiple files, do not sent forward or backword. Only click 'Submit' and save the record. Then go back to your dashbord and click on 'Act' to add another file. Clicking 'Edit' will throw an access denied error.</small></p>";
		}
	echo $this->Form->end(); 
	?>
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
    	</table></div>
	<?php } ?>
	</div>
	<div class="col-md-4">
		<p><?php echo $this->element('helps'); ?></p>
	</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#evidences_ajax')));?>
<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$evidence['Evidence']['id'] ,'ajax'),array('async' => true, 'update' => '#evidences_ajax')));?>
<?php echo $this->Js->writeBuffer();?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
