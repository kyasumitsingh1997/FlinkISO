<div class="row" id="dashboard_files_div">
  <div class="col-md-12">
    <table class="table table-striped table-hover table-bordered ">
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
    <?php foreach($files as $file):
    	if($file['FileUpload']['file_status'] == 0)echo "<tr class='danger text-danger src_".str_replace(' ','_', Inflector::Humanize($this->request->params['pass'][0]))."'>";
    	else echo "<tr class='src_".str_replace(' ','_', Inflector::Humanize($this->request->params['pass'][0]))."'>";
    	$webroot = "/ajax_multi_upload";
            $fullPath = Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id'). DS . $file['FileUpload']['file_dir'];
    	//$displayPath = '../files/'. $this->Session->read('User.company_id').'/'. str_replace(DS , '/', $file['FileUpload']['file_dir']);
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
            <td width="">
            <?php if($this->Session->read('User.is_mr') == true){ ?>  
            <div id="share_div_<?php echo $file['FileUpload']['id'];?>"></div>
            <script>
                
                    $("#share_<?php echo $file['FileUpload']['id'];?>").on('click',function(){
                        cache: false,
                        $("#share_div_<?php echo $file['FileUpload']['id'];?>").load("<?php echo Router::url('/', true); ?>file_uploads/share/<?php echo $file['FileUpload']['id'];?>/1");
                    });

                </script>
            
                <div class="btn-group dropdown files-drop-down-menu">
                  <button type="button" class="btn btn-default btn-xs dropdown-toggle" id="btn__<?php echo $file['FileUpload']['id'];?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Actions <span class="caret"></span>
                    </button>
                  <ul class="dropdown-menu pull-right">
                    <?php 
                        if($file['FileUpload']['file_status'] == 1){
                            echo '<li>'.$this->Html->link('View',array('controller'=>'file_uploads','action'=>'view',$file['FileUpload']['id'],$file['FileUpload']['system_table_id'],$this->request->params['pass'][0]),array('class'=>'')).'</li>'; 
                            echo '<li>'.$this->Html->link('Edit Files',array('controller'=>'file_uploads','action'=>'edit',$file['FileUpload']['id'],$file['FileUpload']['system_table_id'],$this->request->params['pass'][0]),array('class'=>'')).'</li>'; 
                       //     echo $this->Html->link($this->Html->image('../ajax_multi_upload/img/delete.png'),$delUrl,array('escape'=>FALSE));
                             echo '<li>'.$this->Html->link('Delete Files',array('controller'=>'file_uploads','action'=>'delete_file',$file['FileUpload']['id']),array('class'=>'', 'escape'=>FALSE)).'</li>';
                             echo '<li>'.$this->Html->link('Add Change Request',array('controller'=>'change_addition_deletion_requests','action'=>'lists',$file['FileUpload']['id'],'document'),array('class'=>'', 'escape'=>FALSE)).'</li>';
                             echo '<li>'.$this->Html->link('Access Permissions','#dashboard_files_div',array('class'=>'', 'escape'=>FALSE,'id'=>'share_'.$file['FileUpload']['id'])).'</li>';
                             echo '<li>'.$this->Html->link('Add New File With Approval',array('controller'=>'evidences','action'=>'lists','model'=>'dashboard_files','record'=>$this->request->params['pass'][0]),array('class'=>'', 'escape'=>FALSE)).'</li>';
                        }elseif($file['FileUpload']['file_status'] == 2) {
                            echo '<li>'.$this->Html->link('View',array('controller'=>'file_uploads','action'=>'view',$file['FileUpload']['id'],$file['FileUpload']['system_table_id'],$this->request->params['pass'][0]),array('class'=>'')).'</li>'; 
                          //   echo $this->Html->link($this->Html->image('../ajax_multi_upload/img/delete.png'),array('controller'=>'file_uploads','action'=>'purge',$file['FileUpload']['id']),array('escape'=>FALSE));
                        }
                    ?>
                </ul>
                </div>
                <?php } ?>
            </td>                
        </tr>
    <?php endforeach; ?>
    </table>
    <?php if ($this->Session->read('User.is_mr') == true) { ?>
    <?php
    $filesData['action'] = str_replace(' ', '_', $filesData['action']);
    $filesData['action'] = strtolower($filesData['action']);
      	echo $this->Form->create('Upload', array('role' => 'form', 'class' => 'form blank no-padding no-margin'));
          if ($this->Session->read('User.is_mr') == true)echo $this->Upload->edit('upload', "documents/".$filesData['action'],false);
          else echo $this->Upload->view('upload', "documents/".$filesData['action'],false);
    	  
        echo $this->Form->end();
    ?>
    <?php } ?>
    </div>
</div>
<?php if($tables){ ?>
    <div class="col-md-12">
        <h3><?php echo __('Related Records'); ?></h3>
            <?php foreach ($tables as $table) { ?>
            <?php echo $this->Html->link($table['SystemTable']['name'], array('controller'=>$table['SystemTable']['system_name'], 'action' => 'index'),array('class'=>'btn btn-sm btn-primary')); ?>    <?php }?>                
    </div>
    
 <?php } ?>
<script>
    $(function(){
    $(".files-drop-down-menu").hover(            
            function() {
                $('.dropdown-menu', this).stop( true, true ).fadeIn("fast");
                $(this).toggleClass('open');
                //$('b', this).toggleClass("caret caret-up");                
            },
            function() {
                $('.dropdown-menu', this).stop( true, true ).fadeOut("fast");
                $(this).toggleClass('open');
                //$('b', this).toggleClass("caret caret-up");                
            });
    });    
</script>

