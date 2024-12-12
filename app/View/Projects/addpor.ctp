
<?php $planTypes = array(0=>'Field',1=>'Production'); 
$qc  = array(0=>'General',1=>'QC',2=>'Merging');
?>
<td>
<style type="text/css">
    /*.chosen-select{min-width: 100px; max-width: 101px}*/
    .nre {padding-left: 20px}
    .nre .radio input[type="radio"]{ margin: 3px 10px 0 -15px !important}
  </style>
  <?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.sequence',array('default'=>$por, 'label'=>false))?></td>
<td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.process',array('label'=>false))?></td>
<td  style="min-width: 120px; padding-left: 20px" class="nre"><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.qc',array('separator'=>'<br />', 'label'=>false,'legend'=>false,'options'=>array(0=>'General',1=>'QC',2=>'Merging'),'type'=>'radio','default'=>0))?></td>
<td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.list_of_software_id',array('label'=>false))?></td>
<td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.estimated_units',array('label'=>false,'onchange'=>'calcet'.$pop.'('.$pop.')'))?></td>
<td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.unit_rate',array('label'=>false))?></td>
<td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.overall_metrics',array('label'=>false))?></td>
<td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.hours',array('label'=>false))?></td>
<td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.days',array('label'=>false))?></td>
<td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.estimated_resource',array('label'=>false, 'onchange'=>'calcer'.$pop.'('.$pop.')'))?></td>
<td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.start_date',array('label'=>false))?></td>
<td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.end_date',array('label'=>false))?></td>
<td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.dependancy_id',array('label'=>false,'type'=>'text'))?></td>
<td>
	<div class="btn-group">
		<!-- <div class="btn btn-xs btn-default"><span class="fa fa-plus" onclick="addporrow<?php echo $key?><?php echo $pop?>(<?php echo $key;?>,<?php echo $pop;?>,<?php echo $por;?>);"></span></div>	 -->
		<div class="btn btn-xs btn-default"><span class="fa fa-close" onclick="delpoprow(<?php echo $pop?>,<?php echo $por?>)"></span></div>
	</div>
</td>
<?php echo $this->Form->hidden('ProjectProcessPlan.'.$pop.'.'.$por.'.project_overall_plan_id',array('default'=>$op))?>
<?php echo $this->Form->hidden('ProjectProcessPlan.'.$pop.'.'.$por.'.project_id',array('default'=>$project_id))?>
<?php echo $this->Form->hidden('ProjectProcessPlan.'.$pop.'.'.$por.'.milestone_id',array('default'=>$milestone_id))?>
<script type="text/javascript">
$().ready(function(){
    
$(".chosen-select").chosen();

// $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").addClass('addtotal<?php echo $pop?>');
$("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").addClass('dayscal<?php echo $pop?>');
$("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedResource").addClass('addtotal<?php echo $pop?> ers<?php echo $pop?>');
$("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedUnits").addClass('addtotal<?php echo $pop?> units<?php echo $pop?>');
$("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>OverallMetrics").addClass('addtotal<?php echo $pop?>');
$("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Hours").addClass('addtotal<?php echo $pop?>');

$(".addtotal<?php echo $pop?>").on('change',function(){

    <?php if($cal_type == 0){ ?>
      var hours = (parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedUnits").val()) / parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>OverallMetrics").val()));
    <?php }else{ ?>
      var hours = (parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedUnits").val()) * parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>OverallMetrics").val()));
    <?php }?>

    

    $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Hours").val(hours);                                        
    var total = parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").val() * $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedResource").val())
    $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedManhours").val(total);
    var mp = parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Hours").val()) / 7 / parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").val());
    console.log(">>>>>" + mp);
    $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedResource").val(Math.round(mp));

});                                  

$(".dayscal<?php echo $pop?>").on('change',function(){
    var mp = parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Hours").val()) / 7 / parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").val());    
    $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedResource").val(Math.round(mp));

    $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_holidays/null/start_date:" + btoa($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>StartDate").val()) +'/days:'+ $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").val() , function(data) {
          console.log(data);
          $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EndDate").val(data);

          // var mp = parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Hours").val()) / 7 / parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").val());
          // $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedResource").val(Math.round(mp));
    });
});

$("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>StartDate").on('change',function(){
$.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_holidays/null/start_date:" + btoa($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>StartDate").val()) +'/days:'+ $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").val() , function(data) {
          console.log(data);
          $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EndDate").val(data);

          // var mp = parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Hours").val()) / 7 / parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").val());
          // $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedResource").val(Math.round(mp));
  });
});

$("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>StartDate").datepicker({
  changeMonth: true,
  changeYear: true,
  // dateFormat:'YYYY-MM-DD', 
  minDate : '<?php echo date("Y-m-d",strtotime($projectOverallPlan['ProjectOverallPlan']['start_date'])) ;?>',
  maxDate : '<?php echo date("Y-m-d",strtotime($projectOverallPlan['ProjectOverallPlan']['end_date'])) ;?>',
  locale: {
      format: 'YYYY-MM-DD'
  },
  autoclose:true,                                      
});


function calcet<?php echo $pop?>(pop){
  var i = 0;
  $(".units<?php echo $pop?>").each(function(){
      i = i + parseFloat(this.value);                                        
  });
  var total = <?php echo $eu_total?> + parseFloat(i);
  
  if(parseFloat(total) > <?php echo $est_units?>){
    $("#extraunitalert<?php echo $pop?>").html('Total units exceeding estimated units').removeClass('hide').addClass('show');
  }else{
    $("#extraunitalert<?php echo $pop?>").removeClass('show').addClass('hide');
  }  
}

function calcer<?php echo $pop?>(pop){
  var i = 0;
  $(".ers<?php echo $pop?>").each(function(){
      i = i + parseFloat(this.value);                                        
  });
  var total = <?php echo $er_total?> + parseFloat(i);
  
  if(parseFloat(total) > <?php echo $est_units?>){
    $("#extraresalert<?php echo $pop?>").html('Total units exceeding estimated resource').removeClass('hide').addClass('show');
  }else{
    $("#extraresalert<?php echo $pop?>").removeClass('show').addClass('hide');
  }  
}

      

});
</script>
