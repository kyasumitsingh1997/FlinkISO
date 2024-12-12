<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<script>
$.validator.setDefaults({
    ignore: null,
    errorPlacement: function (error, element) {
        if ($(element).attr('name') == 'data[CapaInvestigation][employee_id]') {
            $(element).next().after(error);
        } else if ($(element).attr('name') == 'data[CapaInvestigation][corrective_preventive_action_id]') {
            $(element).next().after(error);
        } else {
            $(element).after(error);
        }
    },
    submitHandler: function (form) {
        $(form).ajaxSubmit({
            url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax",
            type: 'POST',
            target: '#capaInvestigations_ajax',
            beforeSend: function(){
             $("#submit_id").prop("disabled",true);
             $("#submit-indicator-investigation").show();
             // $('#investigationModal').modal('hide');
         },
         complete: function() {
             $("#submit_id").removeAttr("disabled");
             $("#submit-indicator-investigation").hide();
         },
         error: function (request, status, error) {
                    //alert(request.responseText);
                    alert('Action failed!');
                }
            });
    }
});
$().ready(function () {
    $("#submit-indicator-investigation").hide();
    $('.chosen-select').chosen();
    jQuery.validator.addMethod("greaterThanZero", function (value, element) {
        return this.optional(element) || (parseFloat(value) > 0);
    }, "Please select the value");

    $('#CapaInvestigationAddAjaxForm').validate({
        rules: {
            "data[CapaInvestigation][employee_id]": {
                greaterThanZero: true,
            },
            "data[CapaInvestigation][corrective_preventive_action_id]": {
                greaterThanZero: true,
            },

        }
    });

    $('#CapaInvestigationEmployeeId').change(function () {
        if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
            $(this).next().next('label').remove();
        }
    });
    $('#CapaInvestigationCorrectivePreventiveActionId').change(function () {
        if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
            $(this).next().next('label').remove();
        }
    });
    
});
</script>
<div id="capaInvestigations_ajax">
    <?php echo $this->Session->flash();?><div class="nav">
    <?php
    if($modal != 1) { ?>    
    <div class="capaInvestigations form col-md-8">
        <h4>Add Capa Investigation</h4>
        <?php } else { ?>
        <div class="row">
            <div class="col-md-12">
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
            </div>



            <div class="col-md-12">
                <table cellpadding="0" cellspacing="0" class="table table-bordered">
                    <tr>
                        <th><?php echo $this->Paginator->sort('corrective_preventive_action_id'); ?></th>
                        <th><?php echo $this->Paginator->sort('employee_id'); ?></th>
                        <th><?php echo $this->Paginator->sort('target_date'); ?></th>
                        <th><?php echo $this->Paginator->sort('completed_on_date'); ?></th>
                        <th><?php echo $this->Paginator->sort('publish'); ?></th>       

                        
                    </tr>
                    <?php if($capaInvestigations){ ?>
                    <?php foreach ($capaInvestigations as $capaInvestigation): ?>

                    <?php if($capaInvestigation['CapaInvestigation']['current_status'] == 0){ ?>
                    <tr class="text-danger on_page_src">
                        <?php } else{ ?>
                        <tr class="on_page_src"> <?php } ?>                    
                        <td><?php echo h($capaInvestigation['CapaInvestigation']['details']); ?>&nbsp;</td>
                        <td>
                            <?php echo $this->Html->link($capaInvestigation['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $capaInvestigation['Employee']['id'])); ?>
                        </td>
                        <td><?php echo h($capaInvestigation['CapaInvestigation']['target_date']); ?>&nbsp;</td>
                        <td><?php if($capaInvestigation['CapaInvestigation']['current_status'] != 0)echo h($capaInvestigation['CapaInvestigation']['completed_on_date']); ?>&nbsp;</td>
                        <td width="60">
                            <?php if($capaInvestigation['CapaInvestigation']['publish'] == 1) { ?>
                            <span class="fa fa-check"></span>
                            <?php } else { ?>
                            <span class="fa fa-ban"></span>
                            <?php } ?>&nbsp;</td>
                        </tr>
                    <?php endforeach; ?>
                    <?php }else{ ?>
                    <tr><td colspan=72>No results found</td></tr>
                    <?php } ?>
                </table>
            </div>
        </div>
        <div class="capaInvestigations form">

           <?php } ?>
           <?php echo $this->Form->create('CapaInvestigation',array('role'=>'form','class'=>'form','default'=>false)); ?>
           <div class="row">
              <fieldset>
                 <?php
                 echo "<div class='col-md-6'>".$this->Form->input('corrective_preventive_action_id',array('options' => $correctivePreventiveActionIds,'value'=>$capaId)) . '</div>'; 
                 echo "<div class='col-md-6'>".$this->Form->input('employee_id', array('options' => $employeeIds, 'label'=>'Assign CAPA To')) . '</div>'; 
                 echo "<div class='col-md-6'>".$this->Form->input('details', array('value'=>$correctivePreventiveActionDetails['CorrectivePreventiveAction']['initial_remarks'])) . '</div>'; 


                 echo "<div class='col-md-6'>".$this->Form->input('proposed_action',array('value'=>$correctivePreventiveActionDetails['CorrectivePreventiveAction']['proposed_immidiate_action'])) . '</div>'; 
                 echo "<div class='col-md-6'>".$this->Form->input('target_date',array()) . '</div>'; 
                 echo "<div class='col-md-6 hide'>".$this->Form->input('completed_on_date',array()) . '</div>'; 
                 echo "<div class='col-md-6 hide'>".$this->Form->input('investigation_report',array()) . '</div>'; 
                 echo "<div class='col-md-6'>"."<label>" . __('Current Status') . "</label>";
                 echo $this->Form->input('current_status', array('value' => '0', 'label' => false, 'legend' => false,  'div' => false, 'options' => array('0' => 'Open', '1' => 'Close'), 'type' => 'radio', 'style' => 'float:none','onclick' => 'currentStatus()')). '</div>'; 
                 ?>
             </fieldset>
             <?php
             echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
             echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
             echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
             ?>
         </div>
             <?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                }else{
                    echo $this->Form->input('publish', array('label' => __('Publish')));
                }?>
                <?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#capaInvestigations_ajax','async' => 'false')); ?>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator-investigation')); ?>
                <?php echo $this->Form->end(); ?>
                <?php echo $this->Js->writeBuffer();?>
      </div>
      </div><style>
      #ui-datepicker-div{z-index:1999 !important}
      </style>
      <script>
  $("[name*='date']").datepicker({
    changeMonth: true,
    changeYear: true,
    format: 'yyyy-mm-dd',
    autoclose:true,
    startDate : '<?php echo date("Y-m-d",strtotime($correctivePreventiveAction["CorrectivePreventiveAction"]["created"]));?>'
});
  </script>
<?php if($modal != 1) { ?>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
<?php } ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
