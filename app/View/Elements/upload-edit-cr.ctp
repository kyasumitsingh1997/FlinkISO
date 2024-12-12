<?php $model; ?>
<div class="box box-solid box-info file-box">
    <div class="box-header file-header">
    <?php echo "<h4 class='pull-left'>" . __("Evidence Files") . "</h4><span class='pull-right'> ". 
                $this->Html->link('Upload file with approval&nbsp;&nbsp;<i class="fa fa-upload" aria-hidden="true"></i>',
                array('controller'=>'evidences','action'=>'lists','model'=>$this->request->params['controller'],'record'=>$recordId),array('escape'=>false, 'class'=>'pull-right btn btn-success')) ."</span>"; ?>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover table-bordered table-responsive " id="upload_table">
        	<tr>
        		<th>File Name</th>
        		<th>Version</th>
        		<th>Comment</th>
        		<th>By</th>
                <th>Prepared By</th>  
                <th>Approved By</th>                             
                <th>Created</th>          
        		<th>Actions</th>                
        	</tr>      
                <?php foreach($files as $file):
                if($file['FileUpload']['file_status'] == 0)echo "<tr class='danger text-danger'>";
        	       else echo "<tr>";
        	       $webroot = "/ajax_multi_upload";
        	       $fullPath = Configure::read('MediaPath') . 'files' . DS . $this->Session->read('User.company_id'). DS . $file['FileUpload']['file_dir'];
        	       $displayPath = base64_encode(str_replace(DS , '/', $file['FileUpload']['id']));
        	       $baseEncFile = base64_encode($fullPath);
        	       $delUrl = "$webroot/file_uploads/delete/".$file['FileUpload']['id'];
        	       $permanentDelUrl = "$webroot/file_uploads/purge/".$file['FileUpload']['id'];
        ?>
                 <td>
                <?php 
                    if($file['FileUpload']['file_status'] == 1 or $file['FileUpload']['file_status'] == 2) echo $this->Html->link($file['FileUpload']['file_details'].'.'.$file['FileUpload']['file_type'],array(
                            'controller' => 'file_uploads',
                            'action' => 'view_media_file',
                            'full_base' => $displayPath
                        ), array('target'=>'_blank','escape'=>TRUE));
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
                                echo '<li>'.$this->Html->link('Add New File With Approval',array('controller'=>'evidences','action'=>'lists','model'=>$this->request->params['controller'],'record'=>$recordId),array('class'=>'', 'escape'=>FALSE)).'</li>';
                            
                            }elseif($file['FileUpload']['file_status'] == 2) {
                                echo '<li>'.$this->Html->link('View',array('controller'=>'file_uploads','action'=>'view',$file['FileUpload']['id'],$file['FileUpload']['system_table_id'],$this->request->params['pass'][0]),array('class'=>'')).'</li>';                       
                            }
                        ?>
                    </ul>
                    </div></td>                
            </tr>
        <?php endforeach; ?>
        </table>
        <?php echo $this->Form->create('Upload', array('role' => 'form', 'class' => 'form')); ?>
        <?php
        	echo $this->Upload->edit('upload', $usersId . '/' . 'master_list_of_formats' . '/' . $recordId,false);
        	echo $this->Form->end();
        ?>
    </div>
</div>