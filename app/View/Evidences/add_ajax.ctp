<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<div id="evidences_ajax">
    <?php echo $this->Session->flash();?><div class="nav">
        <div class="evidences form col-md-8">
            <h4>Add New Document</h4>
            <?php echo $this->Form->create('Evidence',array('role'=>'form','class'=>'form','default'=>true));?>
            <div class="row">
		      <fieldset>
                <?php
               
                foreach (array_keys($models, 'Design History Files') as $key) {
                    unset($models[$key]);
                }
                $models = array_merge($models,array_keys($special));

                if(isset($this->request->params['named']['model'])){
                    if($this->request->params['named']['model'] == 'products')$selected_model = 1;
                    if($this->request->params['named']['model'] == 'dashboard_files')$selected_model = 0;
                    echo "<div class='col-md-6'>".$this->Form->input('model_name',array('options'=>$models,'default'=>$selected_model, 'label'=>'Table/Form for which you want to upload a document')) . '</div>'; 
                    echo "<div class='col-md-6'>".$this->Form->input('record',array('label'=>'Select a record for upload', 'options'=>$all_recs,'default'=>$selected_record)) . '</div>';
                }else{

                    echo "<div class='col-md-6'>".$this->Form->input('model_name',array('options'=>$models,'label'=>'Table/Form for which you want to upload a document')) . '</div>'; 
                    echo "<div class='col-md-6'>".$this->Form->input('record',array('default'=>$this->request->params['named']['record'], 'label'=>'Select a record for upload', 'options'=>array_values($special))) . '</div>'; 
                    
                }
                if(isset($pro_special) && $this->request->params['named']['model'] != 'dashboard_files'){
                    echo "<div class='col-md-6' id='record_dd'>".$this->Form->input('record_type',array('default'=>$this->request->params['named']['record_type'], 'options'=>$pro_special)) . '</div>'; 
                }elseif($this->request->params['named']['model'] == 'clauses'){
                    echo "<div class='col-md-6' id='record_dd'>".$this->Form->input('record_type',array('options'=>$clause_special,'default'=>$clause_special_selected)) . '</div>'; 
                }else{
                    echo "<div class='col-md-6' id='record_dd'>".$this->Form->input('record_type',array('options'=>array_values($special))) . '</div>'; 
                }

                echo "<div class='col-md-12'>".$this->Form->input('description',array('label'=>'Description / Keywords')) . '</div>'; 
            ?>
		      <div class="col-md-12">
                <h4><?php echo __('Upload File'); ?></h4>
                <?php echo $this->Form->file('document', array('class'=>'btn btn-lg btn-default')); ?>
            </div>
            </fieldset>
                <?php
                    echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
                    echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
                    echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
                    echo $this->Form->input('user_session_id',array('type'=>'hidden' ,'value'=>$this->Session->read('User.user_session_id')));
		          ?>
        </div>
<script type="text/javascript">
    $().ready(function() {
        
        if($('#EvidenceModelName').val() == 1)
        {
            $("#record_dd").show();         
        }else if($('#EvidenceModelName').val() == 2){
            $("#record_dd").show();         
        }else if($('#EvidenceModelName').val() == '<?php echo $clause_key; ?>'){
            $("#record_dd").show();
        }else{
            $("#record_dd").hide();
        };




    $('#EvidenceModelName').change(function() 
    {
        if(this.value == 1){
              $("#EvidenceRecord").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_records/' + encodeURIComponent(this.value), function(response, status, xhr) {            
                    $("#EvidenceRecord").html(response);
                    $('#EvidenceRecord').val(0).trigger('chosen:updated');               
            });
            $("#EvidenceRecordType").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_records/' + encodeURIComponent("product_files"), function(response, status, xhr) {         
                     $("#record_dd").show();
                    $("#EvidenceRecordType").html(response);
                    $('#EvidenceRecordType').val(0).trigger('chosen:updated');               
            });
            $('#EvidenceRecordType').rules('add', {
                greaterThanZeroString: true
            });
        }else if(this.value == 2){
            $("#EvidenceRecord").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_records/' + encodeURIComponent(this.value), function(response, status, xhr) {            
                    $("#EvidenceRecord").html(response);
                    $('#EvidenceRecord').val(0).trigger('chosen:updated');               
            });
            $("#EvidenceRecordType").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_records/' + encodeURIComponent("design_history_files"), function(response, status, xhr) {         
                    $("#record_dd").show();
                    $("#EvidenceRecordType").html(response);
                    $('#EvidenceRecordType').val(0).trigger('chosen:updated');               
            });
            $('#EvidenceRecordType').rules('add', {
                greaterThanZeroString: true
            });
        }else if(this.value == '<?php echo $clause_key; ?>'){
            $("#EvidenceRecord").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_records/' + encodeURIComponent(this.value), function(response, status, xhr) {            
                    $("#EvidenceRecord").html(response);
                    $('#EvidenceRecord').val(0).trigger('chosen:updated');
                    
                    $('#EvidenceRecord').change(function(){
                        $("#EvidenceRecordType").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_records/' + encodeURIComponent("clauses")   + '/' + encodeURIComponent(this.value), function(response, status, xhr) {         
                            $("#record_dd").show();
                            $("#EvidenceRecordType").html(response);
                            $('#EvidenceRecordType').val(0).trigger('chosen:updated');               
                    });
                });               
            }
        );
        $("#record_dd").show();
    }else{                   
              $("#EvidenceRecord").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_records/' + encodeURIComponent(this.value), function(response, status, xhr) {            
                    $("#EvidenceRecord").html(response);
                    $('#EvidenceRecord').val(0).trigger('chosen:updated');               
            });
             $('#EvidenceRecordType').rules('remove');
              $("#record_dd").hide();
    }
});
        //$("#record_dd").hide();
});
</script>
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
    }); </script>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<script>
    $.validator.setDefaults({
           ignore: null,
        errorPlacement: function(error, element) {
            if ($(element).attr('name') == 'data[Evidence][record]' ||
                    $(element).attr('name') == 'data[Evidence][record_type]' ||
                    $(element).attr('name') == 'data[Evidence][model_name]') {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
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
            $("#EvidenceRecord").chosen();
            $("#submit-indicator").hide();
            jQuery.validator.addMethod("greaterThanZero", function(value, element) {
                return this.optional(element) || (parseFloat(value) >0);
            }, "Please select the value");
            jQuery.validator.addMethod("greaterThanZeroString", function(value, element) {
                return this.optional(element) || (value !=-1);
            }, "Please select the value");

        $('#EvidenceAddAjaxForm').validate({
            rules: {
                "data[Evidence][record]": {
                    greaterThanZeroString: true,
                },
                "data[Evidence][model_name]": {
                    greaterThanZeroString: true,
                },
              
            }
        });
          $('#EvidenceModelName').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });

        $('#EvidenceRecord').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#EvidenceRecordType').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
    });
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
