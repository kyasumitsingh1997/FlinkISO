 <div id="fileUploads_ajax">
<?php echo $this->Session->flash();?>
<div class="nav panel panel-default">
<div class="fileUploads form col-md-8">
<h4><?php echo __('Edit File Upload'); ?> <small><?php if(strstr($this->request->referer(),'view') != false) echo $this->Html->link(__('Back'),$this->request->referer(),array('class'=>'badge btn-info btn-small')); ?></small></h4>
<?php echo $this->Form->create('FileUpload',array('role'=>'form','class'=>'form')); ?>

	<?php echo $this->Form->input('id'); ?>

    <div class="row">
<div class="col-md-6"><?php 
    $file_details = explode("-ver-",$this->data['FileUpload']['file_details']);
		echo $this->Form->input('file_details',array('label'=>'File Name', 'value'=>$file_details[0])); 
       echo $this->Form->hidden('old_file_details',array('label'=>'File Name','value'=>$this->data['FileUpload']['file_dir']));
       echo $this->Form->hidden('system_table_id',array('label'=>'File Name','value'=>$this->data['FileUpload']['system_table_id'])); 
       echo $this->Form->hidden('file_dir',array('label'=>'File Dir')); ?>       
    </div>
    
	<div class="col-md-3"><br /><br /><?php echo "-ver-".$file_details[1].".";
		echo $this->data['FileUpload']['file_type'];
		echo $this->Form->hidden('file_type'); ?>
    </div>  
	<div class="col-md-3"><?php 
            echo $this->Form->input('version',array('value'=>$this->data['FileUpload']['version'], 'min'=>1)); 
	?></div>
    <div class="col-md-4"><?php echo $this->Form->input('user_id',array('label'=>'Uploaded By','style'=>'width:100%')); ?></div>
   	<div class="col-md-4"><?php echo $this->Form->input('prepared_by',array('options'=>$PublishedEmployeeList,'label'=>'Prepared By','style'=>'width:100%')); ?></div>              
    <div class="col-md-4"><?php echo $this->Form->input('approved_by',array('options'=>$PublishedEmployeeList,'label'=>'Approved By','style'=>'width:100%')); ?></div>
</div>
<div class="row">            

    <?php 
    echo $file['FileUpload']['file_content'] ?>
               <div class="col-md-12"><h4><?php echo __('Current Document Details (Optional)'); ?></h4>
                    <textarea name="data[FileUpload][file_content]" id="FileUploadFileContent" >
                        <?php echo $this->data['FileUpload']['file_content'] ?>
                    </textarea>
                </div>
        </div>
<div class="row">
    	<div class="col-md-12"><?php echo $this->Form->input('comment'); ?></div>
    	<div class="col-md-12"><?php echo $this->Form->input('archived'); ?></div>        

    </div>

	
    <?php if($showApprovals && $showApprovals['show_panel'] == true ) { ?>
    <?php echo $this->element('approval_form'); ?>
    <?php } else {echo $this->Form->input('publish', array('label'=> __('Publish'))); } ?>
    <?php echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success'));?>
    <?php echo $this->Form->end(); ?>
    <?php echo $this->Js->writeBuffer();?>

<div class="row">
<div class="col-md-12">
	<?php echo "<h4>&nbsp;&nbsp;" . __("Current File") . "</h4>"; ?>
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
        <?php foreach($current_file as $file):
            if($file['FileUpload']['file_status'] == 0)echo "<tr class='danger text-danger'>";
	else echo "<tr>";
	$webroot = "/ajax_multi_upload";
	$fullPath = Configure::read('MediaPath') . 'files' . DS . $this->Session->read('User.company_id'). DS . $file['FileUpload']['file_dir'];
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
        <td>
        <?php
            if($file['FileUpload']['file_status'] == 0)echo "Deleted ". $this->Time->niceShort($file['FileUpload']['created']);
            else echo $this->Time->niceShort($file['FileUpload']['modified']);
        ?>
        </td>                                       
        <td>
        <?php 
            if($file['FileUpload']['file_status'] == 1){
                echo $this->Html->link('Edit',array('controller'=>'file_uploads','action'=>'edit',$file['FileUpload']['id'],$this->request->params['controller'],$this->request->params['pass'][0]),array('class'=>'badge btn-warning')); 
                echo $this->Html->link($this->Html->image('../ajax_multi_upload/img/delete.png'),$delUrl,array('escape'=>FALSE));
            }else {
                echo $this->Html->link('Edit',array('controller'=>'file_uploads','action'=>'edit',$file['FileUpload']['id'],$this->request->params['controller'],$this->request->params['pass'][0]),array('class'=>'badge btn-warning')); 
            }
        ?></td>                
    </tr>
<?php endforeach; ?>
</table>
	<?php echo "<h4>&nbsp;&nbsp;" . __("Revisions") . "</h4>"; ?>
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
        <td><?php echo $this->Html->image('../ajax_multi_upload/img/fileicons/'.$file['FileUpload']['file_type'].'.png'); ?> 
        <?php 
				if($file['FileUpload']['file_status'] == 1 or $file['FileUpload']['file_status'] == 3)echo $this->Html->link($file['FileUpload']['file_details'].'.'.$file['FileUpload']['file_type'],array(
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
            if($file['FileUpload']['file_status'] == 1){
                echo $this->Html->link('Edit',array('controller'=>'file_uploads','action'=>'edit',$file['FileUpload']['id'],$this->request->params['controller'],$this->request->params['pass'][0]),array('class'=>'badge btn-warning')); 
                echo $this->Html->link($this->Html->image('../ajax_multi_upload/img/delete.png'),$delUrl,array('escape'=>FALSE));
            }else {
                echo $this->Html->link('Edit',array('controller'=>'file_uploads','action'=>'edit',$file['FileUpload']['id'],$this->request->params['controller'],$this->request->params['pass'][0]),array('class'=>'badge btn-warning')); 
            }
        ?></td>                
    </tr>
<?php endforeach; ?>
</table>
</div>
</div>
</div>
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
<div class="col-md-12">
    <h3>Record Details</h3>
    <div id="fileView"><div></div>
</div>
<script type="text/javascript">
    $().ready(function(){
        $("#fileView").load("<?php echo Router::url('/', true);?><?php echo Inflector::Variable($file['SystemTable']['name']);?>/view/<?php echo $record['id'];?>");
    });
</script>
<?php echo $this->Html->script(array('ckeditor/ckeditor')); ?>
<script type="text/javascript">
        CKEDITOR.replace('FileUploadFileContent', {toolbar: [
                ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
                {name: 'insert', items: ['Table', 'HorizontalRule', 'SpecialChar', 'PageBreak']},
                {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
                {name: 'document', items: ['Preview', '-', 'Templates']},
                '/',
                {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']},
                {name: 'basicstyles', items: ['Bold', 'Italic']},
                {name: 'styles', items: ['Format', 'FontSize']},
                {name: 'colors', items: ['TextColor', 'BGColor']},
            ]
        });
</script>
