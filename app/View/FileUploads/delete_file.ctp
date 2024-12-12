<?php echo $this->element('checkbox-script'); ?> 
<div id="fileUploads_ajax">
<?php echo $this->Session->flash();?>
<div class="nav panel panel-default">
<div class="fileUploads form col-md-8">
<h4><?php echo __('Delete File Upload'); ?> <small><?php if(strstr($this->request->referer(),'view') != false) echo $this->Html->link(__('Back'),$this->request->referer(),array('class'=>'badge btn-info btn-small')); ?>  <?php echo $this->Html->link(__('Delete All'), '#deleteAll', array('class' => 'badge btn-info btn-small', 'data-toggle' => 'modal', 'onClick' => 'getVals()')); ?></small></h4> 
<?php echo $this->Form->create(array('class' => 'no-padding no-margin no-background')); ?>
<div class="row">
<div class="col-md-12">

	<?php echo "<h4>&nbsp;&nbsp;" . __("Revisions") . "</h4>"; ?>
<table class="table table-striped table-hover table-bordered table-responsive ">
	<tr>
             <th ><input type="checkbox" id="selectAll"></th>
		<th>File Name</th>
		<th>Version</th>
		<th>Comment</th>
		<th>By</th>
                <th>Prepared By</th>  
                <th>Approved By</th>                             
                <th>Created</th>          
		<th>Delete</th>                
	</tr>      
        <?php foreach($revisions as $file):
            if($file['FileUpload']['file_status'] == 0)echo "<tr class='danger text-danger'>";
	else echo "<tr>";
	$webroot = "/ajax_multi_upload";
	$fullPath = Configure::read('MediaPath') . 'files' . DS . $this->Session->read('User.company_id'). DS . $file['FileUpload']['file_dir'];
	//$displayPath = '../files/'. $this->Session->read('User.company_id').'/'. str_replace(DS , '/', $file['FileUpload']['file_dir']);
        $displayPath = base64_encode(str_replace(DS , '/', $file['FileUpload']['id']));
	$baseEncFile = base64_encode($fullPath);
	$delUrl = "$webroot/file_uploads/delete/".$file['FileUpload']['id'];
?>
        <td class=" actions">
         <?php echo $this->Form->checkbox('rec_ids', array('label' => false, 'div' => false, 'value' => $file['FileUpload']['id'], 'multiple' => 'checkbox', 'class' => 'rec_ids', 'onClick' => 'getVals()')); ?> </td>
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
        <td>
        <?php
            if($file['FileUpload']['file_status'] == 0)echo "Deleted ". $this->Time->niceShort($file['FileUpload']['created']);
            else echo $this->Time->niceShort($file['FileUpload']['modified']);
        ?>
        </td>                                       
        <td>
        <?php 
            if($file['FileUpload']['system_table_id'] == 'dashboards'){
                  echo $this->Html->link($this->Html->image('../ajax_multi_upload/img/delete.png'),array('controller'=>'file_uploads','action'=>'purge_file',$file['FileUpload']['id'], $file['FileUpload']['system_table_id']),array('escape'=>FALSE));
            }else{
           echo $this->Html->link($this->Html->image('../ajax_multi_upload/img/delete.png'),array('controller'=>'file_uploads','action'=>'purge_file',$file['FileUpload']['id'],$systemTable['SystemTable']['system_name'], $producttype),array('escape'=>FALSE));
            }
                
         
        ?></td>                
    </tr>
<?php endforeach; ?>
</table>
</div>
</div>
</div>
     <?php echo $this->Form->end(); ?>
<script> $("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      format: 'yyyy-mm-dd',
      autoclose:true,
    }); </script>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
</div>

</div>
<?php echo $this->Js->writeBuffer(); ?>
<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }});
</script>

<div class="modal fade" id="deleteAll" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <?php echo $this->Form->create($this->name, array('action' => 'purge_all_files', 'role' => 'form', 'class' => 'form', 'style' => 'clear:both;overflow:auto')); ?>
            <?php echo $this->Form->hidden('recs_selected', array('id' => 'recs_selected_for_delete')); ?>
            <?php echo $this->Form->hidden('producttype', array('id' => 'producttype')); 
            if($file['FileUpload']['system_table_id'] == 'dashboards'){ ?>
                  <?php echo $this->Form->hidden('modelName', array('value' => $file['FileUpload']['system_table_id'])); ?>
            <?php }else{ ?>
            <?php echo $this->Form->hidden('modelName', array('value' => $systemTable['SystemTable']['system_name'])); ?>
            <?php } ?>
            <?php echo $this->Form->hidden('deleteAll.controller_name', array('value' => $this->request->params['controller'])); ?>
            <h4 class="modal-title"><?php echo __('Are you sure to delete all selected ') . $this->name; ?> ?</h4>
            <?php echo $this->Form->submit('Yes', array('class' => 'btn btn-success', 'style' => 'margin-top:10px;margin-left:5px;')); ?>
            <?php echo $this->Form->button('No', array('class' => 'btn btn-warning', 'data-dismiss' => "modal", 'style' => 'margin-top:10px;margin-left:5px;')); ?>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
