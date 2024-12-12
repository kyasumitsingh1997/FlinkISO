<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<style>
.cke_contents{ height: 450px !important;}
</style>
<div id="continualImprovements_ajax">
<?php echo $this->Session->flash();?><div class="nav">
<div class="continualImprovements form col-md-8">
<h4>Add Continual Improvement</h4>
<?php echo $this->Form->create('ContinualImprovement',array('role'=>'form','class'=>'form','default'=>false)); ?>
<div class="row">
		<fieldset>
			<?php
        		echo "<div class='col-md-12'>".$this->Form->input('name',array()) . '</div>'; 
        		echo "<div class='col-md-6'>".$this->Form->input('corrective_preventive_action_id',array()) . '</div>'; 
        		echo "<div class='col-md-6'>".$this->Form->input('process_id',array()) . '</div>'; 
        		echo "<div class='col-md-6'>".$this->Form->input('internal_audit_id',array()) . '</div>'; 
        		echo "<div class='col-md-6'>".$this->Form->input('internal_audit_detail_id',array()) . '</div>'; 
        		echo "<div class='col-md-6'>".$this->Form->hidden('division_id',array()) . '</div>'; 
            ?>
            <div class="col-md-12">
                <label for="ContinualImprovementDetails"><?php echo __('Add details below.'); ?></label>
            </div>
            <div class="col-md-12">
                <textarea id="ContinualImprovementDetails" name="data[ContinualImprovement][details]"></textarea>
            </div>
        </fieldset>
        <?php
                echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
                echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
                echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
		?>

</div>
<?php echo $this->Html->script(array('ckeditor/ckeditor')); ?>
<script type="text/javascript">
    CKEDITOR.replace('ContinualImprovementDetails', {toolbar: [
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
<div class="row">
<?php
    if ($showApprovals && $showApprovals['show_panel'] == true) {
        echo $this->element('approval_form');
    } else {
        echo $this->Form->input('publish', array('label' => __('Publish')));
    }
?><?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#continualImprovements_ajax','async' => 'false')); ?>
<?php echo $this->Form->end(); ?>
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
<script>
    $.validator.setDefaults({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax",
                type: 'POST',
                target: '#main',
                beforeSend: function(){
                   $("#submit_id").prop("disabled",true);
                    $("#submit-indicator").show();
                },
                complete: function() {
                   $("#submit_id").removeAttr("disabled");
                   $("#submit-indicator").hide();
                },
                error: function(request, status, error) {                    
                    alert('Action failed!');
                }
	    });
        }
    });
		$().ready(function() {
    $("#submit-indicator").hide();
        $('#ContinualImprovementAddAjaxForm').validate();        
    });
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
