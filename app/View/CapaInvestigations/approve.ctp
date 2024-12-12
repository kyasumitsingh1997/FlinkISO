 <div id="capaInvestigations_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="capaInvestigations form col-md-8">
<h4><?php echo __('Approve Capa Investigation'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
        <h3><?php echo __('CAPA Details');?></h3>
                    <table class="table table-responsive">
                        <tr><td width="20%"><?php echo __('CAPA Name'); ?></td>
                            <td>
                                <?php echo $correctivePreventiveAction['CorrectivePreventiveAction']['name']; ?>
                                &nbsp;
                            </td>
                        </tr>
                        <tr><td><?php echo __('CAPA Number'); ?></td>
                            <td>
                                <?php echo $correctivePreventiveAction['CorrectivePreventiveAction']['number']; ?>
                                &nbsp;
                            </td>
                        </tr>
                        <tr><td><?php echo __('CAPA Source'); ?></td>
                            <td>
                                <?php echo $this->Html->link($correctivePreventiveAction['CapaSource']['name'], array('controller' => 'capa_sources', 'action' => 'view', $correctivePreventiveAction['CapaSource']['id'])); ?>
                                &nbsp;
                            </td>
                        </tr>
                        <tr><td><?php echo __('CAPA Category'); ?></td>
                            <td>
                                <?php echo $this->Html->link($correctivePreventiveAction['CapaCategory']['name'], array('controller' => 'capa_categories', 'action' => 'view', $correctivePreventiveAction['CapaCategory']['id'])); ?>
                                &nbsp;
                            </td>
                        </tr>
                        <tr><td><?php echo __('Details'); ?></td>
                            <td>
                                <?php
                                    if ($correctivePreventiveAction['CorrectivePreventiveAction']['internal_audit_id']) {
                                        echo "Internal Audit :" . $correctivePreventiveAction['InternalAudit'][''];
                                    } 

                                    if ($correctivePreventiveAction['CorrectivePreventiveAction']['suggestion_form_id']) {
                                        echo "Suggestions :" .  $correctivePreventiveAction['SuggestionForm']['title'];
                                    } 

                                    if ($correctivePreventiveAction['CorrectivePreventiveAction']['customer_complaint_id']) {
                                        echo "Customer Complaints :" .  $correctivePreventiveAction['CustomerComplaint']['name'];
                                    } 

                                    if ($correctivePreventiveAction['CorrectivePreventiveAction']['supplier_registration_id']) {
                                        echo "Suppliers :" .  $correctivePreventiveAction['SupplierRegistration']['title'];
                                    } 

                                    if ($correctivePreventiveAction['CorrectivePreventiveAction']['product_id']) {
                                        echo "Product :" .  $correctivePreventiveAction['Product']['name'];
                                    } 

                                    if ($correctivePreventiveAction['CorrectivePreventiveAction']['device_id']) {
                                        echo "Device :" .  $correctivePreventiveAction['Device']['name'];
                                    } 

                                    if ($correctivePreventiveAction['CorrectivePreventiveAction']['material_id']) {
                                        echo "Material :" .  $correctivePreventiveAction['Material']['name'];
                                    } 

                                    if ($correctivePreventiveAction['CorrectivePreventiveAction']['process_id']) {
                                        echo "Process :" .  $correctivePreventiveAction['Process']['title'];
                                    }

                                    if ($correctivePreventiveAction['CorrectivePreventiveAction']['risk_assessment_id']) {
                                        echo "Risk :" .  $correctivePreventiveAction['RiskAssessment']['title'];
                                    }
                                ?>&nbsp;
                            </td>
                        </tr>
                        <tr><td><?php echo __('Raised By'); ?></td>
                            <td>
                                <?php $sorce = json_decode($correctivePreventiveAction['CorrectivePreventiveAction']['raised_by'], true); ?>&nbsp;
                                <?php echo $this->Html->link($sorce['Soruce'], array('controller' => str_replace(' ', '_', Inflector::pluralize($sorce['Soruce'])), 'action' => 'view', $sorce['id'])); ?>
                                &nbsp;
                            </td>
                        </tr>
                     
                        <tr><td><?php echo __('Initial Remarks'); ?></td>
                            <td>
                                <?php echo h($correctivePreventiveAction['CorrectivePreventiveAction']['initial_remarks']); ?>
                                &nbsp;
                            </td>
                        </tr>
                        <tr><td><?php echo __('Proposed Immediate Action'); ?></td>
                            <td>
                                <?php echo h($correctivePreventiveAction['CorrectivePreventiveAction']['proposed_immidiate_action']); ?>
                                &nbsp;
                            </td>
                        </tr>
                      
                        <tr><td><?php echo __('Root Cause Analysis Required'); ?></td>
                            <td>
                                <?php echo h($correctivePreventiveAction['CorrectivePreventiveAction']['root_cause_analysis_required']) ? __('Yes') : __('No'); ?>&nbsp;
                            </td>
                        </tr>
                      
                        <tr><td><?php echo __('Current Status'); ?></td>
                            <td>
                                <?php echo $correctivePreventiveAction['CorrectivePreventiveAction']['current_status'] ? __('Close') : __('Open'); ?>
                                &nbsp;
                            </td>
                        </tr>

                       
                        <tr><td><?php echo __('Document Changes Required'); ?></td>
                            <td>
                                <?php
                        if($correctivePreventiveAction['CorrectivePreventiveAction']['document_changes_required'] == 1) {
                        $docChangeReq = 'Yes';
                        echo $docChangeReq;
                        } else {
                        $docChangeReq = 'No';
                        echo $docChangeReq;
                        }
                    ?>&nbsp;
                            </td>
                        </tr>
                <?php if($docChangeReq == 'Yes') { ?>
                        <tr><td><?php echo __('Master List of Format'); ?></td>
                            <td>
                                <?php echo h($changeRequiredIn['MasterListOfFormat']['title']); ?>&nbsp;
                            </td>
                        </tr>
                       
                     
                <?php }?>
                           <tr><td><?php echo __('Closure Remarks'); ?></td>
                            <td>
                                <?php echo h($correctivePreventiveAction['CorrectivePreventiveAction']['closure_remarks']); ?>&nbsp;
                            </td>
                        </tr>
                        <tr><td><?php echo __('Prepared By'); ?></td>
                            <td>
                                <?php echo h($correctivePreventiveAction['PreparedBy']['name']);
                                ?>&nbsp;
                            </td>
                        </tr>
                        <tr><td><?php echo __('Approved By'); ?></td>
                            <td>
                                <?php echo h($correctivePreventiveAction['ApprovedBy']['name']);
                                ?>&nbsp;
                            </td>
                        </tr>
                        <tr><td><?php echo __('Publish'); ?></td>
                            <td>
                                <?php if ($correctivePreventiveAction['CorrectivePreventiveAction']['publish'] == 1) { ?>
                                    <span class="fa fa-check"></span>
                                <?php } else { ?>
                                    <span class="fa fa-ban"></span>
                                <?php } ?>&nbsp;</td>&nbsp;
                        </tr>
                    </table>
<?php echo $this->Form->create('CapaInvestigation',array('role'=>'form','class'=>'form')); ?>
<div class="row">
			<?php
            if($this->request->data['CapaInvestigation']['created_by'] != $this->Session->read('User.id')){
                $disabled = 'disabled';
            }else{
                $disabled = '';
            }
		echo "<div class='col-md-6'>".$this->Form->input('corrective_preventive_action_id',array('disabled', 'options' => $correctivePreventiveActionIds)) . '</div>'; 
                echo "<div class='col-md-6'>".$this->Form->input('employee_id', array($disabled, 'options' => $employeeIds)) . '</div>'; 
          
                if($this->data['CapaInvestigation']['created_by'] != $this->Session->read('User.id')){
                    echo "<div class='col-md-6'>".$this->Form->input('details', array('readonly'=>'readonly')) . '</div>'; 
                    echo "<div class='col-md-6'>".$this->Form->input('proposed_action', array('readonly'=>'readonly')) . '</div>'; 
                    echo "<div class='col-md-6'><br /><h4>Target Date :". date('Y-m-d',strtotime($this->request->data['CapaInvestigation']['target_date'])) . '</h4></div>'; 
                }else{
                     echo "<div class='col-md-6'>".$this->Form->input('details') . '</div>'; 
                    echo "<div class='col-md-6'>".$this->Form->input('proposed_action') . '</div>'; 
                    echo "<div class='col-md-6'>".$this->Form->input('target_date') . '</div>'; 
                    
                }	
		echo "<div class='col-md-12'>".$this->Form->input('investigation_report',array()) . '</div>'; 
        if($this->request->data['CapaInvestigation']['completed_on_date'] == '1970-01-01')$this->request->data['CapaInvestigation']['completed_on_date'] = null;
        echo "<div class='col-md-6'>".$this->Form->input('completed_on_date') . '</div>'; 
        echo "<div class='col-md-6'><br />"."<label>" . __('Current Status') . "</label>";
		echo $this->Form->input('current_status', array('value' => '0', 'label' => false, 'legend' => false,  'div' => false, 'options' => array('0' => 'Open', '1' => 'Close'), 'type' => 'radio', 'style' => 'float:none','onclick' => 'currentStatus()')). '</div>'; 
	?>
<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
		echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
		echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
		echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
		?>

</div>
<div class="">
		

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
      startDate : '<?php echo date("Y-m-d",strtotime($correctivePreventiveAction["CorrectivePreventiveAction"]["created"]));?>'
    }); </script>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#capaInvestigations_ajax')));?>

<?php echo $this->Js->writeBuffer();?>
</div>
<script>
    $.validator.setDefaults({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/approve/<?php echo $this->request->params['pass'][0] ?>/<?php echo $this->request->params['pass'][1] ?>",
                type: 'POST',
                target: '#capaInvestigations_ajax',
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
        $('#CapaInvestigationApproveForm').validate();        
    });
</script>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
