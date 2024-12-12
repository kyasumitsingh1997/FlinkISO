<?php echo $this->Html->script(array('jquery.validate','jquery-form'));?>
<?php echo $this->fetch('script');?>

<script>
$.validator.setDefaults({
submitHandler: function(form) {
$(form).ajaxSubmit({
url:'<?php echo Router::url('/', true);?><?php echo $this->request->params['controller']?>/add_ajax',
type:'POST',
target: '#main'});}
});

$().ready(function() {
$('#EducationAddAjaxForm').validate();});
</script>

<div id="educations_ajax">
<?php echo $this->Session->flash();?><div class="nav">
<div class="educations form col-md-8">
<h4>Add Education</h4>
<?php echo $this->Form->create('Education',array('role'=>'form','class'=>'form','default'=>false)); ?>
<div class="row">
			<div class="col-md-6"><?php echo $this->Form->input('title'); ?></div> 
	<div class="col-md-6"><?php echo $this->Form->input('branchid',array('type'=>'hidden','value'=>$this->Session->read('User.branch_id'))); ?></div> 
	<div class="col-md-6"><?php echo $this->Form->input('departmentid',array('type'=>'hidden','value'=>$this->Session->read('User.department_id'))); ?></div> 
	</row>
</div>
	<?php if($show_approvals && $show_approvals['show_panel'] == true ) { ?>
		<div class="clearfix">&nbsp;</div>
				<div class="panel panel-default">
				<div class="panel-heading"><h3 class="panel-title">
				<?php echo __("Send for approval") ?></h3></div>
				<div class="panel-body"><?php echo __("Records added to this table will be send to the person you choose from the list below.")?>
			<?php echo $this->Form->input('Approval.user_id',array('options'=>$userids));?>
			<?php echo $this->Form->input('Approval.comments',array('type'=>'textarea'));?>
		<?php if($show_approvals['show_publish'] == true)echo $this->Form->input('publish',array('label'=>'Do not send forward. Publish Now')) ?>
	</div> <?php } else {echo $this->Form->input('publish'); }
 ?>

<?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#educations_ajax','async' => 'false')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer();?>
	
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
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
