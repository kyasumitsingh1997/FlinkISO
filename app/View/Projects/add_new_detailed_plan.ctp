  <?php
    echo $this->Html->css(array(
        // 'cake.generic',
        // 'bootstrap/css/bootstrap.min',
        // 'dist/css/AdminLTE.min',
        // 'dist/css/skins/_all-skins.min',
        // 'plugins/iCheck/flat/blue',
        // // 'plugins/morris/morris.min',
        // 'plugins/jvectormap/jquery-jvectormap-1.2.2',
        // 'plugins/datepicker/datepicker3',
        // 'plugins/daterangepicker/daterangepicker-bs3',
        // 'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min',
        // 'jquery.countdown',
        // 'jquery-ui-1.9.2.custom.min',
        // 'bootstrap-chosen.min',
        // 'jquery.datepicker',
        // 'custom',
        // 'font-awesome.min','icons'
    ));

  echo $this->fetch('css');
  ?>
  <?php
  echo $this->Html->script(array(
      // 'js/bootstrap.min','js/npm',
      // 'plugins/jQuery/jQuery-2.2.0.min',
      // 'plugins/jQueryUI/jquery-ui.min',
      // // 'jquery-form.min',
      // // 'jquery.validate.min',
      // 'js/bootstrap.min',
      // 'validation',
      // 'chosen.min',
      // 'tooltip.min',
      // 'plugins/daterangepicker/moment.min',
      // 'jquery.datepicker',    
      // 'plugins/daterangepicker/daterangepicker',
      // 'plugins/datepicker/bootstrap-datepicker',
  ));
  echo $this->fetch('script');
  ?>

<?php echo $this->Form->create('ProjectProcessPlan',array('controller'=>'project_process_plans','action'=>'add_ajax'),array('id'=>'ProjectOverallPlan'.$pop, 'role'=>'form','class'=>'form','default'=>true)); ?> 
<table class="table table-condensed table-bordered">
    <thead>
        <tr>
            <th width="90">Seq#</th>
            <th>Process</th>
            <th>Process Type</th>
            <th>Software</th>
            <th>Est Units</th>
            <th>Units Rate</th>
            <th>Overall Metrics</th>
            <th>Hours</th>
            <th>Days</th>                                            
            <th>Est Resource</th>
            <!-- <th width="195">Date Range</th> -->
            <th>Start Date</th>
            <th>End Date</th>
            <th>Dependancy</th>
            <th></th>               
        </tr>
    </thead>
    <tbody>
      
<tr>
<?php $planTypes = array(0=>'Field',1=>'Production'); ?>
<td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.sequence',array('default'=>$por, 'label'=>false))?></td>
<td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.process',array('label'=>false))?></td>
<td  style="min-width: 120px; padding-left: 20px" class="nre">
<?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.qc',array(
'separator'=>'<br />', 'label'=>false,'legend'=>false,'options'=>array(0=>'General',1=>'QC',2=>'Merging'),'type'=>'radio','default'=>0))?></td>
<td  style="min-width: 160px"><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.list_of_software_id',array('label'=>false))?></td>
<td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.estimated_units',array('label'=>false, 
// 'onchange'=>'calcet'.$pop.'('.$pop.')'
))?></td>
<td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.unit_rate',array('label'=>false))?></td>
<td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.overall_metrics',array('label'=>false))?></td>
<td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.hours',array('label'=>false))?></td>
<td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.days',array('label'=>false))?></td>            
<td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.estimated_resource',array('default'=>$er_total, 'label'=>false, 'onchange'=>'calcer'.$pop.'('. $projectOverallPlan['ProjectOverallPlan']['estimated_resource'].','.$er_total.','.$pop.')'))?></td>
<td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.start_date',array('label'=>false))?></td>
<td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.end_date',array('label'=>false))?></td>
<td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.dependancy_id',array('label'=>false,'type'=>'text'))?></td>
<!-- <td><?php echo $this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.estimated_manhours',array('label'=>false))?></td> -->

<td></td>
        </tr>
        
        <tr id="addpophere-<?php echo $pop;?>-<?php echo $por;?>"></tr>
    </tbody>
</table>

     <?php echo $this->Form->hidden('ProjectProcessPlan.'.$pop.'.'.$por.'.project_overall_plan_id',array('default'=>$projectOverallPlan['ProjectOverallPlan']['id']))?>
                              <?php echo $this->Form->hidden('ProjectProcessPlan.'.$pop.'.'.$por.'.project_id',array('default'=>$milestone['Milestone']['project_id']))?>
                              <?php echo $this->Form->hidden('ProjectProcessPlan.'.$pop.'.'.$por.'.milestone_id',array('default'=>$milestone['Milestone']['id']))?>
                              
                          <?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#projectProcessPlans_ajax','async' => 'false')); ?>
                          <div id="extraunitalert<?php echo $pop?>" class="hide text-danger"></div>
                          <div id="extraresalert<?php echo $pop?>" class="hide text-danger"></div>
                          <span class="btn btn-success btn-xs pull-right" onclick="addpoprow<?php echo $pop?>(<?php echo $pop;?>,<?php echo $por;?>);" > + </span>
                          <?php echo $this->Form->end(); ?>
                          <?php echo $this->Js->writeBuffer();?>
                          <?php echo $this->Form->hidden('popcount',array('id'=>'popcount'.$pop, 'default'=>$por));?>                              

<script type="text/javascript">
   
  $().ready(function(){

    $(".chosen-select").chosen('destroy');
    $(".chosen-select").chosen({width: "100%"});

    // $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").addClass('addtotal<?php echo $pop?>');
    $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").addClass('dayscal<?php echo $pop?>');
    $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedResource").addClass('addtotal<?php echo $pop?> ers<?php echo $pop?>');
    $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedUnits").addClass('addtotal<?php echo $pop?> units<?php echo $pop?>');
    $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>OverallMetrics").addClass('addtotal<?php echo $pop?>');
    $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Hours").addClass('addtotal<?php echo $pop?>');
    
    $(".addtotal<?php echo $pop?>").on('change',function(){
        
        <?php if($projectOverallPlan['ProjectOverallPlan']['cal_type'] == 0){  ?>
          var hours = (parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedUnits").val()) / parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>OverallMetrics").val()));
        <?php }else{ ?>
          var hours = (parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedUnits").val()) * parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>OverallMetrics").val()));

        <?php }?>
        
        $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Hours").val(hours);

        var total = parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").val() * $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedResource").val())
        
        $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedManhours").val(total);
        

        var mp = parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Hours").val()) / 7 / parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").val());
        
        $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedResource").val(Math.round(mp));

    });                                  

  $(".dayscal<?php echo $pop?>").on('change',function(){
        var mp = parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Hours").val()) / 7 / parseFloat($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").val());
        // console.log(">>" + mp);
        $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedResource").val(Math.round(mp));


        $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_holidays/null/<?php echo $milestone['Milestone']['project_id']?>/start_date:" + btoa($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>StartDate").val()) +'/days:'+ $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").val() , function(data) {
              console.log(data);
              $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EndDate").val(data);

              // var mp = parseInt($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Hours").val()) / 7 / parseInt($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").val());
              // $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedResource").val(Math.round(mp));
        });

    });

  $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>StartDate").on('change',function(){
    $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_holidays/null/<?php echo $milestone['Milestone']['project_id']?>/start_date:" + btoa($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>StartDate").val()) +'/days:'+ $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").val() , function(data) {
              console.log(data);
              $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EndDate").val(data);

              // var mp = parseInt($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Hours").val()) / 7 / parseInt($("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>Days").val());
              // $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EstimatedResource").val(Math.round(mp));
      });
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

  

    // function calcet<?php echo $pop?>(pop){
    //   // var i = 0;
    //   // $(".units<?php echo $pop?>").each(function(){
    //   //     i = i + parseFloat(this.value);
    //   // });
    //   // var total = <?php echo $eu_total?> + parseFloat(i);
      
    //   // if(parseFloat(total) > <?php echo $projectOverallPlan['ProjectOverallPlan']['estimated_units']?>){
    //   //   $("#extraunitalert<?php echo $pop?>").html('Total units exceeding estimated units').removeClass('hide').addClass('show');
    //   // }else{
    //   //   $("#extraunitalert<?php echo $pop?>").removeClass('show').addClass('hide');
    //   // }  
    // }

    function calcer<?php echo $pop?>(pop){
      var i = 0;
      $(".ers<?php echo $pop?>").each(function(){
          i = i + parseFloat(this.value);                                        
      });
      var total = <?php echo $er_total?> + parseFloat(i);
      
      if(parseFloat(total) > <?php echo $projectOverallPlan['ProjectOverallPlan']['estimated_resource']?>){
        $("#extraresalert<?php echo $pop?>").html('Total units exceeding estimated resource').removeClass('hide').addClass('show');
      }else{
        $("#extraresalert<?php echo $pop?>").removeClass('show').addClass('hide');
      }  
    }

    function addpoprow<?php echo $pop?>(pop,por){
        var por = parseFloat($("#popcount<?php echo $pop;?>").val());
        
        var bunitspre = <?php echo $projectOverallPlan['ProjectOverallPlan']['estimated_units']?>-<?php echo $eu_total ?>;
        var i = 0;
        $(".units<?php echo $pop?>").each(function(){
            i = i + parseFloat(this.value);            
        });
        var bunits = bunitspre - i;

        // resource
        var bresspre = <?php echo $projectOverallPlan['ProjectOverallPlan']['estimated_resource']?>-<?php echo $er_total ?>;
        var i = 0;
        $(".ers<?php echo $pop?>").each(function(){
            i = i + parseFloat(this.value);            
        });
        var bers = bresspre - i;


        // console.log(pop + "- " + por);
$("#addpophere-<?php echo $pop;?>-"+por).load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/addpor/' + pop + '/' + por + '/<?php echo $projectOverallPlan['ProjectOverallPlan']['cal_type']?>/start:<?php echo $projectOverallPlan['ProjectOverallPlan']['start_date']?>/end:<?php echo $projectOverallPlan['ProjectOverallPlan']['end_date']?>/project_id:<?php echo $milestone['Milestone']['project_id']?>/milestone_id:<?php echo $milestone['Milestone']['id']?>/op:<?php echo $projectOverallPlan['ProjectOverallPlan']['id']?>/overall_metrics:<?php echo $projectOverallPlan['ProjectOverallPlan']['overall_metrics']?>/bunits:'+bunits + '/bers:'+bers + '/est_units:<?php echo $projectOverallPlan['ProjectOverallPlan']['estimated_units'];?>', function(response, status, xhr) {                                    
if (response != "") {
$('#addpophere-<?php echo $pop;?>-'+por).html(response).after('<tr id="addpophere-<?php echo $pop?>-'+(por+1)+'"></tr>');
$("#popcount<?php echo $pop;?>").val(por+1);
} else {               
}
});
    }

    function delpoprow(pop,por){
        $('#addpophere-'+pop+'-'+(por-1)).remove();
    }

        // $("#ProjectProcessPlan<?php echo $pop?><?php echo $por?>EndDate").datepicker({
        //   changeMonth: true,
        //   changeYear: true,
        //   dateFormat:'yy-mm-dd',                                          
        // }).on("changeDate", function() {
        //   var por = parseInt($("#popcount<?php echo $pop;?>").val());
        //   chkholiday(<?php echo $pop ?>,por,this);
        //   return false;
        // });
    
    // function adddetail(pop,pop){
    //     console.log('adddetail'+pop);
    //     $("#adddetails-<?php echo $pop;?>-"+(pop)).load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/addpor/' + pop + '/' + (pop), function(response, status, xhr) {                                    
    //         if (response != "") {                                        
    //             $('#adddetails-<?php echo $pop;?>-'+(pop)+'').html(response).after('<tr id="addpophere-<?php echo $pop?>-'+(pop+1)+'">'+pop+'</tr>');
    //             // $("#popcount<?php echo $pop;?>").val(pop+1);
    //         } else {               
    //         }
    //     });
    // }

    // function addporrow<?php echo $pop?><?php echo $pop?>(pop,pop,por){
    //     console.log('addporrow'+pop);
    //     var por = parseInt($("#porcount<?php echo $pop;?>"+pop).val());
    //     $("#addporhere-<?php echo $pop;?>-"+(por)).load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/addpor/' + key + '/' + pop + por , function(response, status, xhr) {                                    
    //         if (response != "") {                                        
    //             $('#addporhere-<?php echo $pop;?>-'+pop+'-'+por).html(response).after('<tr id="addporhere-<?php echo $pop?>-'+ pop + (por+1)+'"></tr>');
    //             $("#porcount<?php echo $pop;?>"+pop).val(por+1);
    //         } else {
                
    //         }
    //     });
    // }

    // function delporrow(key,por){
    //     console.log('delporrow'+por);
    //     $('#addporhere-'+key+'-'+ por).remove();
    // }  

</script>