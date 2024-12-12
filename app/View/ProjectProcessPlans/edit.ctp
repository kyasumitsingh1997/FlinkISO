<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<div id="projectProcessPlans_ajax">
	<?php echo $this->Session->flash();?>	
	<div class="nav panel panel-default">
	<div class="projectProcessPlans form col-md-8">

<style type="text/css">
	.checkbox input[type=checkbox], .checkbox-inline input[type=checkbox], .radio input[type=radio], .radio-inline input[type=radio]{
		position: relative !important;
	}
</style>		
	<h4><?php echo __('Edit Project Process Plan'); ?>		
			
			</h4>
	<?php echo $this->Form->create('ProjectProcessPlan',array('role'=>'form','class'=>'form')); ?>
	<div class="row">
		<?php 
		// Configure::write('debug',1);
		// debug($this->data);
		//0=>8,1=>12
		if($this->data['Project']['daily_hours'] == 1)$daily_hours = 11.6;
		else $daily_hours = 7.6;

		$weekends = count(json_decode($this->data['Project']['weekends']));
			echo "<div class='col-md-8'>";
			$calTypes = array(0=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Units/Hours',1=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hours/Units');
			echo $this->Form->input('cal_type',array('type'=>'radio','separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp',  'options'=>$calTypes, 'style'=>'min-width:auto !important', 'default'=>$projectOverallPlan['ProjectOverallPlan']['cal_type'], 'label'=>false))?>
				<?php
			echo "</div><div class='col-md-4'><label>Weightage</label><br/>".$this->request->data['ProjectProcessPlan']['weightage']."%</div>";
				
			$eu_total = $er_total = 0;
			echo "<div class='col-md-6 hide'>".$this->Form->hidden('project_id',array('style'=>'')) . '</div>'; 
			echo "<div class='col-md-6 hide'>".$this->Form->hidden('milestone_id',array('style'=>'')) . '</div>'; 
			echo "<div class='col-md-6 hide'>".$this->Form->hidden('project_overall_plan_id',array('style'=>'')) . '</div>'; 
			echo "<div class='col-md-6'>".$this->Form->input('process') . '</div>'; 
			echo "<div class='col-md-6'>".$this->Form->input('qc',array('label'=>false,'legend'=>false,'options'=>array(0=>'General',1=>'QC',2=>'Merging'),'type'=>'radio')) . '</div>'; 
			echo "<div class='col-md-6'>".$this->Form->input('list_of_software_id',array('label'=>'Software')) . '</div>'; 
			echo "<div class='col-md-3'>".$this->Form->input('estimated_units',array('label'=>'Est Units')) . '</div>'; 
			echo "<div class='col-md-3'>".$this->Form->input('unit_rate',array('label'=>'Units Rate('.$currencies[$projectCurrency].')')) . '</div>'; 
			echo "<div class='col-md-3'>".$this->Form->input('overall_metrics',array('label'=>'Overall Metric')) . '</div>'; 
			if($projectOverallPlan['ProjectOverallPlan']['cal_type'] == 0){
				$hours = $this->request->data['ProjectProcessPlan']['estimated_units'] / $this->request->data['ProjectProcessPlan']['overall_metrics'];
			}else{
				$hours = $this->request->data['ProjectProcessPlan']['estimated_units'] * $this->request->data['ProjectProcessPlan']['overall_metrics'];
			}
			
			echo "<div class='col-md-3'>".$this->Form->input('hours',array('default'=>$hours)) . '</div>'; 
			echo "<div class='col-md-3'>".$this->Form->input('days') . '</div>'; 
			echo "<div class='col-md-3'>".$this->Form->input('estimated_resource',array('label'=>'Est Resources','readonly'=>'readonly')) . '</div>'; 
			echo "<div class='col-md-3'>".$this->Form->input('start_date') . '</div>'; 
			echo "<div class='col-md-3'>".$this->Form->input('end_date',array('readonly'=>'readonly')) . '</div>'; 
			
			// echo "<div class='col-md-3'>".$this->Form->input('estimated_manhours') . '</div>'; 
			echo "<div class='col-md-12'>".$this->Form->input('ProjectProcessPlan.'.$pop.'.'.$por.'.dependancy_id',array('onchange'=>'updatedepedancy(this.value,"'.$projectProcessPlan['ProjectProcessPlan']['id'].'")', 'default'=>$projectProcessPlan['ProjectProcessPlan']['dependancy_id'],  'options'=>$existingprocesses)). '</div>'; 

			echo "<div class='col-md-12'>".$this->Form->input('OtherMeasurableUnit.additional_units',array('type'=>'textarea')) . '</div>'; 			
		?>
		<div class='col-md-12'> <strong>Note:</strong>: Add values separated by new line (< ENTER >)</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<br /><br />
			<p><strong>Note: </strong> Changing Est Resources manually will not have any effect. Change <strong>Days</strong> field and system will automatically change the Est Resources and End Date.</p>
		</div>
	<?php
			echo $this->Form->input('id');
			echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
			echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
			echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
			echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
			?>
	</div>
<h3>Additional Measurable Units</h3>
<table class="table table-responsive table-bordered table-condensed">
	<tr>
		<th>Unit Name</th>		
	</tr>
	<?php foreach($this->request->data['OtherMeasurableUnit'] as $otherMeasurableUnit){ ?>
		<tr>			
			<td><?php echo $this->Html->link($otherMeasurableUnit['unit_name'],'javascript:void(0)',array('id'=>'name-'.$otherMeasurableUnit['id'])) ?>&nbsp;
				<script type="text/javascript">
	            $(document).ready(function() {$('#name-<?php echo $otherMeasurableUnit['id'] ?>').editable({
	                   type:  'text',
	                   pk:    '<?php echo $otherMeasurableUnit['id'] ?>',
	                   name:  'data.OtherMeasurableUnit.unit_name',
	                   url:   '<?php echo Router::url('/', true);?>other_measurable_units/inplace_edit',  
	                   title: 'Change',
	                   placement : 'right'
	                });
	            });
	            
          </script>
			</td>			
		</tr>
	<?php } ?>
</table>
<h3>Weekly Plan</h3>
<table class="table table-responsive table-bordered table-condensed">	
	<tr><th>#</th><th>Week</th><th>Planned Resource</th><th>Hours</th><th>Units</th><th>%</th><th>W%</th></tr>
	<?php
$startDate = date("Y/m/d", strtotime("-1 week", strtotime($this->data['ProjectProcessPlan']['start_date'])));
$startDate = $first = date('Y-m-d',strtotime('monday 1 week',strtotime($startDate)));

$endDate =  $second = date('Y-m-d',strtotime($this->data['ProjectProcessPlan']['end_date']));
$w = 0;
while (strtotime($first) <= strtotime($second)) {
$w++;
$first = date("Y-m-d", strtotime("+1 week", strtotime($first)));	
}

$x = 0;
while (strtotime($startDate) <= strtotime($endDate)) {
	echo "<td>".($x+1)."</td>";	
	echo "<td>".date('Y/m/d',strtotime($startDate)) .' - ' . date("Y/m/d", strtotime("+1 week", strtotime($startDate)));
	echo " - " . date('W',strtotime($startDate))."</td>";
	// echo "<th>Planned</th>";

if(isset($this->request->data['ProcessWeeklyPlan']['planned'])){	
	$planned = $this->request->data['ProcessWeeklyPlan']['planned'];
	}else{
	$planned = round($this->request->data['ProjectProcessPlan']['estimated_resource'],2);
}

	echo "<td>";
	echo $this->Form->input('ProcessWeeklyPlan.'.$x.'.id',array());
	echo $this->Form->input('ProcessWeeklyPlan.'.$x.'.planned',array('label'=>false,'default'=>$planned, 'class'=>'1', 'onChange'=>'updatehours('.$x.')'));
	echo "</td><td>";
	echo $this->Form->input('ProcessWeeklyPlan.'.$x.'.hours',array('label'=>false,'readonly'=>'readonly'));
	echo "</td><td>";
	echo $this->Form->input('ProcessWeeklyPlan.'.$x.'.units',array('label'=>false,'readonly'=>'readonly'));
	echo $this->Form->hidden('ProcessWeeklyPlan.'.$x.'.year',array('label'=>false,'default'=>date('y',strtotime($startDate))));
	echo $this->Form->hidden('ProcessWeeklyPlan.'.$x.'.week',array('label'=>false,'default'=>date('W',strtotime($startDate))));
	echo "</td><td><div class='sumtot' id='per".$x."'></div></td><td><div class='sumtotper' id='perw".$x."'></div></td></tr>";

	$total_planned_res = $total_planned_res + $this->request->data['ProcessWeeklyPlan'][$x]['planned'];
	$total_planned_hrs = $total_planned_hrs + $this->request->data['ProcessWeeklyPlan'][$x]['hours'];
	$total_planned_units = $total_planned_units + $this->request->data['ProcessWeeklyPlan'][$x]['units']; ?>
<script type="text/javascript">
	$("#ProcessWeeklyPlan<?php echo $x;?>Planned").addClass(' addp');
	$("#ProcessWeeklyPlan<?php echo $x;?>Hours").addClass(' addh');
	$("#ProcessWeeklyPlan<?php echo $x;?>Units").addClass(' addu');
</script>
<?php $x++;
	?>

	<?php
	$startDate = date("Y-m-d", strtotime("+1 week", strtotime($startDate)));	
}?>
	<tr>
		<td></td>
		<td></td>
		<td><div id="plannedcount">~ <?php echo round($total_planned_res / $w);?> Resources / Week</div></td>
		<td><div id="hourscount"><?php echo $total_planned_hrs;?></div> / <div id="hourscountadj">0</div></td>
		<td><div id="unitscount"><?php echo $total_planned_units;?> </div> / <div id="unitscountadj">0</div></td>
		<td><div id="totalsum">0</div></td>
		<td><div id="totalsumper">0</div></td>
	</tr>
</table>		
	<div class="">
	<?php
		if ($showApprovals && $showApprovals['show_panel'] == true) {
			echo $this->element('approval_form');
		} else {
			echo $this->Form->input('publish', array('label' => __('Publish')));
		}
	?>
	<?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'edit_submit_id')); ?>
	<?php echo $this->Html->image('indicator.gif', array('id' => 'edit_submit-indicator')); ?>
	<?php echo $this->Form->end(); ?>

	<?php echo $this->Js->writeBuffer();?>
	</div>
	</div>
	<script> 
		
		
	</script>
	<div class="col-md-4">
		<p><?php echo $this->element('helps'); ?></p>
	</div>
	</div>
	</div>
	<script>
	    $.validator.setDefaults({
	    	ignore: null,
	    	errorPlacement: function(error, element) {
	            if ($(element).attr('name') == 'data[ProjectProcessPlan][list_of_software_id]')
				{	
	                $(element).next().after(error);
	            } else {
	                $(element).after(error);
	            }
	        },
	        submitHandler: function (form) {
	            $(form).ajaxSubmit({
	                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/edit/<?php echo $this->request->params['pass'][0];?>",
	                type: 'POST',
	                target: '#projectProcessPlans_ajax',
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
	    
	    $("#ProjectProcessPlanStartDate").datepicker({
			changeMonth: true,
			changeYear: true,
			format: 'yyyy-mm-dd',			
			minDate : '<?php echo date("Y-m-d",strtotime($project['Project']['start_date'])) ;?>',
			maxDate : '<?php echo date("Y-m-d",strtotime($project['Project']['end_date'])) ;?>',
			startDate : '<?php echo date("Y-m-d",strtotime($project['Project']['start_date'])) ;?>',
			endDate : '<?php echo date("Y-m-d",strtotime($project['Project']['end_date'])) ;?>',
			autoclose:true,                                      
		});

		$("#ProjectProcessPlanEndDate").datepicker({
			changeMonth: true,
			changeYear: true,
			format: 'yyyy-mm-dd',			
			minDate : '<?php echo date("Y-m-d",strtotime($project['Project']['start_date'])) ;?>',
			maxDate : '<?php echo date("Y-m-d",strtotime($project['Project']['end_date'])) ;?>',
			startDate : '<?php echo date("Y-m-d",strtotime($project['Project']['start_date'])) ;?>',
			endDate : '<?php echo date("Y-m-d",strtotime($project['Project']['end_date'])) ;?>',
			autoclose:true,                                      
		});

		function totalsum(){
			var p = 0;
			$(".addp").each(function(){
				p = p + parseFloat(this.value);
			});
			
			$("#plannedcount").html(parseFloat(p/<?php echo $w?>).toFixed(2));


			var p = 0;
			$(".addh").each(function(){
				p = p + parseFloat(this.value);
			});
			
			$("#hourscount").html(parseFloat(p).toFixed(2));

			$("#hourscountadj").html(parseFloat($("#hourscount").html() - $("#ProjectProcessPlanHours").val()).toFixed(2)); 


			var p = 0;
			$(".addu").each(function(){
				p = p + parseFloat(this.value).toFixed(2);
			});

			$("#unitscount").html(parseFloat(p).toFixed(2));			
			$("#unitscountadj").html(parseFloat($("#unitscount").html() - $("#ProjectProcessPlanEstimatedUnits").val()).toFixed(2));

			var p = 0;
			// add percentages
			<?php for($i=0; $i <= $x; $i++){ ?>
				$("#per<?php echo $i?>").html( parseFloat($("#ProcessWeeklyPlan<?php echo $i;?>Hours").val() * 100 / $("#ProjectProcessPlanHours").val()).toFixed(2));
			<?php } ?>

			var p = 0;
			$(".sumtot").each(function(){
				p = p + parseFloat($("#"+this.id).html());
			});
			$("#totalsum").html(p.toFixed(2));

			// add percentages weightagewise
			var per = 0;
			<?php for($i=0; $i <= $x; $i++){ ?>
				per = parseFloat($("#ProcessWeeklyPlan<?php echo $i;?>Hours").val() * 100 / $("#ProjectProcessPlanHours").val()).toFixed(2);
				wper = parseFloat(per * <?php echo $this->request->data["ProjectProcessPlan"]["weightage"];?> / 100).toFixed(2);
				$("#perw<?php echo $i?>").html(wper);				
			<?php } ?>

			var p = 0;
			$(".sumtotper").each(function(){
				p = p + parseFloat($("#"+this.id).html());
			});
			$("#totalsumper").html(parseFloat(p).toFixed(2));
			
		}

		function updatehours(x){
			$("#ProcessWeeklyPlan"+x+"Hours").val(parseFloat($("#ProcessWeeklyPlan"+x+"Planned").val() * <?php echo $daily_hours;?> * 5).toFixed(2));
			$("#ProcessWeeklyPlan"+x+"Units").val(parseFloat($("#ProcessWeeklyPlan"+x+"Hours").val() * <?php echo $this->data['ProjectProcessPlan']['overall_metrics'];?>).toFixed(2));

			totalsum();
		}

		 function calcet(pop){
		    var i = 0;
		    $(".units").each(function(){
		        i = i + parseFloat(this.value);
		    });
		    var total = <?php echo $eu_total?> + parseFloat(i);
		    
		    if(parseFloat(total) > <?php echo $projectOverallPlan['ProjectOverallPlan']['estimated_units']?>){
		      $("#extraunitalert").html('Total units exceeding estimated units').removeClass('hide').addClass('show');
		    }else{
		      $("#extraunitalert").removeClass('show').addClass('hide');
		    }  
		  }

		  function calcer(pop){
		    var i = 0;
		    $(".ers").each(function(){
		        i = i + parseFloat(this.value);                                        
		    });
		    var total = <?php echo $er_total?> + parseFloat(i);
		    
		    if(parseFloat(total) > <?php echo $projectOverallPlan['ProjectOverallPlan']['estimated_resource']?>){
		      $("#extraresalert").html('Total units exceeding estimated resource').removeClass('hide').addClass('show');
		    }else{
		      $("#extraresalert").removeClass('show').addClass('hide');
		    }  
		  }

		  function addpoprow(pop,por){
		      var por = parseFloat($("#popcount").val());
		      
		      var bunitspre = <?php echo $projectOverallPlan['ProjectOverallPlan']['estimated_units']?>-<?php echo $eu_total ?>;
		      var i = 0;
		      $(".units").each(function(){
		          i = i + parseFloat(this.value);
		          console.log("i" + i);
		      });
		      var bunits = bunitspre - i;

		      // resource
		      var bresspre = <?php echo $projectOverallPlan['ProjectOverallPlan']['estimated_resource']?>-<?php echo $er_total ?>;
		      var i = 0;
		      $(".ers").each(function(){
		          i = i + parseFloat(this.value);
		          console.log("i" + i);
		      });
		      var bers = bresspre - i;
		}

		  function delpoprow(pop,por){
		      console.log('delpoprow'+pop+'-'+(por-1));
		      $('#addpophere-'+pop+'-'+(por-1)).remove();
		  }


	    $().ready(function() {


	    	totalsum();

	    	for (let i = 0; i < <?php echo $x;?>; i++) {
  				updatehours(i);
			}


	    	$("#edit_submit-indicator").hide();
	    	
			$("#ProjectProcessPlan<?php echo $por?>Days").addClass('dayscal');
			$("#ProjectProcessPlan<?php echo $por?>EstimatedResource").addClass('addtotal ers');
			$("#ProjectProcessPlan<?php echo $por?>EstimatedUnits").addClass('addtotal units');
			$("#ProjectProcessPlan<?php echo $por?>OverallMetrics").addClass('addtotal');
			$("#ProjectProcessPlan<?php echo $por?>Hours").addClass('addtotal');
		  
			  $(".addtotal").on('change',function(){

			  	// console.log('here');

			  	var cal_type = $('input[name="data[ProjectProcessPlan][cal_type]"]:checked').val();

			  	if(cal_type == 0){
					var hours = (parseFloat($("#ProjectProcessPlan<?php echo $por?>EstimatedUnits").val()) / parseFloat($("#ProjectProcessPlan<?php echo $por?>OverallMetrics").val()));

					$("#ProjectProcessPlan<?php echo $por?>Hours").val(hours);                                        
					
					// var total = parseFloat($("#ProjectProcessPlan<?php echo $por?>Days").val() * $("#ProjectProcessPlan<?php echo $por?>EstimatedResource").val())
					// $("#ProjectProcessPlan<?php echo $por?>EstimatedManhours").val(total);
					
					var mp = parseFloat($("#ProjectProcessPlan<?php echo $por?>Hours").val()) / <?php echo $daily_hours;?> / parseFloat($("#ProjectProcessPlan<?php echo $por?>Days").val());
					// console.log(">>>>>" + mp);
					$("#ProjectProcessPlan<?php echo $por?>EstimatedResource").val(Math.round(mp));
				}else{

					var hours = (parseFloat($("#ProjectProcessPlan<?php echo $por?>EstimatedUnits").val()) * parseFloat($("#ProjectProcessPlan<?php echo $por?>OverallMetrics").val()));

					$("#ProjectProcessPlan<?php echo $por?>Hours").val(hours);                                        
					
					// var total = parseFloat($("#ProjectProcessPlan<?php echo $por?>Days").val() * $("#ProjectProcessPlan<?php echo $por?>EstimatedResource").val())
					// $("#ProjectProcessPlan<?php echo $por?>EstimatedManhours").val(total);
					
					var mp = parseFloat($("#ProjectProcessPlan<?php echo $por?>Hours").val()) / <?php echo $daily_hours;?> / parseFloat($("#ProjectProcessPlan<?php echo $por?>Days").val());
					// console.log(">>>>>" + mp);
					$("#ProjectProcessPlan<?php echo $por?>EstimatedResource").val(Math.round(mp));

				}

			  });                                  

			$(".dayscal").on('change',function(){

				var cal_type = $('input[name="data[ProjectProcessPlan][cal_type]"]:checked').val();

				if(cal_type == 0){				
					var mp = parseFloat($("#ProjectProcessPlan<?php echo $por?>Hours").val()) / <?php echo $daily_hours;?> / parseFloat($("#ProjectProcessPlan<?php echo $por?>Days").val());
			      
					$("#ProjectProcessPlan<?php echo $por?>EstimatedResource").val(mp.toFixed(1));
			  	}else{
			  		var mp = parseFloat($("#ProjectProcessPlan<?php echo $por?>Hours").val()) / <?php echo $daily_hours;?> / parseFloat($("#ProjectProcessPlan<?php echo $por?>Days").val());
			      	
			      	$("#ProjectProcessPlan<?php echo $por?>EstimatedResource").val(mp.toFixed(2));
			  	}

			  	// $("#ProjectProcessPlan<?php echo $por?>StartDate").on('change',function(){
					$.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_holidays/null/start_date:" + btoa($("#ProjectProcessPlan<?php echo $por?>StartDate").val()) +'/days:'+ $("#ProjectProcessPlan<?php echo $por?>Days").val() + '/project_id:<?php echo $this->request->data["ProjectProcessPlan"]["project_id"];?>', function(data) {
			            const d = moment(data).format('YYYY-MM-DD');
			            $("#ProjectProcessPlan<?php echo $por?>EndDate").val(d);

			            // var mp = parseInt($("#ProjectProcessPlan<?php echo $por?>Hours").val()) / 7 / parseInt($("#ProjectProcessPlan<?php echo $por?>Days").val());
			            // $("#ProjectProcessPlan<?php echo $por?>EstimatedResource").val(Math.round(mp));
			    	});
				// });

			      $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_holidays/null/start_date:" + btoa($("#ProjectProcessPlan<?php echo $por?>StartDate").val()) +'/days:'+ $("#ProjectProcessPlan<?php echo $por?>Days").val() + '/project_id:<?php echo $this->request->data["ProjectProcessPlan"]["project_id"];?>' , function(data) {
			            console.log(data);
			            $("#ProjectProcessPlan<?php echo $por?>EndDate").val(data);

			            // var mp = parseFloat($("#ProjectProcessPlan<?php echo $por?>Hours").val()) / 7 / parseFloat($("#ProjectProcessPlan<?php echo $por?>Days").val());
			            // $("#ProjectProcessPlan<?php echo $por?>EstimatedResource").val(Math.round(mp));
			      });

			  });

			$("#ProjectProcessPlan<?php echo $por?>StartDate").on('change',function(){
				$.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_holidays/null/start_date:" + btoa($("#ProjectProcessPlan<?php echo $por?>StartDate").val()) +'/days:'+ $("#ProjectProcessPlan<?php echo $por?>Days").val() + '/project_id:<?php echo $this->request->data["ProjectProcessPlan"]["project_id"];?>', function(data) {
			            const d = moment(data).format('YYYY-MM-DD');
			            $("#ProjectProcessPlan<?php echo $por?>EndDate").val(d);

			            // var mp = parseInt($("#ProjectProcessPlan<?php echo $por?>Hours").val()) / 7 / parseInt($("#ProjectProcessPlan<?php echo $por?>Days").val());
			            // $("#ProjectProcessPlan<?php echo $por?>EstimatedResource").val(Math.round(mp));
			    });
			});

			// });


		 
			// $("#ProjectProcessPlanStartDate").datepicker("setDate", "<?php echo date("Y-m-d",strtotime($project['Project']['start_date'])) ;?>");


	    	jQuery.validator.addMethod("greaterThanZero", function(value, element) {
	            return this.optional(element) || (parseFloat(value) > 0);
	        }, "Please select the value");

	        $('#ProjectProcessPlanEditForm').validate({        	
	            rules: {
					"data[ProjectProcessPlan][list_of_software_id]": {
	                		greaterThanZero: true,
						}
	            }
	        }); 
				
	        
	        $("#edit_submit_id").click(function(){
	            if($('#ProjectProcessPlanEditForm').valid()){
	                 $("#edit_submit_id").prop("disabled",true);
	                 $("#edit_submit-indicator").show();
	                $('#ProjectProcessPlanEditForm').submit();
	            }

	        });

			$('#ProjectProcessPlanListOfSoftwareId').change(function() {
				if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
					$(this).next().next('label').remove();
				}
			});			

	    });
</script>