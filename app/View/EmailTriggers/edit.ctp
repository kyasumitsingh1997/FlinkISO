 <div id="emailTriggers_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="emailTriggers form col-md-8">
<h4><?php echo __('Edit Email Trigger'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('EmailTrigger',array('role'=>'form','class'=>'form')); ?>
<div class="row">
		<?php
        $recipents = array();			
		$recs = json_decode($this->request->data['EmailTrigger']['recipents'],true);
		$cc = json_decode($this->request->data['EmailTrigger']['cc'],true);
		$bcc = json_decode($this->request->data['EmailTrigger']['bcc'],true);
			
		echo "<div class='col-md-12'>".$this->Form->input('name',array()) . '</div>'; 
		//echo "<div class='col-md-12'>".$this->Form->input('Message.to',array('name'=>'Message.to[]','type' => 'select','class'=>'chzn-select', 'multiple','options' => $users,'label'=>__('Recepient'),'style'=>'width:100%')) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('details',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('system_table',array()) . '</div>'; 
        ?>
                      
        <div class="col-md-6"><?php echo $this->Form->input('branch_id',  array('style' => 'width:100%', 'label' => __('Branch'), 'options' => $PublishedBranchList)); ?></div>
				
		<?php 
		
		echo "<div class='col-md-6'>".$this->Form->input('if_added',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('if_edited',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('if_publish',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('if_approved',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('if_soft_delete',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('recipents',array('name'=>'recipents[]','type'=>'select','multiple','options'=>$PublishedEmployeeList,
				'default' => $recs, 'value' => $recs
			)
			) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('cc',array('type'=>'select','multiple','options'=>$PublishedEmployeeList,'default' => $cc, 'value' => $cc)) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('bcc',array('type'=>'select','multiple','options'=>$PublishedEmployeeList, 'default' => $bcc, 'value' => $bcc)) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('subject',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('template',array()) . '</div>'; 
	?>
<?php
		echo $this->Form->input('id');
		echo 	$this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
		echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
		echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
		echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
		?>

</div>
<div class="row">
		

<?php

		
	if ($showApprovals && $showApprovals['show_panel'] == true) {
		
		echo $this->element('approval_form');
		
	} else {
		
		echo $this->Form->input('publish', array('label' => __('Publish')));
		
	}
?>
<?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id')); ?>
<?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?><?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer();?>
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
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#emailTriggers_ajax')));?>

<?php echo $this->Js->writeBuffer();?>
</div>
		<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Import from file (excel & csv formats only)</h4>
		</div>
<div class="modal-body"><?php echo $this->element('import'); ?></div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div></div></div></div>

<script>
//    $.validator.setDefaults({
//        submitHandler: function(form) {
//            $(form).ajaxSubmit({
//                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax",
//                type: 'POST',
//                target: '#main',
//                beforeSend: function(){
//                   $("#submit_id").prop("disabled",true);
//                    $("#submit-indicator").show();
//                },
//                complete: function() {
//                   $("#submit_id").removeAttr("disabled");
//                   $("#submit-indicator").hide();
//                },
//                error: function(request, status, error) {                    
//                    alert('Action failed!');
//                }
//	    });
//        }
//    });
//		$().ready(function() {
//    $("#submit-indicator").hide();
//        $('#EmailTriggerAddAjaxForm').validate();        
//    });
$("#submit-indicator").hide();
        $('#EmailTriggerEditForm').validate();
        $("#submit_id").click(function(){
             if($('#EmailTriggerEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                 $('#EmailTriggerEditForm').submit();
             }
        });
</script>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
