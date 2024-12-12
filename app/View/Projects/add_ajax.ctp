<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min','jQRangeSlider/jQDateRangeSlider-withRuler-min','plugins/daterangepicker/moment.min','plugins/js_plugin/bootstable')); ?>
<?php echo $this->fetch('script'); ?>
<?php echo $this->Html->css(array('jQRangeSlider/css/iThing-min'));?>
<?php echo $this->fetch('css');?>

<?php 
$activities = array(
    1=>'1',
    2=>'2',
    3=>'3',
    4=>'4',
    5=>'5',
    6=>'6',
    7=>'7',
    8=>'8',
    9=>'9',
    10=>'10',
    11=>'11',
    12=>'12',
    13=>'13',
    14=>'14',
    15=>'15',
    16=>'16',
    17=>'17',
    18=>'18',
    19=>'19',
    20=>'20',
);
?>

<div id="projects_ajax">
<?php echo $this->Session->flash();?><div class="nav">
<div class="projects form col-md-12">
<h4>Add Project</h4>
<?php echo $this->Form->create('Project',array('role'=>'form','class'=>'form','default'=>false)); ?>
<div class="row">
		<fieldset>
			<?php
        		echo "<div class='col-md-3'>".$this->Form->input('project_code',array()) . '</div>';
                // echo "<div class='col-md-3'>".$this->Form->input('project_number',array()) . '</div>'; 
                echo "<div class='col-md-3'>".$this->Form->input('deliverable_unit_id',array('options'=>$deliverableUnits)) . '</div>'; 
                echo "<div class='col-md-6'>".$this->Form->input('title',array()) . '</div>'; 
                echo "<div class='col-md-12'>".$this->Form->input('customer_id',array('label'=>'Client Name')) . '</div>'; 
                // echo "<div class='col-md-4'>".$this->Form->input('state_id',array()) . '</div>'; 
                
                // echo "<div class='col-md-12'>".$this->Form->input('users',array('label'=>'Project Team', 'name'=>'Project[users][]', 'options'=>$PublishedUserList, 'multiple')) . '</div>'; 
        		echo "<div class='col-md-12'>".$this->Form->input('goal',array('rows'=>2)) . '</div>'; 
        		echo "<div class='col-md-12'>".$this->Form->input('scope',array('rows'=>2)) . '</div>'; 
        		echo "<div class='col-md-12'>".$this->Form->input('success_criteria',array('rows'=>2)) . '</div>'; 
        		echo "<div class='col-md-12'>".$this->Form->input('challenges',array('rows'=>2)) . '</div>'; 
            	
                echo "<div class='col-md-12'>".$this->Form->input('employee_id',array('name'=>'data[Project][employee_id][]','style'=>'','label'=>'Project Manager','multiple','onchange'=>'get_childs(this.id)')) . '</div>';
                
                echo "<div class='col-md-12'>".$this->Form->input('project_leader_id',array('name'=>'data[Project][project_leader_id][]', 'options'=>$PublishedEmployeeList, 'style'=>'','multiple','label'=> 'PLs')) . '</div>';
                
                echo "<div class='col-md-12'>".$this->Form->input('team_leader_id',array('name'=>'data[Project][team_leader_id][]', 'style'=>'','multiple','label'=>'TLs')) . '</div></div>';
                
                echo "<div class='row'><div class='col-md-4'>".$this->Form->input('estimated_total_resources',array()) . '</div>';
                echo "<div class='col-md-4'>".$this->Form->input('start_date',array('required'=>'required')) . '</div>'; 
                echo "<div class='col-md-4'>".$this->Form->input('end_date') . '</div>'; 
                echo "<div class='col-md-4'>".$this->Form->input('current_status',array('default'=>0)) . '</div>'; 

                echo "<div class='col-md-4'>".$this->Form->input('daily_hours',array('options'=>array(0=>8,1=>12),'type'=>'radio','default'=>0)) . '</div>'; 
                echo "<div class='col-md-4'>".$this->Form->input('weekends',array('name'=>'data[Project][weekends][]', 'selected'=>json_decode($this->data['Project']['weekends']), 'options'=>$weekends,'multiple','required'=>'required')) . '</div>';
        
        // echo "<div class='col-md-12'><h4>Stakeholders</h4></div>"; 
        // echo "<div class='col-md-4'>".$this->Form->input('employees',array('type'=>'checkbox','options'=>false)) . '</div>'; 
        // echo "<div class='col-md-4'>".$this->Form->input('customers') . '</div>'; 
        // echo "<div class='col-md-4'>".$this->Form->input('suppliers_vendors') . '</div>'; 
        // echo "<div class='col-md-12'>".$this->Form->input('others') . '</div>'; 
		// echo "<div class='col-md-6'>".$this->Form->input('branch_id',array()) . '</div>'; 
		
		// echo "<div class='col-md-6'>".$this->Form->input('user_session_id',array()) . '</div>'; 
	?>
</fieldset>
</div>
<div class="row">
<div id="project_planning" class="">
    <h3 style="margin-left: 12px">Planning Board</h3>
    <fieldset>
        
        <?php
            $i = 0;            
            foreach ($activities as $key => $value) { ?>
                <div class="btn btn-info btn-xl float-left" id="<?php echo $key?>" style="margin: 5px 5px 5px 12px;"><?php echo $value?></div>                
                <script type="text/javascript">
                    $("#<?php echo $key?>").click(function(){
                        $("#<?php echo $key?>").removeClass("btn-info").addClass("btn-success");

                        if($("#<?php echo $key?>_div").html()==''){
                            $("#<?php echo $key?>_div").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/addmilestone/' + <?php echo $key?> + '/' + btoa("<?php echo $value?>"), function(response, status, xhr) {
                                // if (response != "") {
                                //     $('#EmployeeOfficeEmail').val('');
                                //     $('#EmployeeOfficeEmail').addClass('error');
                                // } else {
                                //     $('#EmployeeOfficeEmail').removeClass('error');
                                // }
                            });
                        }else{
                            $("#panel_body_<?php echo $key?>").toggle(500);
                        }
                    });
                </script>
            <?php $i++;?>
            <?php } ?>        
            <?php
            $i = 0;
            foreach ($activities as $key => $value) { ?>
                <div id="<?php echo $key?>_div" class="float-left"></div>
            <?php $i++;?>
            <?php } ?> 
    </fieldset>


 <!-- + '/' + btoa($("#ProjectEndDate").val()) -->
<div class="row">    
    <div class="col-md-12"><h2>Estimated Project Cost : <span id="pcost">0</span></h2></div>
    <div class="col-md-12"><?php echo $this->Form->hidden('estimated_project_cost',array('default'=>0))?></div>
</div>
</div>
<?php
    echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
    echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
    echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
		?></div>

<div class="">
<?php
if ($showApprovals && $showApprovals['show_panel'] == true) {
	echo $this->element('approval_form');
} else {
	echo $this->Form->input('publish', array('label' => __('Publish')));
}?>
<?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#projects_ajax','async' => 'false')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer();?>
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
    
    // $("[name*='date']").datepicker({
    //   changeMonth: true,
    //   changeYear: true,
    //   format: 'yyyy-mm-dd',
    //   locale: {
    //     format: 'yyyy-mm-dd'
    // },
    // autoclose:true,
    // });

    $("#ProjectStartDate").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    }).on('changeDate', function(selected){
        startDate = new Date(selected.date.valueOf());
        $('#ProjectEndDate').datepicker('setStartDate', startDate);
    });  

    $("#ProjectEndDate").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    }).on('changeDate', function(selected){
        startDate = new Date(selected.date.valueOf());
        $('#ProjectStartDate').datepicker('setEndDate', startDate);
    });

   
</script>
<div class="col-md-12">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<script>

    function calc(){
        var subtotal = 0;
        $(".subt .form-control").each(function(){
            var i = this.id;
            // var x = $("#"+i).val();
            var x = this.value;
            console.log(i + "---" + x);
            if(x > 0)subtotal = parseInt(subtotal) + parseInt(x);
        });
        // subtotal = parseInt(subtotal) + parseInt(subtotal);
        $("#ProjectEstimatedProjectCost").val(subtotal);
        $("#pcost").html(subtotal);
    }

    function cale(val , i){        
        $("#MilestoneProjectResource<?php echo $key;?>"+i+"ResourceSubTotal").val(parseInt($("#MilestoneProjectResource<?php echo $key;?>"+i+"Mandays").val()) * parseInt($("#MilestoneProjectResource<?php echo $key;?>"+i+"ResourceCost").val()));
        calc();
    }

    function addAgendaDiv(key,x) {
        var i = parseInt($('#agendaNumber'+key).val());
        // console.log(i);
        // $('#ProjectAgendaNumber').val();
        $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_resource/" + key + "/" + i, function(data) {
            $('#purchaseOrderDetails_ajax'+ key + '_' + x).append(data);
        });
        i = i + 1;
        $('#agendaNumber'+key).val(i);
    }
    function removeAgendaDiv(key,x) {
        var r = confirm("Are you sure to remove this order details?");
        if (r == true)
        {
            $('#purchaseOrderDetails_ajax'+ key + '_' + x).remove();
            calc();
        }
    }


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

            jQuery.validator.addMethod("greaterThanZero", function (value, element) {
            return this.optional(element) || (value != -1 );
            }, "Please select the value");

            jQuery.validator.addMethod("greaterThanZero1", function (value, element) {
            return (this.optional(element) == false) || (value != null);
            }, "Please select the value");
            

            $('#ProjectAddAjaxForm').validate({
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


            $("#ProjectEndDate").change(function(){
                $("#project_planning").removeClass('hide');
                $(".tasks_panel").removeClass('hide');
                $(".sliders").each(function(){
                    $("#"+this.id).dateRangeSlider();
                    $("#"+this.id).dateRangeSlider("bounds", new Date($("#ProjectStartDate").val()), new Date($("#ProjectEndDate").val()));
                });
            });


            $(".subt .form-control").each(function(){
                var i = this.id;
                $("#"+i).on('change',function(){
                    calc();
                })
            });

            // $(".subt .form-control").each(function(){
            //     var i = this.id;
            //     $("#"+i).on('change',function(){
            //         calc();
            //     })
            // });

            $("#submit-indicator").hide();
                $('#ProjectAddAjaxForm').validate();     
            });
</script>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
