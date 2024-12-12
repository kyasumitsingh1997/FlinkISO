 <div id="incidentInvestigations_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="incidentInvestigations form col-md-8">
<h4><?php echo __('Approve Incident Investigation'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('IncidentInvestigation',array('role'=>'form','class'=>'form')); ?>
<div class="row">
<?php
		echo "<div class='col-md-6'>".$this->Form->input('incident_id',array('value'=>$incidentId)) . '</div>';
		echo "<div class='col-md-6'>".$this->Form->input('incident_investigator_id',array()) . '</div>';
		//echo "</div><div class='row'>";
		//echo "<div class='col-md-6'>".$this->Form->input('investigation_type',array('type'=>'radio','options'=>array('Affected Person','Withness'),'default'=>0)) . '</div>';		
		//echo "<div class='col-md-6'><div id='personal'>".$this->Form->input('incident_affected_personal_id',array()) . '</div>'; 
		//echo "<div id='witness' class='hide'>".$this->Form->input('incident_witness_id',array()) . '</div></div>'; 
		//echo "</div><div class='row'>";
		?>
		<?php
		echo "<div class='col-md-4'>".$this->Form->input('reference_number',array()) . '</div>'; 		
		echo "<div class='col-md-4'>".$this->Form->input('investigation_date_from',array()) . '</div>'; 
		echo "<div class='col-md-4'>".$this->Form->input('investigation_date_to',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('title',array()) . '</div>'; 
		?>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h4>Affected Persons</h4>
			<table class="table table-responsive">
				<tr>
					<th>Name</th>
					<th>Investigation Interview Findings</th>
					<th>Action</th>
				</tr>
			<?php if($incidentAffectedPersonals){
				foreach($incidentAffectedPersonals as $personals): ?>
				<tr>
					<td><?php echo $personals['IncidentAffectedPersonal']['name']?></td>
					<td><?php echo $personals['IncidentAffectedPersonal']['investigation_interview_findings']?></td>
					<td><?php echo $this->Html->link('Edit',array('controller'=>'incident_affected_personals','action'=>'edit',$personals['IncidentAffectedPersonal']['id']),array('class'=>'btn btn-xs btn-info')); ?></td>	
				</tr>		

			<?php	endforeach;
			} ?>
		</table>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h4>Witnesses</h4>
			<table class="table table-responsive">
				<tr>
					<th>Name</th>
					<th>Interview Findings</th>
					<th>Action</th>
				</tr>
			<?php if($incidentWitnesses){
				foreach($incidentWitnesses as $witness): ?>
				<tr>
					<td><?php echo $witness['IncidentWitness']['name']?></td>
					<td><?php echo $witness['IncidentWitness']['investigation_interview_findings']?></td>
					<td><?php echo $this->Html->link('Edit',array('controller'=>'incident_witnesses','action'=>'edit',$witness['IncidentWitness']['id']),array('class'=>'btn btn-xs btn-info')); ?></td>	
				</tr>		

			<?php	endforeach;
			} ?>
		</table>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h2>Add Report</h2>
		<div id="investigation_tab">
		  	<ul>
			    <li><a href="#control_measures_currently_in_place">Control Measures</a></li>
			    <li><a href="#summery_of_findings">Summery</a></li>
			    <li><a href="#reason_for_incidence">Reason</a></li>
			    <li><a href="#immediate_action_taken">Immediate Action</a></li>
			    <li><a href="#risk_assessment">Risk Assessment</a></li>
			    <li><a href="#action_taken">Action Taken</a></li>
		  	</ul>
		  	<div id="control_measures_currently_in_place">		    	
		    	<textarea name="data[IncidentInvestigation][control_measures_currently_in_place]" id="IncidentInvestigationControlMeasuresCurrentlyInPlace" >
		    		<?php echo $this->data['IncidentInvestigation']['control_measures_currently_in_place']; ?>
		    	</textarea>
		  	</div>
		  	<div id="summery_of_findings">
		    	<textarea name="data[IncidentInvestigation][summery_of_findings]" id="IncidentInvestigationSummeryOfFindings" >
		    		<?php echo $this->data['IncidentInvestigation']['summery_of_findings']; ?>
		    	</textarea>
		  	</div>
		  	<div id="reason_for_incidence">
		  		<textarea name="data[IncidentInvestigation][reason_for_incidence]" id="IncidentInvestigationReasonForIncidence" >
					<?php echo $this->data['IncidentInvestigation']['reason_for_incidence']; ?>
		  		</textarea>
		  	</div>
		  	<div id="immediate_action_taken">
		  		<textarea name="data[IncidentInvestigation][immediate_action_taken]" id="IncidentInvestigationImmediateActionTaken" >
		  			<?php echo $this->data['IncidentInvestigation']['immediate_action_taken']; ?>	
		  		</textarea>		    	
		  	</div>
		  	<div id="risk_assessment">
		  		<textarea name="data[IncidentInvestigation][risk_assessment]" id="IncidentInvestigationRiskAssessment" >
					<?php echo $this->data['IncidentInvestigation][risk_assessment']; ?>
		  		</textarea>		    	
		  	</div>
		  	<div id="action_taken">
		  		<textarea name="data[IncidentInvestigation][action_taken]" id="IncidentInvestigationActionTaken" >
		  			<?php echo $this->data['IncidentInvestigation][action_taken']; ?>	
		  		</textarea>		    	
		  	</div>
		</div>
	</div>
	</div>
	<div class="row">
		<?php 
		echo "<div class='col-md-12'>".$this->Form->input('investigation_reviewd_by',array()) . '</div>'; 		
		//echo "<div class='col-md-6'>".$this->Form->input('corrective_preventive_action_id',array()) . '</div>'; 
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#incidentInvestigations_ajax')));?>

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
        CKEDITOR.replace('IncidentInvestigationControlMeasuresCurrentlyInPlace', {toolbar: [
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

        CKEDITOR.replace('IncidentInvestigationSummeryOfFindings', {toolbar: [
               ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
                {name: 'insert', items: ['Table', 'HorizontalRule', 'SpecialChar', 'PageBreak']},
                {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
                {name: 'document', items: ['Preview', '-', 'Templates']},
                '/',
                {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']},
                {name: 'basicstyles', items: ['Bold', 'Italic']},
                {name: 'styles', items: ['Format', 'FontSize']},
                {name: 'colors', items: ['TextColor', 'BGColor']},
            ]});
		
		CKEDITOR.replace('IncidentInvestigationReasonForIncidence', {toolbar: [
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

        CKEDITOR.replace('IncidentInvestigationImmediateActionTaken', {toolbar: [
               ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
                {name: 'insert', items: ['Table', 'HorizontalRule', 'SpecialChar', 'PageBreak']},
                {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
                {name: 'document', items: ['Preview', '-', 'Templates']},
                '/',
                {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']},
                {name: 'basicstyles', items: ['Bold', 'Italic']},
                {name: 'styles', items: ['Format', 'FontSize']},
                {name: 'colors', items: ['TextColor', 'BGColor']},
            ]});
		
		CKEDITOR.replace('IncidentInvestigationRiskAssessment', {toolbar: [
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

        CKEDITOR.replace('IncidentInvestigationActionTaken', {toolbar: [
               ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
                {name: 'insert', items: ['Table', 'HorizontalRule', 'SpecialChar', 'PageBreak']},
                {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
                {name: 'document', items: ['Preview', '-', 'Templates']},
                '/',
                {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']},
                {name: 'basicstyles', items: ['Bold', 'Italic']},
                {name: 'styles', items: ['Format', 'FontSize']},
                {name: 'colors', items: ['TextColor', 'BGColor']},
            ]});


    </script>

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
			$( "#investigation_tab" ).tabs();
    		$("#submit-indicator").hide();
        	$('#IncidentInvestigationAddAjaxForm').validate();        
    });
</script>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
