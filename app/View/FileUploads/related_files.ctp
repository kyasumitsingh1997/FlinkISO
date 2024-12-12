<br />
<?php
echo '<ul class="list-group">';
	if(isset($related_uploaded_files)){
		foreach($related_uploaded_files as $modelname=>$related_files):
			
			echo "<li class='list-group-item active'>". Inflector::Humanize(Inflector::underscore($modelname))." <small class='pull-right'>Evidence Files</small></li>";
			if($related_files){
				foreach($related_files as $related_files){
					foreach ($related_files as $related_file) {
						// echo $related_file['FileUpload']['file_details'];
						$webroot = "/ajax_multi_upload";
				        $fullPath = Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id'). DS . $related_file['FileUpload']['file_dir'];    	
				        $displayPath = base64_encode(str_replace(DS , '/', $related_file['FileUpload']['id']));
				    	$baseEncFile = base64_encode($fullPath);
						echo "<li class='list-group-item'>". $this->Html->link($related_file['FileUpload']['file_details'].'.'.$related_file['FileUpload']['file_type'], array(
				            'controller' => 'file_uploads','action' => 'view_media_file','full_base' => $displayPath),array('target'=>'_blank','escape'=>TRUE)) . "<br /><small> By : ".$related_file['CreatedBy']['name']. " on : " .$related_file['FileUpload']['created']. "</small></li>";
					}

					
				}
			}else{
				echo "<li class='list-group-item'>No files</li>";
			}
			
		endforeach; ?>
	<?php }else{ ?> 
Files Not Found...
	<?php }?><br />
	<?php
	if(isset($final_approval_files)){
		foreach($final_approval_files as $key=> $related_files):			
		echo "<li class='list-group-item active'>".Inflector::Humanize(Inflector::underscore($key))." <small class='pull-right'>Approval Process Files</small></li>";
		foreach($related_files as $related_file){			
			if($related_files){
				
					//echo $related_files['FileUpload']['file_details'];
					$webroot = "/ajax_multi_upload";
			        $fullPath = Configure::read('MediaPath'). 'files' . DS . $this->Session->read('User.company_id'). DS . $related_file['FileUpload']['file_dir'];    	
			        $displayPath = base64_encode(str_replace(DS , '/', $related_file['FileUpload']['id']));
			    	$baseEncFile = base64_encode($fullPath);
					echo "<li class='list-group-item'>". $this->Html->link($related_file['FileUpload']['file_details'].'.'.$related_file['FileUpload']['file_type'], array(
			            'controller' => 'file_uploads','action' => 'view_media_file','full_base' => $displayPath),array('target'=>'_blank','escape'=>TRUE)) . "<br /><small> By : ".$related_file['CreatedBy']['name']. " on : " .$related_file['FileUpload']['created']. "</small></li>";
				
			}else{
				echo "<li class='list-group-item'>No files</li>";
			}
			}
		endforeach; ?>
	<?php }else{ ?> 

	<?php }?>
</ul>
