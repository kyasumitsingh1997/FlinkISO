<table class="table table-responsive">
	<tr>
		<th><?php echo __('File'); ?></th>
		<th><?php echo __('Prepared By'); ?></th>
		<th><?php echo __('Approved By'); ?></th>
		<th><?php echo __('Date'); ?></th>
		<th width="42"><?php echo __("Act"); ?></th>
	</tr>
	<?php 
		foreach ($not_seen as $file) { ?>
		<tr>
		<?php 
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
			<td><?php echo $employees[$file['FileUpload']['prepared_by']];?></td>
			<td><?php echo $employees[$file['FileUpload']['approved_by']];?></td>
			<td><?php echo $file['FileUpload']['created'];?></td>
			<td><?php echo $this->Html->link('View',array('action'=>'view',$file['FileUpload']['id']),array('class'=>'btn btn-xs btn-warning'));?></td>
		</tr>
		<?php } ?>
</table>