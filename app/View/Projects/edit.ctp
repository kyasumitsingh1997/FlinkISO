 <div id="projects_ajax">

<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>

<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="projects form col-md-8">
<h4><?php echo __('Edit Project'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
        <?php echo $this->Html->link('View',array('action'=>'view',$this->request->params['pass'][0]),array('class'=>'label btn-warning'));?>
		
		</h4>
<?php echo $this->Form->create('Project',array('role'=>'form','class'=>'form')); ?>
<div class="row">
<fieldset>
			<?php            
		      // echo "<div class='col-md-6'>".$this->Form->input('title',array()) . '</div>'; 
        //         echo "<div class='col-md-6'>".$this->Form->input('customer_id',array()) . '</div>'; 
        //         // echo "<div class='col-md-12'>".$this->Form->input('users',array('label'=>'Project Team', 'name'=>'Project[users][]', 'options'=>$PublishedUserList, 'multiple')) . '</div>'; 
        // 		echo "<div class='col-md-12'>".$this->Form->input('goal',array()) . '</div>'; 
        // 		echo "<div class='col-md-12'>".$this->Form->input('scope',array()) . '</div>'; 
        // 		echo "<div class='col-md-12'>".$this->Form->input('success_criteria',array()) . '</div>'; 
        // 		echo "<div class='col-md-12'>".$this->Form->input('challenges',array()) . '</div>'; 
            	
        //         echo "<div class='col-md-4'>".$this->Form->input('employee_id',array('style'=>'','label'=>'Project Leader')) . '</div>';         
        //         echo "<div class='col-md-4'>".$this->Form->input('start_date',array()) . '</div>'; 
        //         echo "<div class='col-md-4'>".$this->Form->input('end_date') . '</div>'; 
        //         echo "<div class='col-md-4'>".$this->Form->input('current_status',array()) . '</div>'; 

            echo "<div class='col-md-3'>".$this->Form->input('project_code',array()) . '</div>';
            // echo "<div class='col-md-3'>".$this->Form->input('project_number',array()) . '</div>'; 
            echo "<div class='col-md-3'>".$this->Form->input('deliverable_unit_id',array('options'=>$deliverableUnits)) . '</div>'; 
            echo "<div class='col-md-6'>".$this->Form->input('title',array()) . '</div>'; 
            echo "<div class='col-md-8'>".$this->Form->input('customer_id',array('label'=>'Client Name')) . '</div>'; 
            echo "<div class='col-md-4'>".$this->Form->input('currency_id',array('label'=>'Currency')) . '</div>'; 
            // echo "<div class='col-md-4'>".$this->Form->input('state_id',array()) . '</div>'; 
            
            // echo "<div class='col-md-12'>".$this->Form->input('users',array('label'=>'Project Team', 'name'=>'Project[users][]', 'options'=>$PublishedUserList, 'multiple')) . '</div>'; 
            echo "<div class='col-md-12'>".$this->Form->input('goal',array('rows'=>2)) . '</div>'; 
            echo "<div class='col-md-12'>".$this->Form->input('scope',array('rows'=>2)) . '</div>'; 
            echo "<div class='col-md-12'>".$this->Form->input('success_criteria',array('rows'=>2)) . '</div>'; 
            echo "<div class='col-md-12'>".$this->Form->input('challenges',array('rows'=>2)) . '</div>'; 
            
            echo "<div class='col-md-12'>".$this->Form->input('employee_id',array('name'=>'data[Project][employee_id][]','style'=>'','label'=>'Project Manager','multiple','options'=>$projectManagers, 'selected'=>json_decode($this->data['Project']['employee_id'],false),'onchange'=>'get_childs(this.id)')) . '</div>';
            
            echo "<div class='col-md-12'>".$this->Form->input('project_leader_id',array('name'=>'data[Project][project_leader_id][]', 'options'=>$teamLeaders, 'selected'=>json_decode($this->data['Project']['project_leader_id'],false),'style'=>'','multiple','label'=> 'PLs')) . '</div>';
            

            echo "<div class='col-md-12'>".$this->Form->input('team_leader_id',array('name'=>'data[Project][team_leader_id][]', 'options'=>$teamLeaders, 'selected'=>json_decode($this->data['Project']['team_leader_id'],false), 'style'=>'','multiple','label'=>'TLs')) . '</div></div>';
            
            
            echo "<div class='row'><div class='col-md-4'>".$this->Form->input('estimated_total_resources',array()) . '</div>';
            echo "<div class='col-md-4'>".$this->Form->input('start_date',array()) . '</div>'; 
            echo "<div class='col-md-4'>".$this->Form->input('end_date') . '</div>'; 
            echo "<div class='col-md-4'>".$this->Form->input('current_status',array('default'=>0)) . '</div>'; 

            echo "<div class='col-md-4'>".$this->Form->input('daily_hours',array('options'=>array(0=>8,1=>12),'type'=>'radio','default'=>0)) . '</div>'; 

            echo "<div class='col-md-4'>".$this->Form->input('weekends',array('name'=>'data[Project][weekends][]', 'selected'=>json_decode($this->data['Project']['weekends']), 'options'=>$weekends,'multiple')) . '</div>';
	?>
</fieldset>

<div class="col-md-12"><?php echo $this->Form->hidden('estimated_project_cost',array())?></div>
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

<script> 
$("#ProjectEndDate").datepicker({
      changeMonth: true,
      changeYear: true,
      // minDate:'<?php echo date('Y-m-d',strtotime($this->request->data['Project']['start_date']))?>',
      // startDate:'<?php echo date('Y-m-d',strtotime($this->request->data['Project']['start_date']))?>',
      format: 'yyyy-mm-dd',
      locale: {
        format: 'yyyy-mm-dd'
    },
    autoclose:true,
    });

    $("#ProjectStartDate").datepicker({
        format: 'yyyy-mm-dd',
        locale: {
            format: 'yyyy-mm-dd',
            autoclose: true
        },
    }).on('changeDate', function(selected){
        startDate = new Date(selected.date.valueOf());
        $('#ProjectEndDate').datepicker('setStartDate', startDate);
    });  

    $("#ProjectEndDate").datepicker({
        format: 'yyyy-mm-dd',
        locale: {
            format: 'yyyy-mm-dd',
            autoclose: true
        },
    }).on('changeDate', function(selected){
        startDate = new Date(selected.date.valueOf());
        $('#ProjectStartDate').datepicker('setEndDate', startDate);
    });

</script>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
</div>
<script>

    function get_childs(id){
        var selected=[];
        var x = 0;
        $('#'+ id + ' :selected').each(function(){
            selected[x]=$(this).val();
            x++;
        });
        
        console.log(selected);
        $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_childs/ids:" + btoa(selected) , function(data) {                
                $("#ProjectTeamLeaderId").find('option').remove().end().append(data).trigger('chosen:updated');
                $("#ProjectProjectLeaderId").find('option').remove().end().append(data).trigger('chosen:updated');
        });
    }

	function calc(){
        var subtotal = 0;
        $(".subt .form-control").each(function(){
            var i = this.id;
            var x = $("#"+i).val();
            console.log(i + "---" + x);
            if(x > 0)subtotal = parseInt(subtotal) + parseInt(x);
        });
        // subtotal = parseInt(subtotal) + parseInt(subtotal);
        $("#ProjectEstimatedProjectCost").val(subtotal);
        $("#pcost").html(subtotal);
    }

    function cale(val , i){        
        $("#ProjectResource"+i+"ResourceSubTotal").val(parseInt($("#ProjectResource"+i+"Mandays").val()) * parseInt($("#ProjectResource"+i+"ResourceCost").val()));
        calc();
    }

	    function addAgendaDiv(args) {
	        var i = parseInt($('#ProjectAgendaNumber').val());
	        $('#ProjectAgendaNumber').val();
	        $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_resource/" + i, function(data) {
	            $('#purchaseOrderDetails_ajax').append(data);
	        });
	        i = i + 1;
	        $('#ProjectAgendaNumber').val(i);
	    }
	    function removeAgendaDiv(i) {
	        var r = confirm("Are you sure to remove this order details?");
	        if (r == true)
	        {
	            $('#purchaseOrderDetails_ajax' + i).remove();
	            calc();
	        }
	    }

    // $.validator.setDefaults();

    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function(error, element) {
            
            if ($(element).attr('name') == 'data[Project][deliverable_unit_id]')
            {
                $(element).next().after(error);
            }else if ($(element).attr('name') == 'data[Project][customer_id]')
            {
                $(element).next().after(error);
            }else if ($(element).attr('name') == 'data[Project][team_leader_id]')
            {
                $(element).next().after(error);
            }else if ($(element).attr('name') == 'data[Project][current_status]')
            {
                $(element).next().after(error);
            }else if ($(element).attr('name') == 'data[Project][employee_id][]')
            {
                $(element).next().after(error);
            }else if ($(element).attr('name') == 'data[Project][team_leader_id][]')
            {
                $(element).next().after(error);
            }else if ($(element).attr('name') == 'data[Project][project_leader_id][]')
            {
                $(element).next().after(error);
            }else if ($(element).attr('name') == 'data[Project][weekends][]')
            {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        }
    });

    $().ready(function() {

    	$(".subt .form-control").each(function(){
            var i = this.id;
            $("#"+i).on('change',function(){
                calc();
            })
        });
    	

        jQuery.validator.addMethod("greaterThanZero", function (value, element) {
        return this.optional(element) || (value != -1 );
        }, "Please select the value");

        jQuery.validator.addMethod("greaterThanZero1", function (value, element) {
        return (this.optional(element) == false) || (value != null);
        }, "Please select the value");


    	// $('#ProjectStartDate').change(function(){
    	// 	// $('#ProjectEndDate').val($('#ProjectStartDate').val());
    	// });
        $('#ProjectEditForm').validate({
            rules: {
                "data[Project][deliverable_unit_id]": {
                    greaterThanZero: true,
                },
                "data[Project][customer_id]": {
                    greaterThanZero: true,
                },
                "data[Project][team_leader_id]": {
                    greaterThanZero: true,
                },
                "data[Project][current_status]": {
                    greaterThanZero: true,
                },
                "data[Project][employee_id][]": {
                    greaterThanZero1: true,
                },
                "data[Project][project_leader_id][]": {
                    greaterThanZero1: true,
                },
                "data[Project][team_leader_id][]": {
                    greaterThanZero1: true,
                },
                "data[Project][weekends][]": {
                    greaterThanZero1: true,
                },
            }
        });


        $('#ProjectDeliverableUnitId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });

        $('#ProjectCustomerId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });            
        
        $('#ProjectCurrentStatus').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });            

        $('#ProjectEmployeeId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });

        $('#ProjectProjectLeaderId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });

        $('#ProjectTeamLeaderId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });

        $('#ProjectWeekends').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });

        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
            if($('#ProjectEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                $('#ProjectEditForm').submit();
            }

        });
    });
</script>

