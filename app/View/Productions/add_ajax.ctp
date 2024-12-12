<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<style type="text/css">
    .chosen-container{min-width: 100% !important}
</style>
<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'data[Production][branch_id]' ||
                $(element).attr('name') == 'data[Production][employee_id]' ||
                $(element).attr('name') == 'data[Production][production_weekly_plan_id]' ||
                $(element).attr('name') == 'data[Production][product_id]') {
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

    $().ready(function () {
        $("#ProductionProductionDate").attr('disabled',true);
        $("#submit-indicator").hide();
        jQuery.validator.addMethod("greaterThanZero", function (value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");
        $('#ProductionAddAjaxForm').validate({
            rules: {
                "data[Production][branch_id]": {
                    greaterThanZero: true,
                },
                "data[Production][employee_id]": {
                    greaterThanZero: true,
                },
                "data[Production][product_id]": {
                    greaterThanZero: true,
                },"data[Production][production_weekly_plan_id]": {
                    greaterThanZero: true,
                },
            }
        });
        $('#ProductionBranchId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ProductionEmployeeId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ProductionProductId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ProductionProductionWeeklyPlanId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        
        $('#ProductionBatchNumber').blur(function() {
            $("#getBatch").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params["controller"] ?>/get_batch/' + encodeURIComponent(this.value), function(response, status, xhr) {
                if (response != '') {
                    $('#ProductionBatchNumber').val('');
                    $('#ProductionBatchNumber').addClass('error');
                    $("#getBatch").val(response);
                } else {
                    $('#ProductionBatchNumber').removeClass('error');
                }
            });
        });

        $("#ProductionProductId").change(function(){
            $.get("<?php echo Router::url('/', true); ?>productions/get_plan/product_id:" + $("#ProductionProductId").val(), function(data) {
                $("#ProductionProductionWeeklyPlanId").html(data).trigger("chosen:updated");                 
            });

            $("#stocks").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params["controller"] ?>/get_stocks/product_id:' + $("#ProductionProductId").val(), function(response, status, xhr) {                
            });
            $("#ProductionActualProductionNumber").val('0');
            $("#hisory").html('');
            $("#stocks").html('');
        });

        $("#ProductionProductionWeeklyPlanId").change(function(){
            // alert('a');
            $("#submit_id").attr('disabled',false);
            $("#hisory").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params["controller"] ?>/get_history/product_id:' + $("#ProductionProductId").val()+'/week_id:'+$("#ProductionProductionWeeklyPlanId").val(), function(response, status, xhr) {
                // alert(response);
                    // if (response != '') {
                    //     $('#ProductionBatchNumber').val('');
                    //     $('#ProductionBatchNumber').addClass('error');
                    //     $("#getBatch").val(response);
                    // } else {
                    //     $('#ProductionBatchNumber').removeClass('error');
                    // }
            });
            $("#ProductionActualProductionNumber").val('0');

        });

        <?php if($this->request->params['named']['product_id']){ ?>
            // $.get("<?php echo Router::url('/', true); ?>productions/get_plan/product_id:" + $("#ProductionProductId").val(), function(data) {
            //     $("#ProductionProductionWeeklyPlanId").html(data).trigger("chosen:updated");
            //     $("#ProductionProductionWeeklyPlanId").prop("selectedValue", "<?php echo $this->request->params['named']['production_weekly_plan_id'];?>");
            // });

            // $("#stocks").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params["controller"] ?>/get_stocks/product_id:' + $("#ProductionProductId").val(), function(response, status, xhr) {                
            // });            
            <?php if($this->request->params['named']['production_weekly_plan_id']){ ?>
                // $("#ProductionProductionWeeklyPlanId").selected("<?php echo $this->request->params['named']['production_weekly_plan_id'];?>");
                // $("#ProductionProductionWeeklyPlanId").prop('selectedValue',1);

                $("#hisory").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params["controller"] ?>/get_history/product_id:' + $("#ProductionProductId").val()+'/week_id:'+$("#ProductionProductionWeeklyPlanId").val(), function(response, status, xhr) {               
                });
            <?php } ?>
        <?php } ?>

        $("#ProductionActualProductionNumber").change(function(){
            var actual_production_number = $("#ProductionActualProductionNumber").val();
            var balance = $("#ProductionBalance").val();
            var planned = $("#ProductionProductionPlanned").val();
            if(Number(balance) < Number(actual_production_number)){
                alert('Acutual production can not be more than balance!');
                $("#ProductionActualProductionNumber").val(0);
                $("#ProductionActualProductionNumber").focus();
            }
        });
    });
</script>

<div id="productions_ajax">
    <?php echo $this->Session->flash(); ?><div class="nav">
        <div class="productions form col-md-12">
            <h4><?php echo __('Add Production Batch'); ?></h4>
            <?php echo $this->Form->create('Production', array('role' => 'form', 'class' => 'form', 'default' => false)); ?>
            <?php
                $i = 0;
                // $batch = $batch  + 1;
                // Configure::write('debug',1);
                // debug($products);
                ?>
                    <div class="row">
                        <div class="col-md-4">
                            <?php echo $this->Form->input('batch_number',array('default'=>$batch,'label'=>'Batch#')); ?>
                            <label id="getBatch" class="error" style="clear:both" ></label>
                        </div>
                        <div class="col-md-4"><?php echo $this->Form->input('product_id',array('default'=>$this->request->params['named']['product_id'])); ?></div>
                        <div class="col-md-4"><?php echo $this->Form->input('production_weekly_plan_id',array('default'=>$this->request->params['named']['production_weekly_plan_id'])); ?></div>
                    </div>    
                    <div class="row">
                        <div class="col-md-4"><?php echo $this->Form->input('current_status',array('legend'=>'Plan Status','options'=>$currentStatus, 'type'=>'radio',  'default'=>0)); ?></div>
                        <div class="col-md-4"><?php echo $this->Form->input('production_date',array('default'=>date('yyyy-MM-dd'))); ?></div>
                        <div class="col-md-4"><?php echo $this->Form->input('actual_production_number',array('label'=>'# Actual Production', 'default'=>0)); ?></div>
                        
                        <div class="col-md-12 text-danger"><p><br /><strong>Note: </strong>Once <b>"Plan status"</b> is set at <b>"completed"</b> you will no longer be able to add production batch under that plan.</p></div>
                        <div class="col-md-12">
                            <div id="hisory"></div>
                        </div>
                        <div><div id="stocks"></div></div>
                        <div class="col-md-6"><?php echo $this->Form->input('branch_id',array('type'=>'select','default'=>$this->Session->read('User.branch_id'), 'options'=>$PublishedBranchList)); ?></div>
                        <div class="col-md-6"><?php echo $this->Form->input('employee_id', array('label' => 'Supervisor','type'=>'select','options'=>$PublishedEmployeeList)); ?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><?php echo $this->Form->input('details'); ?></div>
                        <div class="col-md-6"><?php echo $this->Form->input('remarks', array('label' => 'Any other Remarks')); ?></div>

                        <?php echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id'))); ?>
                        <?php echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); ?>
                    </div>    
                    <?php
                        // $i++ ;
                        // $batch++; 
                    ?>  
                
            <?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish');
                }
            ?>
            <?php echo $this->Form->submit('Submit', array('div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#productions_ajax', 'async' => 'false','id'=>'submit_id')); ?>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
            <?php echo $this->Form->end(); ?>
            <?php echo $this->Js->writeBuffer(); ?>
        </div>



        <div class="col-md-12">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
</div>
<script> 
    // $("[name*='date']").datepicker({
    //   changeMonth: true,
    //   changeYear: true,
    //   dateFormat:'yy-mm-dd',
    //   // startDate : '06/15/2017'
    // }); 
</script>
<script>
    $.ajaxSetup({
        beforeSend: function () {
            $("#busy-indicator").show();
        },
        complete: function () {
            $("#busy-indicator").hide();
        }
    });
</script>
