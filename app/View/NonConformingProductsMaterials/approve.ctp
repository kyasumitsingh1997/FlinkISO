<?php echo $this->Html->script(array('jquery.validate.min','jquery-form.min'));?>
<?php echo $this->fetch('script');?>

<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'data[NonConformingProductsMaterial][product_id]' ||
                $(element).attr('name') == 'data[NonConformingProductsMaterial][material_id]') {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
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
        jQuery.validator.addMethod("greaterThanZero", function (value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");

        $('#NonConformingProductsMaterialApproveForm').validate();
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
             if($('#NonConformingProductsMaterialApproveForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                 $("#NonConformingProductsMaterialApproveForm").submit();
             }
        });
        if ($('#NonConformingProductsMaterialMaterialId').val() == '-1') {
            $("#material").hide();
        }
        if ($('#NonConformingProductsMaterialProductId').val() == '-1') {
            $("#product").hide();
        }
        
<?php if(count($correctiveActions)){ $corrective_checked = "checked";?>
 $('#capa_corrective').removeClass('hide');
<?php } ?>
<?php if(count($preventiveActions)){  $preventive_checked = "checked";?>  
$('#capa_preventive').removeClass('hide'); <?php } ?>
    });
    $("#material").hide();
    $("#product").hide();
    $("#procedure").hide();
    $("#process").hide();
    $("#risk").hide();
</script>
<?php
    if($this->request->data['NonConformingProductsMaterial']['type'] == 1){
        $default = 1; 
        $val = $this->request->params['pass'][0];
        ?>
        <script>
        $().ready(function(){
            $("#material").show();
            $("#procedure").hide();
            $("#product").hide();
            $("#risk").hide();
            $("#process").hide();
        });
        </script>
<?php }elseif($this->request->data['NonConformingProductsMaterial']['type'] == 0){
        $default = 0; 
        $val = $this->request->params['pass'][0];
        ?>
        <script>
        $().ready(function(){
            $("#material").hide();
            $("#risk").hide();
            $("#procedure").hide();
            $("#process").hide();
            $("#product").show();
        });
        </script>
<?php }elseif($this->request->data['NonConformingProductsMaterial']['type'] == 2){
        $default = 0; 
        $val = $this->request->params['pass'][0];
        ?>
        <script>
        $().ready(function(){
            $("#material").hide();            
            $("#risk").hide();
            $("#product").hide();
            $("#process").hide();
            $("#procedure").show();
        });
        </script>
<?php }elseif($this->request->data['NonConformingProductsMaterial']['type'] == 3){        
        $default = 0; 
        $val = $this->request->params['pass'][0];
        ?>
        <script>
        $().ready(function(){
            $("#material").hide();            
            $("#risk").hide();
            $("#product").hide();
            $("#procedure").hide();
            $("#process").show();
        });
        </script>
<?php }elseif($this->request->data['NonConformingProductsMaterial']['type'] == 4){
        $default = 0; 
        $val = $this->request->params['pass'][0];
        ?>
        <script>
        $().ready(function(){
            $("#material").hide();                        
            $("#product").hide();
            $("#procedure").hide();
            $("#process").hide();
            $("#risk").show();
        });
        </script>
<?php }
?>

<div id="nonConformingProductsMaterials_ajax">
    <?php echo $this->Session->flash();?>
    <div class="nav panel panel-default">
        <div class="nonConformingProductsMaterials form col-md-8">
            <h4><?php echo __('Approve Non Conforming Report'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>
            </h4>
            <?php echo $this->Form->create('NonConformingProductsMaterial', array('role' => 'form', 'class' => 'form')); ?>
            <?php echo $this->Form->input('id'); ?>
            <div class="row">
                <div class="col-md-12"><?php echo $this->Form->input('title'); ?></div>                
            </div>
            <div class="row">
                <div class="col-md-12"><?php echo $this->Form->input('type', array('options' => array('Product', 'Material','Procedure','Process','Risk'), 'type' => 'radio', 'onClick' => 'shhd(this.value)', 'class' => 'checkbox-2', 'legend' => __('Select Source'),'default'=>0)); ?></div>

                <div class="col-md-12 hidediv"  id="material"><?php echo $this->Form->input('material_id', array('style' => 'width:100%')); ?></div>
                
                <div class="col-md-12 hidediv" id="product"><?php echo $this->Form->input('product_id', array('style' => 'width:100%')); ?></div>
                
                <div class="col-md-12 hidediv" id="procedure"><?php echo $this->Form->input('procedure_id', array('style' => 'width:100%')); ?></div>

                <div class="col-md-12 hidediv" id="process"><?php echo $this->Form->input('process_id', array('style' => 'width:100%')); ?></div>

                <div class="col-md-12 hidediv" id="risk"><?php echo $this->Form->input('risk_assessment_id', array('style' => 'width:100%')); ?></div>
            </div>
            <div class="row">
                <div class="col-md-6"><?php echo $this->Form->input('non_confirmity_date', array('label' => __('Date'))); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('violation_of_section'); ?></div>
                <div class="col-md-6">  <?php echo $this->Form->input('department_id', array('style' => 'width:100%','label' => __('Department'), 'options' => $PublishedDepartmentList )); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('reported_by',array('options'=>$PublishedEmployeeList,'label' =>'Recorded By ')); ?></div>
                <div class="col-md-12"><?php echo $this->Form->input('details'); ?></div>
                <div class="col-md-4"><?php echo $this->Form->input('status',array('options'=>array('0'=>'Open','1'=>'Close'),'type' => 'radio','default'=>0)); ?></div>
                <!--<div class="col-md-4"><?php echo $this->Form->input('add_corrective_action',array('type'=> 'checkbox','checked'=>$corrective_checked)); ?></div>
                <div class="col-md-4"><?php echo $this->Form->input('add_preventive_action',array('type'=> 'checkbox','checked'=>$preventive_checked)); ?></div> -->                
            </div>
            <?php if($corrective_checked == 'checked') { ?> 
                <div class="row">
                    <div class="col-md-12">
                    <!--- corrrective action started -->
                        <?php echo $this->element('nccapa',array('capa'=>$correctiveActions,'h2'=>'Associated Corrective Action')); ?>
                    <!-- corrective action ended -->
                    </div>
                </div>
            <?php } ?>
            <?php if($preventive_checked == 'checked') { ?> 
                <div class="row">
                    <div class="col-md-12">
                    <!--- corrrective action started -->
                        <?php echo $this->element('nccapa',array('capa'=>$preventiveActions,'h2'=>'Associated Preventive Action')); ?>
                    <!-- corrective action ended -->
                    </div>
                </div>
            <?php } ?>

                <?php echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id'))); ?>
                <?php echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); ?>
                <?php echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id'])); ?>
            
                <?php
                    if ($showApprovals && $showApprovals['show_panel'] == true) {
                        echo $this->element('approval_form');
                    } else {
                        echo $this->Form->input('publish');
                    }
                ?>
                <?php 
                    echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
                    echo $this->Form->submit('Submit', array('div' => false, 'class' => 'btn btn-primary btn-success' ,'id'=>'submit_id')); ?>
                    <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
                    <?php echo $this->Form->end(); ?>
                    <?php echo $this->Js->writeBuffer();
                ?>
                </div>
        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
</div>
<script>     
    
    $("#NonConformingProductsMaterialNonConfirmityDate").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-smm-dd',
      autoclose:true,
        'showTimepicker': false,
    }).attr('readonly', 'readonly');
    
        $("#CorrectiveCapaInvestigationTargetDate").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
       
    }).attr('readonly', 'readonly');
   
        $("#PreventiveCapaInvestigationTargetDate").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
    }).attr('readonly', 'readonly');

</script>
<?php $this->Js->get('#list'); ?>
<?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#nonConformingProductsMaterials_ajax'))); ?>
<?php echo $this->Js->writeBuffer(); ?>
