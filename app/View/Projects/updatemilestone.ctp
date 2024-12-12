<?php echo $this->Html->script(array(
    'jquery.validate.min', 
    'jquery-form.min',
    'jQRangeSlider/jQDateRangeSlider-withRuler-min',
    'plugins/daterangepicker/moment.min',
    'plugins/js_plugin/bootstable')); ?>
<?php echo $this->fetch('script'); ?>
<?php echo $this->Html->css(array('jQRangeSlider/css/iThing-min'));?>
<?php echo $this->fetch('css');?>

<style type="text/css">
    .panel-body .row{margin: 0px 10px !important}
    /*#makeEditable th{padding: 2px !important; font-size: 12px !important;background:#737272;border:1px solid #565555; color: #fff; text-align: center;}
    #makeEditable td{background-color: #e6e6e6;font-size: 12px !important; padding: 4px 2px !important}*/
    .makeEditables .btn-group{min-width: 60px !important}
    .makeEditables .btn-sm{font-size: 8px !important}
    #but_add{font-size: 12px!important; padding: 5px 6px 6px 6px}
    .box{margin: 5px}
    .box-header{padding: 0 10px}
    .table .chosen-container{min-width: auto !important}
    /*, .chosen-container-single, .chosen-select*/
</style>
<div class="panel panel-default">
    <div class="panel-heading"><h4>Milestone <?php echo $value;?>
        <span class="pull-right">            
            <div class="btn btn-warning btn-sm" id="<?php echo $key?>_btn_min">-</div>
            <div class="btn btn-danger btn-sm" id="<?php echo $key?>_btn">X</div>
        </span>
        </h4>
    </div>     
<script type="text/javascript">
    $().ready(function(){
        $("#<?php echo $key?>_btn_min").on('click',function(){
            $("#panel_body_<?php echo $key?>").toggle(500);
        });
    });
</script>
<?php echo $this->Form->create('Milestone',array('controller'=>'milestone','action'=>'updatemilestone'));?>
<?php echo $this->Form->hidden('Milestone.'.$key.'.Milestone.project_id',array('default'=>$this->request->params['named']['project_id'])); ?>
<div class="panel-body" style="padding:15px 0px" id="panel_body_<?php echo $key?>">
    <div class="row">
        <?php
        // $units1 = array('Field','Production');
        // echo "<div class='col-md-12'>".$this->Form->input('Milestone.'.$key.'.Milestone.type',array('type'=>'radio', 'options'=>$units1)) . '</div>';
        echo "<div class='col-md-12'><div id='slider-".$key."' class='sliders'></div></div>"; 
        echo "<div class='col-md-12'><div id='slider-".$key."_error' class='text-danger'></div></div>";  
        
        echo "<div class='col-md-12'><br /></div>";
        echo "<div class='col-md-8'>".$this->Form->input('Milestone.'.$key.'.Milestone.title',array()). '</div>';
        echo "<div class='col-md-4'>".$this->Form->input('Milestone.'.$key.'.Milestone.single_batch',array('type'=>'radio','options'=>array('Single','Batch'),'default'=>0)). '</div>';
        // $units = array('KLM','GRID','PO');
        echo "<div class='col-md-3'>".$this->Form->input('Milestone.'.$key.'.Milestone.unit_id',array('options'=>$deliverableUnits)) . '</div>';
        // $milestoneTypes = array('KLM','Area','Files','Process','Tower');
        echo "<div class='col-md-3'>".$this->Form->input('Milestone.'.$key.'.Milestone.milestone_type_id',array('options'=>$milestoneTypes)) . '</div>';
        echo "<div class='col-md-2'>".$this->Form->input('Milestone.'.$key.'.Milestone.currency_id',array('default'=>$projectCurrency)). '</div>'; 
        echo "<div class='col-md-2'>".$this->Form->input('Milestone.'.$key.'.Milestone.estimated_invoice',array()). '</div>'; 
        echo "<div class='col-md-2'>".$this->Form->input('Milestone.'.$key.'.Milestone.acceptable_errors',array('label'=>'Acceptable Errors (%)')). '</div>'; 
        // echo "<div class='col-md-12'>".$this->Form->input('Milestone.'.$key.'.Milestone.challenges',array('rows'=>2,)). '</div>'; 
        // echo "<div class='col-md-6'>".$this->Form->input('Milestone.'.$key.'.Milestone.resources',array('default'=>0)) . '</div>'; 
        echo "<div class='col-md-6'>".$this->Form->hidden('Milestone.'.$key.'.Milestone.estimated_cost',array('default'=>0)) . '</div>'; 
        echo $this->Form->hidden('Milestone.'.$key.'.Milestone.start_date',array()); 
        echo $this->Form->hidden('Milestone.'.$key.'.Milestone.end_date',array()); 
        ?>
    </div>

  <div class="row">  
    <?php $x = 1; ?>
    <div class="col-md-12"><br /><legend>Overall Plan</legend></div>
    <!-- <div id="purchaseOrderDetails_ajax"> -->
        <div id="purchaseOrderDetails_ajax<?php echo $key; ?>_<?php echo $x;?>" class="col-md-12"> 
            <?php $pop = 0; ?>
            <?php 
                    $calTypes = array(0=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Units/Hours',1=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hours/Units');
                    echo $this->Form->input('Milestone.'.$key.'.ProjectOverallPlan.'.$pop.'.cal_type',array('type'=>'radio','separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp',  'options'=>$calTypes, 'style'=>'min-width:auto !important', 'label'=>false))?> 
            <table class="table table-condensed table-bordered makeEditables">
                <thead>
                    <tr>
                        <th width="120">Plan Type</th>
                        <th width="120">Lot/Process</th>
                        <th width="220">Details</th>
                        <th width="120">Est Units</th>
                        <th width="120">Overall Metrics</th>
                        <th width="120">Est Man Hrs</th> 
                        <th width="120">Est Days</th> 
                        <th width="120">Est Resource</th>
                        <th width="120">Start Date</th>
                        <th width="120">End Date</th>
                        <th></th>                       
                    </tr>
                </thead>
                <tbody>
                
                    <tr>
                        <?php $planTypes = array(0=>'Field',1=>'Production'); ?>
                        <td><?php echo $this->Form->input('Milestone.'.$key.'.ProjectOverallPlan.'.$pop.'.plan_type',array('options'=>$planTypes, 'style'=>'min-width:auto !important', 'label'=>false))?></td>
                        <?php $types = array(0=>'Lot',1=>'Process'); ?>
                        <td><?php echo $this->Form->input('Milestone.'.$key.'.ProjectOverallPlan.'.$pop.'.type',array('options'=>$types,  'required', 'style'=>'min-width:auto !important', 'label'=>false))?></td>
                        <td><?php echo $this->Form->input('Milestone.'.$key.'.ProjectOverallPlan.'.$pop.'.lot_process',array('label'=>false ,'required'))?></td>
                        <td><?php echo $this->Form->input('Milestone.'.$key.'.ProjectOverallPlan.'.$pop.'.estimated_units',array('default'=>0, 'label'=>false,'required'))?></td>
                        <td><?php echo $this->Form->input('Milestone.'.$key.'.ProjectOverallPlan.'.$pop.'.overall_metrics',array('default'=>0,'label'=>false,'required'))?></td>
                        <td><?php echo $this->Form->input('Milestone.'.$key.'.ProjectOverallPlan.'.$pop.'.estimated_manhours',array('default'=>0,'label'=>false,'required'))?></td>
                        <td><?php echo $this->Form->input('Milestone.'.$key.'.ProjectOverallPlan.'.$pop.'.days',array('default'=>0,'label'=>false,'required'))?></td>
                        <td><?php echo $this->Form->input('Milestone.'.$key.'.ProjectOverallPlan.'.$pop.'.estimated_resource',array('default'=>0,'label'=>false,'required'))?></td>
                        <td><?php echo $this->Form->input('Milestone.'.$key.'.ProjectOverallPlan.'.$pop.'.start_date',array('label'=>false,'required'))?></td>
                        <td><?php echo $this->Form->input('Milestone.'.$key.'.ProjectOverallPlan.'.$pop.'.end_date',array('label'=>false,'required'))?></td>
                        <td></td>
                    </tr>
                    <script type="text/javascript">
                        $().ready(function(){
                            $("#submit_id").on('click',function(){
                                // console.log("SAdasd" + $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>EstimatedUnits").next('.error').html());
                                if(
                                    $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?>PlanType").val() == '-1'){
                                        err = '<span class="error">This should be grear than zero</span>';                                    
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
                    <tr id="addpophere-<?php echo $key;?>-<?php echo $pop;?>">
                </tbody>
            </table>
        </div>

        <span id="but_add<?php echo $key; ?>_<?php echo $x;?>" class="btn btn-danger pull-right" onclick="addpoprow<?php echo $key?>(<?php echo $key;?>,<?php echo $pop;?>);" >Add New Row</span>
        <?php echo $this->Form->hidden('popcount',array('id'=>'popcount'.$key, 'default'=>$pop));?>
            <script type="text/javascript">
                $().ready(function(){

                    $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>Days").addClass('dayscal<?php echo $key?><?php echo $pop?>');
                    $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>EstimatedResource").addClass('addtotal<?php echo $key?><?php echo $pop?> ers<?php echo $key?><?php echo $pop?>');
                    $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>EstimatedUnits").addClass('addtotal<?php echo $key?><?php echo $pop?> units<?php echo $key?><?php echo $pop?>');
                    $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>OverallMetrics").addClass('addtotal<?php echo $key?><?php echo $pop?>');
                    $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>EstimatedManhours").addClass('addtotal<?php echo $key?><?php echo $pop?>');

                    $('input[name="data[Milestone][<?php echo $key?>][ProjectOverallPlan][<?php echo $pop?>][cal_type]"]').addClass('addtotal<?php echo $key?><?php echo $pop?>');


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

                        var cal_type = $('input[name="data[Milestone][<?php echo $key;?>][ProjectOverallPlan][<?php echo $pop;?>][cal_type]"]:checked').val();


                        if(cal_type == 0){
                            var units = parseFloat($("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>EstimatedUnits").val());
                            var overall_metrics = parseFloat($("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>OverallMetrics").val());

                            var hours = units / overall_metrics;
                            $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>EstimatedManhours").val(hours);    
                        }else{
                            var units = parseFloat($("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>EstimatedUnits").val());
                            var overall_metrics = parseFloat($("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>OverallMetrics").val());

                            var hours = units * overall_metrics;
                            $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>EstimatedManhours").val(hours);
                        }
                        
                        var hours = parseFloat($("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>EstimatedManhours").val());
                        var days = parseFloat($("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>Days").val());
                            var mp = hours / 7 / days;
                        $("#Milestone<?php echo $key?>ProjectOverallPlan<?php echo $pop?><?php echo $por?>EstimatedResource").val(Math.round(mp));
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
                
                

                function addpoprow<?php echo $key?>(key,pop){
                    console.log('addpoprow<?php echo $key?>');
                    var pop = parseFloat($("#popcount<?php echo $key;?>").val());
                    var cal_type = $('input[name="data[Milestone][<?php echo $key;?>][ProjectOverallPlan][<?php echo $pop;?>][cal_type]"]:checked').val();

                    $("#addpophere-<?php echo $key;?>-"+(pop)).load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/addpop/' + key + '/' + (pop) + '/'+  cal_type, function(response, status, xhr) {                                    
                        if (response != "") {                                        
                            $('#addpophere-<?php echo $key;?>-'+(pop)+'').html(response).after('<tr id="addpophere-<?php echo $key?>-'+(pop+1)+'">'+pop+'</tr>').after('<tr><td id="adddetails-<?php echo $key;?>-'+(pop+1)+'" colspan="8"></td></tr>');
                            $("#popcount<?php echo $key;?>").val(pop+1);
                        } else {               
                        }
                    });
                }

                function delpoprow(key,pop,por){
                    console.log('delpoprow'+key+'-'+(pop-1));
                    $('#addpophere-'+key+'-'+(pop-1)).remove();
                }

                function adddetail(key,pop){
                    console.log('adddetail'+pop);
                    $("#adddetails-<?php echo $key;?>-"+(pop)).load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/addpor/' + key + '/' + (pop), function(response, status, xhr) {                                    
                        if (response != "") {                                        
                            $('#adddetails-<?php echo $key;?>-'+(pop)+'').html(response).after('<tr id="addpophere-<?php echo $key?>-'+(pop+1)+'">'+pop+'</tr>');
                            // $("#popcount<?php echo $key;?>").val(pop+1);
                        } else {               
                        }
                    });
                }

                function addporrow<?php echo $key?><?php echo $pop?>(key,pop,por){
                    var por = parseFloat($("#porcount<?php echo $key;?>"+pop).val());
                    $("#addporhere-<?php echo $key;?>-"+(por)).load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/addpor/' + key + '/' + pop + por , function(response, status, xhr) {                                    
                        if (response != "") {                                        
                            $('#addporhere-<?php echo $key;?>-'+pop+'-'+por).html(response).after('<tr id="addporhere-<?php echo $key?>-'+ pop + (por+1)+'"></tr>');
                            $("#porcount<?php echo $key;?>"+pop).val(por+1);
                        } else {
                            
                        }
                    });
                }

                function delporrow(key,por){
                    $('#addporhere-'+key+'-'+ por).remove();
                }  

            </script>
<div class="clearfix">&nbsp;</div>
</div>
<script type="text/javascript">    
    // $("[name*='date']").datepicker({
    //   changeMonth: true,
    //   changeYear: true,
    //   dateFormat:'yy-mm-dd',      
    // });     
</script>
<?php $x++;?>

<div class="row"> 
    <div class="col-md-12">
        <div class="box box-primary collapsed-box resizable">
            <div class="box-header with-border"><h4>Cost Estimation</h4>
                <div class="btn-group box-tools pull-right">
                    <button type="button" class="btn btn-xs btn-default" data-widget="collapse"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            <div class="box-body" style="padding: 0px">
                <table class="table table-bordered table-responsive" style="margin: 0px">
                    <tr style="background: #e8e8e8">
                        <th>Cost Category</th>
                        <th>Estimated Cost</th>
                        <th>Description</th>            
                    </tr>
                    <?php $c = 0; foreach ($costCategories as $ckey => $cvalue) {  ?>
                        <tr>
                            <td><?php echo "<strong>".$cvalue."</strong>"; 
                            echo $this->Form->hidden('Milestone.'.$key.'.ProjectEstimate.'.$c.'.cost_category_id',array('label'=>false, 'default'=>$ckey))
                            ?></td>                
                            <td><div class="subt subt-<?php echo $key;?>"><?php echo $this->Form->input('Milestone.'.$key.'.ProjectEstimate.'.$c.'.cost',array('default'=>0,'label'=>false))?></div></td>
                            <td><?php echo $this->Form->input('Milestone.'.$key.'.ProjectEstimate.'.$c.'.details',array('rows'=>1,'label'=>false))?></td>
                        </tr>
                    <?php $c++; } ?>

                </table>
            </div>
        </div>
    </div>    
</div>
<div class="row hide"> 
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border"><h4>Upload File</h4>
                <div class="btn-group box-tools pull-right">
                    <button type="button" class="btn btn-xs btn-default" data-widget="collapse"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            <div class="box-body" style="padding: 20px">
                <?php echo $this->Form->file('Milestone.'.$key.'.file.'.$c.'.details',array())?>   
                <p class="help-text">Upload Excel Sheet with file names.</p>         
            </div>
        </div>
    </div>    
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border"><h4>Paste File Data</h4>
                <div class="btn-group box-tools pull-right">
                    <button type="button" class="btn btn-xs btn-default" data-widget="collapse"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            <div class="box-body" style="padding: 20px">
                <?php echo $this->Form->input('Milestone.'.$key.'.file.'.$c.'.file_data',array('type'=>'textarea'))?>   
                <p class="help-text">Upload Excel Sheet with file names.</p>         
            </div>
        </div>
    </div>    
</div>
<div class="row">
    <div class="col-md-12"><h4><strong>Total of Cost Estimation : <span id="sub-m-<?php echo $key;?>">0</div></strong></h4></span>
</div>
<div class="row">
    <div class="col-md-12"><?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id')); ?></div>
</div>
<?php echo $this->Form->end(); ?>

<?php echo $this->Js->writeBuffer();?>

    <script type="text/javascript">
        $().ready(function(){
            $("#submit_id").on('click',function(){
                if($("#Milestone<?php echo $key?>MilestoneStartDate").val() == '' || $("#Milestone<?php echo $key?>MilestoneEndDate").val() == ''){
                    alert('Select Milestone Dates from the slider.');
                    return false;
                }
            });

            // $('#makeEditable<?php echo $key; ?>_<?php echo $x;?>').SetEditable({ 
            //     $addButton: $('#but_add<?php echo $key; ?>_<?php echo $x;?>'),
            //     // columnsEd: "0,1,2,3,4,5,6,7,8,9,10" //editable columns 
            //     }
            // );

            // $(".datepicker").datepicker();

            $(".subt .form-control").each(function(){
                var i = this.id;
                $("#"+i).on('change',function(){
                    calc();
                })
            });

            $(".chosen-select").chosen();

            $("#<?php echo $key?>_btn").click(function(){                
                $("#<?php echo $key?>_div").html("");
                $("#<?php echo $key?>").removeClass("btn-success").addClass("btn-info");
                calc();
            }); 

            $(".sliders").each(function(){
                console.log(new Date($("#ProjectStartDate").val()));
                $("#"+this.id).dateRangeSlider();
                $("#"+this.id).dateRangeSlider("bounds", new Date($("#ProjectStartDate").val()), new Date($("#ProjectEndDate").val()));
            });   
        });
        

        $("#slider-<?php echo $key ;?>").bind("userValuesChanged", function(e, data){
            $("#Milestone<?php echo $key;?>MilestoneStartDate").val(moment(data.values.min).format('YYYY-MM-DD'));
            $("#Milestone<?php echo $key;?>MilestoneEndDate").val(moment(data.values.max).format('YYYY-MM-DD'));
            // $("#Milestone<?php echo $key;?>Include").prop('checked', true);
        });

        // $("#Milestone<?php echo $key;?>ProjectTaskInclude").on('change', function(){
        //     var x = $("#Milestone<?php echo $key;?>ProjectTaskInclude").is(':checked');
        //     if(x == true){
        //         $(".taskclass_<?php echo $key;?>").removeClass('hide');
        //     }else{
        //         $(".taskclass_<?php echo $key;?>").addClass('hide');
        //     }
            
        // });
        // $("#Milestone<?php echo $key;?>Include").on('change', function(){
        //     var x = $("#Milestone<?php echo $key;?>Include").is(':checked');
        //     if(x == true){
        //         // $(".taskclass_<?php echo $key;?>").removeClass('hide');
        //     }else{
        //         $("#ProjectTask<?php echo $key;?>Include").prop('checked', false);
        //         $(".taskclass_<?php echo $key;?>").addClass('hide');
        //     }
            
        // });


        function calc(){
            var subtotal = 0;
            $(".subt .form-control").each(function(){
                var i = this.id;
                // var x = $("#"+i).val();
                var x = this.value;
                // console.log(i + "---" + x);
                if(x > 0)subtotal = parseFloat(subtotal) + parseFloat(x);
            });
            // subtotal = parseFloat(subtotal) + parseFloat(subtotal);
            $("#ProjectEstimatedProjectCost").val(subtotal);
            $("#pcost").html(subtotal);


            var subtotalm = 0;
            $(".subt-<?php echo $key;?> .form-control").each(function(){
                var i = this.id;
                // var x = $("#"+i).val();
                var x = this.value;
                // console.log(i + "---" + x);
                if(x > 0)subtotalm = parseFloat(subtotalm) + parseFloat(x);
            });
            // subtotal = parseFloat(subtotal) + parseFloat(subtotal);
            $("#Milestone<?php echo $key;?>MilestoneEstimatedCost").val(subtotalm);
            $("#sub-m-<?php echo $key;?>").html(subtotalm);
        }

        function cale(val , i){        
            $("#Milestone<?php echo $key;?>ProjectResource"+i+"ResourceSubTotal").val(parseFloat($("#Milestone<?php echo $key;?>ProjectResource"+i+"Mandays").val()) * parseFloat($("#Milestone<?php echo $key;?>ProjectResource"+i+"ResourceCost").val()));
            calc();
        }
    </script>
    
    
    </div>                        
</div> 