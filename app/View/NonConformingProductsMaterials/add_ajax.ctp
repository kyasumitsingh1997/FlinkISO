<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>

<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'data[NonConformingProductsMaterial][product_id]' ||
                $(element).attr('name') == 'data[NonConformingProductsMaterial][procedure_id]' || 
                $(element).attr('name') == 'data[NonConformingProductsMaterial][process_id]' || 
                $(element).attr('name') == 'data[NonConformingProductsMaterial][risk_assessment_id]' || 
                $(element).attr('name') == 'data[NonConformingProductsMaterial][material_id]') {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
        submitHandler: function (form) {
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
                error: function (request, status, error) {
                    //alert(request.responseText);
                    alert('Action failed!');
                }
            });
        }
    });

    function shhd(chk) {
        if (chk == 0) {
            $("#material").hide();
            $("#procedure").hide();
            $("#process").hide();
            $("#risk").hide();
            $("#product").show();
            $("#NonConformingProductsMaterialProductId_chosen").width('100%');
            $('#NonConformingProductsMaterialMaterialId').rules('remove');
            $('#NonConformingProductsMaterialProcedureId').rules('remove');
            
            if ($('#NonConformingProductsMaterialMaterialId').next().next('label').hasClass("error")) {
                $('#NonConformingProductsMaterialMaterialId').next().next('label').remove();
            }

            $('#NonConformingProductsMaterialProductId').rules('add', {
                greaterThanZero: true
            });
        } else if (chk == 1) {
            $("#material").show();
            $("#product").hide();
            $("#process").hide();
            $("#risk").hide();
            $("#NonConformingProductsMaterialMaterialId_chosen").width('100%');
            $('#NonConformingProductsMaterialProductId').rules('remove');
            $('#NonConformingProductsMaterialProcedureId').rules('remove');
            if ($('#NonConformingProductsMaterialProductId').next().next('label').hasClass("error")) {
                $('#NonConformingProductsMaterialProductId').next().next('label').remove();
            }
            $('#NonConformingProductsMaterialMaterialId').rules('add', {
                greaterThanZero: true
            });
        } else if (chk == 2) {
            $("#material").hide();
            $("#product").hide();
            $("#process").hide();
            $("#risk").hide();
            $("#procedure").show();
            $("#NonConformingProductsMaterialProcedureId_chosen").width('100%');
            $('#NonConformingProductsMaterialProductId').rules('remove');
            $('#NonConformingProductsMaterialMaterialId').rules('remove');
            if ($('#NonConformingProductsMaterialProcedureId').next().next('label').hasClass("error")) {
                $('#NonConformingProductsMaterialProcedureId').next().next('label').remove();
            }
            $('#NonConformingProductsMaterialProcedureId').rules('add', {
                greaterThanZero: true
            });
        } else if (chk == 3) {
            $("#process").show();
            $("#material").hide();
            $("#product").hide();
            $("#risk").hide();
            $("#procedure").hide();
            $("#NonConformingProductsMaterialProcessId_chosen").width('100%');
            $('#NonConformingProductsMaterialProductId').rules('remove');
            $('#NonConformingProductsMaterialMaterialId').rules('remove');
            if ($('#NonConformingProductsMaterialProcessId').next().next('label').hasClass("error")) {
                $('#NonConformingProductsMaterialProcessId').next().next('label').remove();
            }
            $('#NonConformingProductsMaterialProcessId').rules('add', {
                greaterThanZero: true
            });
        } else if (chk == 4) {
            $("#process").hide();
            $("#material").hide();
            $("#product").hide();
            $("#risk").show();
            $("#procedure").hide();
            $("#NonConformingProductsMaterialRiskAssessmentId_chosen").width('100%');
            $('#NonConformingProductsMaterialRiskAssessmentId').rules('remove');
            $('#NonConformingProductsMaterialRiskAssessmentId').rules('remove');
            if ($('#NonConformingProductsMaterialProcedureId').next().next('label').hasClass("error")) {
                $('#NonConformingProductsMaterialProcedureId').next().next('label').remove();
            }
            $('#NonConformingProductsMaterialRiskAssessmentId').rules('add', {
                greaterThanZero: true
            });
        }
    }
  
    $().ready(function () {
        $('#NonConformingProductsMaterialAddCorrectiveAction').on('change',function(){
            if(this.checked){
                $('#capa_corrective').removeClass('hide');
            }else{
                $('#capa_corrective').addClass('hide');
            }
        });
          $('#NonConformingProductsMaterialAddPreventiveAction').on('change',function(){
            if(this.checked){
                $('#capa_preventive').removeClass('hide');
            }else{
                $('#capa_preventive').addClass('hide');
            }
        });
        $("#submit-indicator").hide();
        jQuery.validator.addMethod("greaterThanZero", function (value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");

        $('#NonConformingProductsMaterialAddAjaxForm').validate();
        shhd(0);
    });
</script>
<?php
    if($this->request->params['pass'][1] == 'Material'){
        $default = 1; 
        $val = $this->request->params['pass'][0];
        ?>
        <script>
            $("#material").show();
            $("#procedure").hide();
            $("#product").hide();
            $("#risk").hide();
            $("#process").hide();
        </script>
<?php }elseif($this->request->params['pass'][1] == 'Product'){
        $default = 0; 
        $val = $this->request->params['pass'][0];
        ?>
        <script>
            $("#material").hide();
            $("#risk").hide();
            $("#procedure").hide();
            $("#process").hide();
            $("#product").show();
        </script>
<?php }elseif($this->request->params['pass'][2] == 'Procedure'){
        $default = 0; 
        $val = $this->request->params['pass'][0];
        ?>
        <script>
            $("#material").hide();            
            $("#risk").hide();
            $("#product").hide();
            $("#process").hide();
            $("#procedure").show();
        </script>
<?php }elseif($this->request->params['pass'][3] == 'Process'){
        $default = 0; 
        $val = $this->request->params['pass'][0];
        ?>
        <script>
            $("#material").hide();            
            $("#risk").hide();
            $("#product").hide();
            $("#procedure").hide();
            $("#process").show();
        </script>
<?php }elseif($this->request->params['pass'][4] == 'Risk'){
        $default = 0; 
        $val = $this->request->params['pass'][0];
        ?>
        <script>
            $("#material").hide();                        
            $("#product").hide();
            $("#procedure").hide();
            $("#process").hide();
            $("#risk").show();
        </script>
<?php }
?>
<div id="nonConformingProductsMaterials_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav">
        <div class="nonConformingProductsMaterials form col-md-8">
            <h4><?php echo __('Add New NC'); ?></h4>
            <?php echo $this->Form->create('NonConformingProductsMaterial', array('role' => 'form', 'class' => 'form', 'default' => false)); ?>
            <div class="row">
                <div class="col-md-6"><?php echo $this->Form->input('title'); ?></div>
                <div class="col-md-6"><br />
                    <strong>NC Number : <?php echo $nc_number; ?></strong> <small class="label label-info">Auto Generated</small><br />
                    <?php echo $this->Form->hidden('number', array('label' => __('Number'),'value'=>$nc_number)); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12"><?php echo $this->Form->input('type', array('options' => array('Product', 'Material','Procedure','Process','Risk'), 'type' => 'radio', 'onClick' => 'shhd(this.value)', 'class' => 'checkbox-2', 'legend' => __('Select Source'),'default'=>0)); ?></div> 
                
                <div class="col-md-12 hidediv"  id="material"><?php echo $this->Form->input('material_id', array('default'=>$val, 'style' => 'width:100%')); ?></div>
                <div class="col-md-12 hidediv" id="product"><?php echo $this->Form->input('product_id', array('default'=>$val,'style' => 'width:100%')); ?></div>
                <div class="col-md-12 hidediv" id="procedure"><?php echo $this->Form->input('procedure_id', array('default'=>$val, 'style' => 'width:100%')); ?></div>
                <div class="col-md-12 hidediv" id="process"><?php echo $this->Form->input('process', array('default'=>$val, 'style' => 'width:100%')); ?></div>
                <div class="col-md-12 hidediv" id="risk"><?php echo $this->Form->input('risk_assessment_id', array('default'=>$val, 'style' => 'width:100%')); ?></div>
            </div>
            <div class="row">

                <div class="col-md-6"><?php echo $this->Form->input('non_confirmity_date', array('label' => __('Date'))); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('violation_of_section'); ?></div>
                <div class="col-md-6">  <?php echo $this->Form->input('department_id', array('style' => 'width:100%','label' => __('Department'), 'options' => $PublishedDepartmentList )); ?></div>
                  <div class="col-md-6"><?php echo $this->Form->input('reported_by',array('options'=>$PublishedEmployeeList,'label' =>'Recorded By ')); ?></div>
                <div class="col-md-12"><?php echo $this->Form->input('details'); ?></div>
   
                  <div class="col-md-4"><?php echo $this->Form->input('status',array('options'=>array('0'=>'Open','1'=>'Close'),'type' => 'radio','default'=>0)); ?></div>
                <div class="col-md-4"><?php echo $this->Form->input('add_corrective_action',array('type'=> 'checkbox')); ?></div>                
                <div class="col-md-4"><?php echo $this->Form->input('add_preventive_action',array('type'=> 'checkbox')); ?></div>                
            </div>
            <div class="row hide" id="capa_corrective"><br/>
                <div class="col-md-12"><h4><?php echo __('Add Corrective Action'); ?></h4> <hr/></div>
               
              
                <div class="col-md-5"><?php echo $this->Form->input('Corrective.CorrectivePreventiveAction.capa_source_id', array('style' => 'width:100%', 'label' => __('CAPA Source'))); ?></div>
                <div class="col-md-5"><?php echo $this->Form->input('Corrective.CorrectivePreventiveAction.capa_category_id', array('style' => 'width:100%', 'label' => __('CAPA Category'))); ?></div>
                <div class="col-md-2"><?php echo $this->Form->input('Corrective.CapaInvestigation.target_date', array('label' => __('Not later than'))); ?></div>
                
                <div class="col-md-12"><?php echo $this->Form->input('Corrective.CorrectivePreventiveAction.initial_remarks', array('type'=>'textarea','label' => __('Details of investigation of the problem'))); ?></div>
              
                
                 <div class="col-md-12"><?php echo $this->Form->input('Corrective.CapaRootCauseAnalysi.root_cause_details', array('type'=>'textarea','label' => __('What caused the compaint?'))); ?></div>
                
<!--                 <div class="col-md-6"><?php //echo $this->Form->input('CapaRootCauseAnalysis.root_cause_remarks', array('type'=>'textarea','label' => __('How was this conclusion reached?'))); ?></div>-->
                 <div class="col-md-12"><?php echo $this->Form->input('Corrective.CapaRootCauseAnalysi.proposed_action', array('type'=>'textarea','label' => __('What action is needed to resolve this problem?'))); ?></div>
                <?php echo $this->Form->input('Corrective.CorrectivePreventiveAction.capa_type', array('type' => 'hidden', 'value' => 0)); ?>
                  <?php echo $this->Form->input('Corrective.CorrectivePreventiveAction.root_cause_analysis_required', array('type' => 'hidden', 'value' => 1)); ?>
            </div>
            
              <div class="hide" id="capa_preventive"><br/>
                 <div class="col-md-12"><h4><?php echo __('Add Preventive Action'); ?></h4> <hr/></div>
               
               <div class="row">
                <div class="col-md-5"><?php echo $this->Form->input('Preventive.CorrectivePreventiveAction.capa_source_id', array('style' => 'width:100%', 'label' => __('CAPA Source'))); ?></div>
                <div class="col-md-5"><?php echo $this->Form->input('Preventive.CorrectivePreventiveAction.capa_category_id', array('style' => 'width:100%', 'label' => __('CAPA Category'))); ?></div>
           <div class="col-md-12"><?php echo $this->Form->input('Preventive.CorrectivePreventiveAction.initial_remarks', array('type'=>'textarea','label' => __('Did the corrective action permanently resolve this problem?'))); ?></div>
                
                
                <div class="col-md-12"><?php echo $this->Form->input('Preventive.CapaRootCauseAnalysi.proposed_action', array('type'=>'textarea','label' => __('What action is needed to resolve this problem?'))); ?></div>
               </div>
                 <div class="col-md-6"><?php echo $this->Form->input('Preventive.CapaInvestigation.target_date', array('label' => __('Date'))); ?></div>
               
                
                 <div class="col-md-6"><?php echo $this->Form->input('Preventive.CorrectivePreventiveAction.division_id', array('options'=>$divisions,'label' =>'Division')); ?></div>
                <?php echo $this->Form->input('Preventive.CorrectivePreventiveAction.root_cause_analysis_required', array('type' => 'hidden', 'value' => 1)); ?>
            <?php echo $this->Form->input('Preventive.CorrectivePreventiveAction.capa_type', array('type' => 'hidden', 'value' => 1)); ?>
            </div>

                <?php echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id'))); ?>
                <?php echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); ?>
                <?php echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id'])); ?>
            

            <?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish', array('label' => __('Publish')));
                }
            ?>
            <?php echo $this->Form->submit('Submit', array('div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#nonConformingProductsMaterials_ajax', 'async' => 'false','id'=>'submit_id')); ?>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
            <?php echo $this->Form->end(); ?>
            <?php echo $this->Js->writeBuffer(); ?>

        </div>
        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
</div>
<script> 
    $("[name*='date']").datepicker(); 
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>
