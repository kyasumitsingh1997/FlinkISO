<div id="js_div_<?php echo $this->request->params['pass'][0]; ?>"></div>
<?php 
	echo $this->Html->script(array('bootstrap.min'));
	echo $this->fetch('script');
?>
<script>$(function() {$('#share_model_<?php echo $this->request->params['pass'][0]; ?>').modal();});</script>
<style type="text/css">
.chosen-container-single .chosen-single, .element.style (width: 100% !important);
</style>

<div class="modal fade" id="share_model_<?php echo $this->request->params['pass'][0]; ?>">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo __('Share Document'); ?></h4>
      </div>
      <div class="modal-body">
      	<?php if(isset($permission) && ($permission == 0)){ ?>      	
      	<h2><?php echo __('Sorry'); ?> <small><?php echo __('You do not have permission to access this file'); ?></small></h2>
      	<?php } else { ?>
			<div class="row">
				<div id="steps-tabs_<?php echo $this->request->params['pass'][0]; ?>" class="col-md-12">						
					<?php echo $this->Form->create('Share', array('role' => 'form', 'class' => 'form', 'default' => false)); ?>
			    	<ul>
			    		<?php foreach ($branches as $key => $value) { ?>
							<li><a href="#<?php echo $key;?>"><?php echo $value['Name']; ?></a></li>
						<?php } ?>			
					</ul>
				<?php 
			    $i = 0;
			    foreach ($branches as $key => $value) { ?>
			        <div id="<?php echo $key?>" >
			        	<fieldset>				        	
							<?php 
								echo "<div class='col-md-12'>".$this->Form->input('FileUpload.'.$i.'.Everyone',array(
									'label'=>'<h4 class="no-margin">Everyone <small>Open file, any user can acess the file in <strong>'.$value['Name'].'</strong> branch</small></h4>', 
									'type'=>'checkbox',
									'id' => 'FileUpload_'. $this->request->params["pass"][0].'-'.$i.'-Everyone',
									'options'=>array('all'=>0))) . '</div>'; 								
								echo 
									"<div class='col-md-12' 
									id='".$key."_".$i."_check_".$this->request->params["pass"][0]."'>".$this->Form->input('FileUpload.'.$i.'.user_id',array(
									'label'=>'<h4>Or Strict Access <small>Only selected users will get access to the file</small></h4>', 
									'options'=>$value['Users'],
									'multiple'=>'checkbox',
									'type'=>'select',
									'default'=>$sel_users)) . '</div>'; 
								
								echo $this->Form->hidden('FileUpload.'.$i.'.branch_id',array('value'=>$key));
								
								echo $this->Form->hidden('FileUpload.'.$i.'.file_upload_id',array('value'=>$this->request->params['pass'][0]));
							?>
						</fieldset>
					</div>
					<script type="text/javascript">
			        	$('#FileUpload_<?php echo $this->request->params["pass"][0]; ?>-<?php echo $i; ?>-Everyone').on('click', function(){
							$("#<?php echo $key ?>_<?php echo $i ?>_check_<?php echo $this->request->params['pass'][0]; ?>").find(':checkbox').prop('checked', this.checked);							
			        	});
			        </script>
			        <?php			        
			        $i++;
					} 
						echo $this->Js->submit('Apply Permissions',array(
					        'before'=>$this->Js->get('#sending_'. $this->request->params['pass'][0])->effect('fadeIn'),
					        'success'=>$this->Js->get('#sending_'. $this->request->params['pass'][0])->effect('fadeOut'),
					        'update'=>'#share_model_alert_'.$this->request->params['pass'][0],
					        'class'=>'btn btn-sm btn-info'
					         ));
			        	echo $this->Form->end();
			        ?>

			</div>
			<div id="share_model_alert_<?php echo $this->request->params['pass'][0]; ?>"></div>
			<div id="sending_<?php echo $this->request->params['pass'][0]; ?>" style="display: none;">reloading...</div>
			<script>$(function() {			
			  $(".chosen-container").width('100%');
			  $(".chosen-container-multi").width('100%');
			  $(".chosen-container-single").width('100%');
			  $( "#steps-tabs_<?php echo $this->request->params['pass'][0]; ?>" ).tabs();});
			</script>
				
			</div>
			 <?php } ?>
		</div>
      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>
<?php echo $this->Js->writeBuffer(array('cache'=>false)); ?>
