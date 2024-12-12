<style type="text/css">
strong{font-weight: 500}
</style>
<div id="masterListOfFormatDepartments_ajax">
<?php echo $this->Session->flash();?>
<div class="nav">
<div class="masterListOfFormatDepartments form col-md-12">
<table class="table table-responsive">
	<tr>
		<th><?php echo __('Document Title'); ?></th>
		<th><?php echo __('Number'); ?></th>
		<th><?php echo __('Issue #'); ?></th>
		<th><?php echo __('Revision #'); ?></th>
		<th><?php echo __('Revision Date'); ?></th>
		<th><?php echo __('Prepared By'); ?></th>
		<th><?php echo __('Approved By'); ?></th> 
		<th width="190"><?php echo __('Action'); ?></th> 		               
	</tr>
	<?php foreach($masterListOfFormatDepartment as $document): ?>
    <?php if($document['flag'] == TRUE)
		{
			if(($document['MasterListOfFormat']['document_details'] == '') && ($document['MasterListOfFormat']['work_instrcutions'] == ''))
				echo "<tr class='warning text-danger'>";
			else
				echo "<tr class='warning'>";	
		}else{
			if(($document['MasterListOfFormat']['document_details'] == '') && ($document['MasterListOfFormat']['work_instrcutions'] == ''))
				echo "<tr class='text-danger'>";
			else
				echo "<tr>";	
		}
	?>
	<td>
		<div class="pull-left" style="width:100%">    
		<strong>
       <?php 
			if(($document['MasterListOfFormat']['document_details'] == '') && ($document['MasterListOfFormat']['work_instrcutions'] == '')){
	   			echo $this->Html->link($document['MasterListOfFormat']['title'].'*',array('controller'=>'master_list_of_formats','action'=>'view',$document['MasterListOfFormat']['id']),array('class'=>'text-danger')); 	   			
	   		}
			else 
				echo $this->Html->link($document['MasterListOfFormat']['title'],array('controller'=>'master_list_of_formats','action'=>'view',$document['MasterListOfFormat']['id'])); 
			?></strong>						
		</div>
			<div class="pull-left"><small><?php echo $masterListOfFormatCategories[$document['MasterListOfFormat']['master_list_of_format_category_id']]?></small></div>
    </td>
	<td><?php echo $document['MasterListOfFormat']['document_number']; ?></td>
	<td><?php echo $document['MasterListOfFormat']['issue_number']; ?></td>
	<td><?php echo $document['MasterListOfFormat']['revision_number']; ?></td>
	<td><?php echo $document['MasterListOfFormat']['revision_date']; ?></td>
	<td><?php echo $document['PreparedBy']['name']; ?></td>
	<td><?php echo $document['ApprovedBy']['name']; ?></td>
	<td>
		<div class='btn-group'>
		 <?php 
				switch ($document['MasterListOfFormat']['document_status']) {
					case 0:
						echo '<div class="btn btn-xs btn-warning">'.$documentStatuses[$document['MasterListOfFormat']['document_status']].'</div>';
						break;
					case 1:
						echo '<div class="btn btn-xs btn-success">'.$documentStatuses[$document['MasterListOfFormat']['document_status']].'</div>';
						break;
					case 2:
						echo '<div class="btn btn-xs btn-danger">'.$documentStatuses[$document['MasterListOfFormat']['document_status']].'</div>';
						break;
					case 3:
						echo '<div class="btn btn-xs btn-danger">'.$documentStatuses[$document['MasterListOfFormat']['document_status']].'</div>';
						break;

					default:
						# code...
						break;
				}
			?>
			<?php 
				if($this->Session->read('User.is_mr')== 1 && $document['flag'] == FALSE && $document['MasterListOfFormat']['document_status'] != 2)echo $this->Html->link('Create CR',array('controller'=>'change_addition_deletion_requests','action'=>'lists',$document['MasterListOfFormat']['id']),array('class'=>'btn btn-xs btn-info'));
				elseif($this->Session->read('User.is_mr')== 1) echo $this->Html->link('View CR',array('controller'=>'change_addition_deletion_requests','action'=>'edit',$document['flag_id']),array('class'=>'btn btn-xs btn-warning'));
			 ?>
			<?php
			
			foreach($PublishedUserList as $key=>$value):
				$count = 0 ;
				$dir = new Folder(Configure::read('MediaPath') . 'files/' . $this->Session->read('User.company_id') . '/upload/master_list_of_formats/' . $document['MasterListOfFormat']['id'] . '/');
	           $folders = $dir->read();
				$count = $count + count($folders[1]);
			endforeach;		
			
			if($count == 0)echo '<div class="btn btn-xs btn-info">' . $count .' </div>';
			else echo '<div class="btn btn-xs btn-primary">'.$count .'</div>';
		?>
	</div>
	 </td>	        
	</tr>
	<?php endforeach ?>

</table>
</div>
<span class='text-danger'><small>* Update Document Details/Work Instrutions etc.</small></span>
</div>
<?php echo $this->Js->writeBuffer();?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
