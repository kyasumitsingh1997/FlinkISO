<style>
.modal-dialog {width:50%;}
.modal-dialog {width:50%;z-index: 20!important}
.chosen-container, .chosen-container-single, .chosen-select
{min-width: 200px; width:100% !important;}
#ui-datepicker-div,.ui-datepicker,.datepicker{z-index:9999 !important}{z-index: 999999 !important}
.capa-check input[type="checkbox"]{margin-left: 0px !important;position:relative;}
.capa-check .checkbox-inline{padding-left: 0px ;margin-left:0px;}
#submit_id{margin-top: 10px;}
</style>
<script>
    $().ready(function() {
        $('select').chosen();
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
	    $("#submit_id").prop("disabled",true);
	    $("#submit-indicator").show();
	    $("#capa_advanced-search-form").submit();
        });
    });
</script>
<?php
    $postData = null;
    $postData = array('number' => 'Number', 'name' => 'Name','initial_remarks'=>'Initial Remarks','problem_description'=>'Problem Desciption','proposed_immidiate_action'=>'Proposed Immidiate Action','closure_remarks'=>'Closure Remarks');
?>
<div class="modal fade " id="advanced_search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-wide">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo __('Advanced Search'); ?></h4>
            </div>
            <div class="modal-body">
                <?php echo $this->Form->create($this->name, array('action' => 'advanced_search', 'role' => 'form', 'class' => 'advanced-search-form', 'id' => 'capa_advanced-search-form', 'type' => 'get')); ?>
                <div class="row">
                    <div class="col-md-12"><?php echo $this->Form->input('Search.keywords', array('label' => __('Type Keyword & select the field which you want to search from below'))); ?></div>
                    <div class="col-md-12 capa-check"><?php echo $this->Form->input('Search.search_fields', array('label' => false, 'options' => array($postData), 'multiple' => 'checkbox', 'class' => 'checkbox-inline col-md-4')); ?></div>

                </div>
                <div class="col-md-12"><hr /></div>

                <?php
                    $employee = array('action_assigned_to' => 'Action Assigned To', 'closed_by' => 'Closed By');
                    $capaCategory = $this->requestAction('App/get_model_list/CapaCategory/');
                    $capaSource = $this->requestAction('App/get_model_list/CapaSource/');
                ?>
                <div class="row">
                    <div class="col-md-6"><?php echo $this->Form->input('Search.capa_source_id', array('label' => __('Select Capa Source'), 'options' => $capaSource, 'class' => 'form-control')); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('Search.capa_category_id', array('label' => __('Select Capa Category'), 'options' => $capaCategory, 'class' => 'form-control')); ?></div>
                    <div class="col-md-6 hide"><?php echo $this->Form->input('Search.employee_type', array('label' => __('Select Employee Type'), 'options' => $employee, 'class' => 'form-control')); ?></div>
                    <div class="col-md-6 hide"><?php echo $this->Form->input('Search.employee_id', array('label' => __('Select Employee'), 'options' => $PublishedEmployeeList, 'class' => 'form-control')); ?></div>
                    <div style="clear:both"></div>
                    <div class="col-md-6"><?php echo $this->Form->input('Search.document_change_required', array('label' => __('Document Change Required'), 'type' => 'checkbox')); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('Search.current_status', array('label' => __('Select Current Status'), 'options' => array(0 => 'Open', 1 => 'Closed'), 'type' => 'radio')); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?php echo $this->Form->input('Search.branch_list', array('label' => __('Select branches you want to search'), 'options' => $PublishedBranchList, 'multiple' => true, 'class' => 'form-control')); ?></div>
                </div>

               <div class="row">
                    <div class="col-md-12"><hr /></div>
                    <div class="col-md-4"><?php echo $this->Form->input('Search.from-date', array('id' => 'ddfrom', 'label' => __('Select start date'))); ?></div>
                    <div class="col-md-4"><?php echo $this->Form->input('Search.to-date', array('id' => 'ddto', 'label' => __('Select end date'))); ?></div>
                    <div class="col-md-4"><?php echo $this->Form->input('Search.strict_search', array('label' => __('Strict Search'), 'options' => array('Yes', 'No'), 'checked' => 1, 'type' => 'radio')); ?></div>
                    <?php echo $this->Form->input('Search.capa_type', array('type' => 'hidden')); ?>
                </div>
                <div class ="row">
                    <div class = "col-md-6"><?php echo $this->Form->input('prepared_by', array('options' => $PublishedEmployeeList, 'style'=>array('width'=>'100%'))); ?></div>
                    <div class = "col-md-6"><?php echo $this->Form->input('approved_by', array('options' => $PublishedEmployeeList)); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-12">
			
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#capa_main_inner', 'async' => 'false', 'id'=>'submit_id')); ?>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
                        <?php echo $this->Form->end(); ?>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close'); ?></button>
            </div>
        </div>
    </div>
</div>
<script>
    function datePicker() {
        $("[name*='date']").datepicker({
            changeMonth: true,
            changeYear: true,
            format: 'yyyy-mm-dd',
      autoclose:true,
            'showTimepicker': false,
        }).attr('readonly', 'readonly');
    }
</script>
<script>
    var startDateTextBox = $('#ddfrom');
    var endDateTextBox = $('#ddto');

    startDateTextBox.datepicker({
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
        changeMonth: true,
        changeYear: true,
        beforeShow: function(input, inst) {
            var offset = $(input).offset();
            var height = $(input).height();
            window.setTimeout(function() {
                inst.dpDiv.css({top: (offset.top + height - 260) + 'px'})
            })
        },
        onClose: function(dateText, inst) {
            if (endDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate) {
                    endDateTextBox.val(startDateTextBox.val());
                }
            }
            else {
                endDateTextBox.val(dateText);
            }
        },
        onSelect: function(selectedDateTime) {
            endDateTextBox.datepicker('option', 'minDate', startDateTextBox.datepicker('getDate'));
        }
    }).attr('readonly', 'readonly');
    endDateTextBox.datepicker({
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
        changeMonth: true,
        changeYear: true,
        beforeShow: function(input, inst) {
            var offset = $(input).offset();
            var height = $(input).height();
            window.setTimeout(function() {
                inst.dpDiv.css({top: (offset.top + height - 260) + 'px'});
            })
        },
        onClose: function(dateText, inst) {
            if (startDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate)
                    startDateTextBox.val(endDateTextBox.val());
            }
            else {
                startDateTextBox.val(dateText);
            }
        },
        onSelect: function(selectedDateTime) {
            startDateTextBox.datepicker('option', 'maxDate', endDateTextBox.datepicker('getDate'));
        }
    }).attr('readonly', 'readonly');
</script>
<script>
    $("#Material").hide();

    function customerComplaint() {
        $('.hidediv').hide();
        $('#Product').show();
        $("[name='complaint_source']").click(function() {
            $val = this.value;
            $('.hidediv').hide();
            $('#SearchProductId').val(-1).trigger('chosen:updated');
            $('#SearchDeliveryChallanId').val(-1).trigger('chosen:updated');
            $('.hidediv').find('select').prop('value', -1);
            $('#' + $val).toggle();
        });
    }

    function purchaseOrders() {
        $('.hidedivPO').hide();
        $('.hidedivType').hide();
        $('#Product').show();
        $('#Supplier').show();
        $("[name='purchase_orders']").click(function() {
            $val = this.value;
            $('.hidedivPO').hide();
            $('#SearchProductId').val(-1).trigger('chosen:updated');
            $('#SearchDeviceId').val(-1).trigger('chosen:updated');
            $('.hidedivPO').find('select').prop('value', -1);
            $('#' + $val).toggle();
        });
        $("[name='type']").click(function() {
            $val = this.value;
            if ($val == 0)
                $val = 'Supplier';
            else
                $val = 'Customer';
            $('.hidedivType').hide();
            $('#SearchSupplierRegistrationId').val(-1).trigger('chosen:updated');
            $('#SearchSearchCustomerId').val(-1).trigger('chosen:updated');
            $('.hidedivType').find('select').prop('value', -1);
            $('#' + $val).toggle();
        });
    }

    function changeAddition_DocAmendment() {
        $('.hidediv').hide();
        $('#Branch').show();
        $("[name='request_from']").click(function() {
            $val = this.value;
            $('.hidediv').hide();
            $('#SearchBranchId').val(-1).trigger('chosen:updated');
            $('#SearchDepartmentId').val(-1).trigger('chosen:updated');
            $('.hidediv').find('select').prop('value', -1);
            $('#' + $val).toggle();
        });
    }

    function shhd(chk) {
        if (chk == 'Product') {
            $("#Material").hide();
            $("#Product").show();
        } else if (chk == 'Material') {
            $("#Material").show();
            $("#Product").hide();

        }
    }
</script>
