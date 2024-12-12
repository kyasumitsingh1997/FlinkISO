<?php
foreach($tableFields as $key=>$value):
	if($key != 'publish' && $key != 'soft_delete' && $key != 'sr_no' && $key != 'created_by' && $key != 'modified_by' && $key != 'created' && $key != 'modified'  && $key != 'branchid'  && $key != 'departmentid'  && $key != 'id'  && $key != 'system_table_id'  && $key != 'master_list_of_format_id' ){
		$options[$key] = Inflector::humanize(Inflector::singularize($key));
	  }
endforeach;
?>
<script>
	function add_val($value,$i){
		$("#"+$i).val($value.value);
	}
</script>

<div id="fileUploads_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-body">
<div class="fileUploads form col-md-12">
<?php if($missing_file != true){ ?>
	<?php if($missing_fields){ ?>	
		<div class="col-md-12">
			<div class="alert alert-danger">
				<h1>You can not import data from this file</h1>
				Following fields are missing form your file. <br />
				We request you do download the <strong>"sample file" form the first tab</strong> and add your data in that file with correct fields and upload the file and then try importing again.
				
				<h5>Missing Fields</h5>
				<ul style="font-size: 15px">
				<?php
					foreach($missing_fields as $missing_field):
						echo "<li>".$missing_field."</li>";
					endforeach;
				?>
				</ul>
				<h3>Note : The file you just uplaoded has been deleted from the server</h3>
			</div>
		</div>
		<?php } elseif($missing_data_fields){ ?>
		<h3>Missing Data</h3>
			<div class="alert alert-danger">
					<h3>Can not import data</h3>
				<p>
					Please make sure you already have follwing data in the system. You can not import data unless you have these values manually entered. <br/>
					<br />
					<?php foreach($missing_data_fields as $missing_model=> $fields):  ?>
						<ul>
							<li><strong><?php echo $this->Html->link($missing_model,array('controller'=>Inflector::tableize($missing_model),'action'=>'lists')) ?></strong></li>
							<ul>
								<?php foreach($fields as $field): ?>
								<li><?php echo $field ?></li>
								<?php endforeach ?>
							</ul>
						</ul>		
					<?php endforeach; 
					
					?>
				</p>	
			</div>
			
			<div class="alert alert-info">
				<h3>What does this means ?</h3>
				<p>You get this message when the file you have imported has data which has a dependancy on other tables.
				e.g. If you are importing the employees.When you add employee, you can select their branch, departments from dropdowns.
				These values (branch &amp; departments etc) are already stored in your system and system links those value with each other.
				Like you link employee to a branch and department as so on.</p>
				<br />
				<p>The file you have imported, has certain data which is linked with other data in your system.
				Unless you do not have that data in your system, you can not successfully import your new data into the system.</p>
				<br />
				<p>For your convinience, list of such data is displyed in the red alert box above.
				You can either add this new data to their respective tables if you have permissions to do so,
				or if you find that there are any spelling mistakes in your current data, or some monir changes are required to your current data,
				please make those changes in your file and upload your file again and try again.</p>
				<br />
			</div>
		
		<?php } else { ?>
		
		<div class="col-md-12">
			<p>
					<?php
					$path = '/import/'.$this->request->params['pass'][1].'/'.$this->request->params['pass'][0].'/'.$this->request->params['pass'][2];
					$file = new File(Configure::read('MediaPath').'files/'.$path);
					
					$fileDetails = $file->info();
					
					?>				
					<div class ="panel-panel-body" style="text-align: center">
							<h1><div class="glyphicon glyphicon-file text-lg"></div></h1>
							<h4><?php echo $fileDetails['basename'] ?></h4>
							<h5><?php
							if($fileDetails['filesize'] < 1000000){
							echo round($fileDetails['filesize']/1024) .'kb';
							}else{
							echo round($fileDetails['filesize']/1024) .'kb';
							}
							$fileChange = $file->lastChange();
							?></h5>
							
							
							<p class="text-small">Last Modified :<?php echo date('Y-M-d h:m',$fileChange); ?></p>
							<?php echo $this->Form->create('FileUpload',array('action'=>'save_imported_data','role'=>'form','class'=>'form')); ?>
							<?php echo $this->Form->hidden('fileDetails',array('value'=>'files/import/'.$this->request->params['pass'][1].'/'.$controller_name.'/'.$this->request->params['pass'][2],'label'=>false));?>
							<?php echo $this->Form->submit(__('Click Here To Add Data'),array('div'=>false,'class'=>'btn btn-lg btn-success','style'=>'float:none')); ?>
							<?php echo $this->Form->end(); ?>
							<?php echo $this->Js->writeBuffer();?>
					</div>
			</p>
			<div class="row"><div class="col-md-12">
			<iframe class="panel panel-body"  width="100%" height="400px" src="<?php echo Router::url('/', true);?>file_uploads/show_file/<?php echo str_replace('/','<>',str_replace(Configure::read('MediaPath'), '',$file->path)) ?>"></iframe>
			</div></div>
		</div>
	<?php } ?>
	<?php } else { ?>
		<div class="col-md-12">
			<h2>File not found</h2>
		</div>
	<?php } ?>
	</fieldset>
</div>
<script> $("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      format: 'yyyy-mm-dd',
      autoclose:true,
    }); </script>

</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#fileUploads_ajax')));?>

<?php echo $this->Js->writeBuffer();?>
</div>
		<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title"><?php echo __('Import from file (excel & csv formats only)'); ?></h4>
		</div>
<div class="modal-body"><?php echo $this->element('import'); ?></div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close');?></button>
		</div></div></div></div>
