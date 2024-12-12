<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min','ckeditor/ckeditor')); ?>
<?php echo $this->fetch('script');?>
 <div id="riskAssessments_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="riskAssessments form col-md-8">
<h4><?php echo __('Approve Risk Assessment'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('RiskAssessment',array('role'=>'form','class'=>'form')); ?>
<div class="row">
      <?php
	    echo "<div class='col-md-12'>".$this->Form->input('title',array()) . '</div>'; 
	    echo "<div class='col-md-6'>".$this->Form->input('process_id',array()) . '</div>'; 
	    echo "<div class='col-md-6'>".$this->Form->input('branch_id',array()) . '</div>'; 
	    echo "<div class='col-md-12'>".$this->Form->input('task',array()) . '</div>'; 
	    echo "<div class='col-md-6'>".$this->Form->input('ra_date',array()) . '</div>'; 
	    echo "<div class='col-md-6'>".$this->Form->input('ra_expert_1',array()) . '</div>'; 
	    echo "<div class='col-md-6'>".$this->Form->input('ra_expert_2',array()) . '</div>'; 
	    echo "<div class='col-md-6'>".$this->Form->input('management',array()) . '</div>'; 
	    echo "<div class='col-md-6'>".$this->Form->input('technical_expert',array()) . '</div>'; 
	    echo "<div class='col-md-6'>".$this->Form->input('risk_control_exprt',array()) . '</div></div>'; 
	    echo "<div class='row'><div class='col-md-6'>".$this->Form->input('reference_number',array()) . '</div>'; 
	    echo "<div class='col-md-6'>".$this->Form->input('hazard_type_id',array()) . '</div></div>'; 
	    echo "<div class='row'><div class='col-md-6'>".$this->Form->input('injury_type_id',array()) . '</div>'; 
	    echo "<div class='col-md-6'>".$this->Form->input('hazard_source_id',array()) . '</div>'; 
	    echo " </div>
            <div class='row'><div class='col-md-12'>
            <label for='RiskAssessmentExistingControls'>What Could Happen</label></div>
            <div class='col-md-12'>
            <textarea id='RiskAssessmentWhatCouldHappan' name=data[RiskAssessment][what_could_happan]>".$this->data['RiskAssessment']['what_could_happan']."</textarea>
            </div>";
	    echo "<div class='col-md-6'>".$this->Form->input('accident_type_id',array()) . '</div>'; 
	    echo "<div class='col-md-6'>".$this->Form->input('severiry_type_id',array()) . '</div>'; 
	    echo " </div>
            <div class='row'><div class='col-md-12'>
            <label for='RiskAssessmentExistingControls'>Existing Controls</label></div>
            <div class='col-md-12'>
            <textarea id='RiskAssessmentExistingControls' name=data[RiskAssessment][existing_controls]>".$this->data['RiskAssessment']['existing_controls']."</textarea>
            </div>"; 
	    echo " </div>
           
            <div class='row'><div class='col-md-12'>
            <label for='RiskAssessmentLikelihood'>Likelihood</label>
                    </div>
           
            <div class='col-md-12'>
                     <textarea id='RiskAssessmentLikelihood' name=data[RiskAssessment][likelihood]>".$this->data['RiskAssessment']['likelihood']."</textarea>
                    
                   </div>"; 
	    echo "<div class='col-md-6'>".$this->Form->input('risk_rating_id',array()) . '</div>'; 
	      echo " </div>
           
            <div class='row'><div class='col-md-12'>
            <label for='RiskAssessmentAdditionalControlNeeded'>Additional Control Needed</label>
                    </div>
           
            <div class='col-md-12'>
                     <textarea id='RiskAssessmentAdditionalControlNeeded' name=data[RiskAssessment][additional_control_needed]>".$this->data['RiskAssessment']['additional_control_needed']."</textarea>
                    
                   </div>";  
	    echo "<div class='col-md-4'>".$this->Form->input('person_responsible',array()) . '</div>'; 
	    echo "<div class='col-md-4'>".$this->Form->input('target_date',array()) . '</div>'; 
	    echo "<div class='col-md-4'>".$this->Form->input('completions_date',array()) . '</div>'; 
	 echo " </div>
           
            <div class='row'><div class='col-md-12'>
            <label for='RiskAssessmentProcessNotes'>Process Notes</label>
                    </div>
           
            <div class='col-md-12'>
                     <textarea id='RiskAssessmentProcessNotes' name=data[RiskAssessment][process_notes]>".$this->data['RiskAssessment']['process_notes']."</textarea>
                    
                   </div>"; 
	    echo " </div>
           
            <div class='row'><div class='col-md-12'>
            <label for='RiskAssessmentTaskNotes'>Task Notes</label>
                    </div>
           
            <div class='col-md-12'>
                     <textarea id='RiskAssessmentTaskNotes' name=data[RiskAssessment][task_notes]>".$this->data['RiskAssessment']['task_notes']."</textarea>
                    
                   </div>"; 
  ?>
<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
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
<?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
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
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#riskAssessments_ajax')));?>

<?php echo $this->Js->writeBuffer();?>
</div>
<script>
    $.validator.setDefaults({



        submitHandler: function(form) {

$("#RiskAssessmentWhatCouldHappan").val( CKEDITOR.instances.RiskAssessmentWhatCouldHappan.getData());
              $("#RiskAssessmentAdditionalControlNeeded").val( CKEDITOR.instances.RiskAssessmentAdditionalControlNeeded.getData());
              $("#RiskAssessmentLikelihood").val( CKEDITOR.instances.RiskAssessmentLikelihood.getData());
              $("#RiskAssessmentExistingControls").val( CKEDITOR.instances.RiskAssessmentExistingControls.getData());
              $("#RiskAssessmentProcessNotes").val( CKEDITOR.instances.RiskAssessmentProcessNotes.getData());
              $("#RiskAssessmentTaskNotes").val( CKEDITOR.instances.RiskAssessmentTaskNotes.getData());
         
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/approve/<?php echo $this->request->params['pass'][0] ?>/<?php echo $this->request->params['pass'][1] ?>",
                type: 'POST',
                target: '#riskAssessments_ajax',
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
        $('#RiskAssessmentApproveForm').validate();        
    });
</script>
<script type="text/javascript">
    CKEDITOR.replace('RiskAssessmentWhatCouldHappan', {toolbar: [
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
    CKEDITOR.replace('RiskAssessmentAdditionalControlNeeded', {toolbar: [
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
    CKEDITOR.replace('RiskAssessmentLikelihood', {toolbar: [
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
    CKEDITOR.replace('RiskAssessmentExistingControls', {toolbar: [
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
    CKEDITOR.replace('RiskAssessmentProcessNotes', {toolbar: [
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
    CKEDITOR.replace('RiskAssessmentTaskNotes', {toolbar: [
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