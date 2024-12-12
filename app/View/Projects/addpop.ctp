<?php $planTypes = array(0=>'Field',1=>'Production'); ?>
<td><?php echo $this->Form->input('Milestone.'.$key.'.ProjectOverallPlan.'.$pop.'.plan_type',array('options'=>$planTypes, 'style'=>'min-width:auto !important', 'label'=>false))?></td>
<?php $types = array(0=>'Lot',1=>'Process'); ?>
<td><?php echo $this->Form->input('Milestone.'.$key.'.ProjectOverallPlan.'.$pop.'.type',array('options'=>$types, 'style'=>'min-width:auto !important', 'label'=>false))?></td>
<!-- <td><?php echo $this->Form->input('Milestone.'.$key.'.ProjectOverallPlan.'.$pop.'.qc',array('label'=>false,'legend'=>'', 'type'=>'radio', 'options'=>array(0=>'No',1=>'Yes')))?> -->
<td><?php echo $this->Form->input('Milestone.'.$key.'.ProjectOverallPlan.'.$pop.'.lot_process',array('label'=>false))?></td>
<td><?php echo $this->Form->input('Milestone.'.$key.'.ProjectOverallPlan.'.$pop.'.estimated_units',array('label'=>false))?></td>
<td><?php echo $this->Form->input('Milestone.'.$key.'.ProjectOverallPlan.'.$pop.'.overall_metrics',array('label'=>false))?></td>
<td><?php echo $this->Form->input('Milestone.'.$key.'.ProjectOverallPlan.'.$pop.'.estimated_manhours',array('label'=>false))?></td>
<td><?php echo $this->Form->input('Milestone.'.$key.'.ProjectOverallPlan.'.$pop.'.days',array('label'=>false,'required'=>'required'))?></td>
<td><?php echo $this->Form->input('Milestone.'.$key.'.ProjectOverallPlan.'.$pop.'.estimated_resource',array('label'=>false))?></td>
<td><?php echo $this->Form->input('Milestone.'.$key.'.ProjectOverallPlan.'.$pop.'.start_date',array('label'=>false))?></td>
<td><?php echo $this->Form->input('Milestone.'.$key.'.ProjectOverallPlan.'.$pop.'.end_date',array('label'=>false))?></td>
<td width="15">
	<div class="btn-group">
		<!-- <div class="btn btn-xs btn-default"><span class="fa fa-list" onclick="adddetail(<?php echo $key?>,<?php echo $pop?>)"></span></div> -->
		<div class="btn btn-xs btn-default"><span class="fa fa-close" onclick="delpoprow(<?php echo $key?>,<?php echo $pop?>)"></span></div>
	</div>
</td>

<script type="text/javascript">
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function(error, element) {
            if ($(element).attr('name') == 'data[Milestone][<?php echo $key;?>][ProjectOverallPlan][<?php echo $pop;?>][plan_type]')
            {                
                $(element).next().after(error);
            }else if ($(element).attr('name') == 'data[Milestone][<?php echo $key;?>][ProjectOverallPlan][<?php echo $pop;?>][type]')
            {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
    });
    
    // $().ready(function() {        

        $('#Milestone<?php echo $key;?>ProjectOverallPlan<?php echo $pop;?>PlanType').rules("add", { greaterThanZero : true });
        $('#Milestone<?php echo $key;?>ProjectOverallPlan<?php echo $pop;?>Type').rules("add", { greaterThanZero : true });


        $('#Milestone<?php echo $key;?>ProjectOverallPlan<?php echo $pop;?>PlanType').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });

        $('#Milestone<?php echo $key;?>ProjectOverallPlan<?php echo $pop;?>Type').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        }); 
    // });
    
</script>
<script type="text/javascript">
    $().ready(function(){
        $("#submit_id").on('click',function(){
            // console.log("SAdasd" + $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>EstimatedUnits").next('.error').html());
            if(
                $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>PlanType").val() == '-1'){
                    err = '<span class="error">Select Plan Type</span>'; 
                    // $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>EstimatedUnits").next().span.remove();
                    if($("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>PlanType").next().next().html() == null){
                        $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>PlanType").next().after(err);    
                    }                                        
                    return false;
            }else{
                err = '';  
                $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>PlanType").next().next().html('');
            }

            if(
                $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>Type").val() == '-1'){
                    err = '<span class="error">Select Type</span>'; 
                    // $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>EstimatedUnits").next().span.remove();
                    if($("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>Type").next().next().html() == null){
                        $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>Type").next().after(err);    
                    }                                        
                    return false;
            }else{
                err = '';  
                $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>Type").next().next().html('');
            }

            if(
                $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>EstimatedUnits").val() <= 0){
                    err = '<span class="error">This should be grear than zero</span>';                                    
                    // $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>EstimatedUnits").next().span.remove();
                    if($("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>EstimatedUnits").next('.error').html() == null){
                        $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>EstimatedUnits").after(err);    
                    }                                        
                    return false;
            }else{
                err = '';  
                $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>EstimatedUnits").next().remove();
            }
            
            if($("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>OverallMetrics").val() <= 0 ){
                    err = '<span class="error">This should be grear than zero</span>';
                    if($("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>OverallMetrics").next('.error').html() == null){
                        $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>OverallMetrics").after(err);
                    }
                    return false;
            }else{
                err = '';
                $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>OverallMetrics").next().remove();;
                // return false;
            }

            if($("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>Days").val() <= 0 ){
                    err = '<span class="error">This should be grear than zero</span>';
                    if($("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>Days").next('.error').html() == null){
                        $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>Days").next().after(err);
                    }
                    return false;
            }else{
                err = '';
                $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>Days").next().remove();;
                // return false;
            }
        });
    });
</script>
<script type="text/javascript">

$().ready(function(){
	$(".chosen-select").chosen();

    $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>Days").addClass('dayscal<?php echo $key?><?php echo $pop?>');
    $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>EstimatedResource").addClass('addtotal<?php echo $key?><?php echo $pop?> ers<?php echo $key?><?php echo $pop?>');
    $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>EstimatedUnits").addClass('addtotal<?php echo $key?><?php echo $pop?> units<?php echo $key?><?php echo $pop?>');
    $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>OverallMetrics").addClass('addtotal<?php echo $key?><?php echo $pop?>');
    $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>EstimatedManhours").addClass('addtotal<?php echo $key?><?php echo $pop?>');


    $("#Milestone<?php echo $key;?>ProjectOverallPlan<?php echo $pop;?>StartDate").datepicker(
        {
            // minDate : $("#ProjectStartDate").datepicker('getDate'),
            // startDate : $("#ProjectStartDate").datepicker('getDate'),
            // endDate : $("#ProjectEndDate").datepicker('getDate'),
            dateFormat:'yy-mm-dd'   
        });
    
    // $("#Milestone<?php echo $key;?>ProjectOverallPlan<?php echo $pop;?>StartDate").datepicker('option', 'minDate', $("#ProjectStartDate").datepicker('getDate'));
    // $("#Milestone<?php echo $key;?>ProjectOverallPlan<?php echo $pop;?>StartDate").datepicker('option', 'startDate', $("#ProjectStartDate").datepicker('getDate'));

    $("#Milestone<?php echo $key;?>ProjectOverallPlan<?php echo $pop;?>EndDate").datepicker(
        {
            // minDate : $("#Milestone<?php echo $key;?>ProjectOverallPlan<?php echo $pop;?>StartDate").datepicker('getDate'),
            // startDate : $("#Milestone<?php echo $key;?>ProjectOverallPlan<?php echo $pop;?>StartDate").datepicker('getDate'),
            // endDate : $("#ProjectEndDate").datepicker('getDate'),
            dateFormat:'yy-mm-dd'   
        });
    
    // $("#Milestone<?php echo $key;?>ProjectOverallPlan<?php echo $pop;?>EndDate").datepicker('option', 'minDate', $("#Milestone<?php echo $key;?>ProjectOverallPlan<?php echo $pop;?>StartDate").datepicker('getDate'));
    // $("#Milestone<?php echo $key;?>ProjectOverallPlan<?php echo $pop;?>EndDate").datepicker('option', 'startDate', $("#ProjectEndDate").datepicker('getDate'));

    $(".addtotal<?php echo $key?><?php echo $pop?>").on('change',function(){
        var units = parseFloat($("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>EstimatedUnits").val());
        var overall_metrics = parseFloat($("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>OverallMetrics").val());
        <?php if($cal_type == 0){ ?>
            var hours = units / overall_metrics;
        <?php }else{ ?>
            var hours = units * overall_metrics;
        <?php } ?>
        
        $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>EstimatedManhours").val(hours);

        // var days = hours / 7;
        // $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>Days").val(days);
    });

    $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>Days").on('change',function(){
        var hours = parseFloat($("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>EstimatedManhours").val());
        var days = parseFloat($("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>Days").val());
            var mp = hours / 7 / days;
        $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>EstimatedResource").val(Math.round(mp));
    });


    $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>StartDate").on('change',function(){
        $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_holidays/null/start_date:" + btoa($("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>StartDate").val()) +'/days:'+ $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>Days").val() , function(data) {
                  console.log(data);
                  $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>EndDate").val(data);
            });
    });
});
                

</script>