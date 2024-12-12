<script type="text/javascript">
	$().ready(function(){
		$("#ReportDates").daterangepicker({
	        format: 'MM/DD/YYYY',
	        locale: {
	            format: 'MM/DD/YYYY'
	        },
	        autoclose:true,
	    }); 
	});
		

</script>
<?php
if($tables){
	foreach ($tables as $table) {
		$t[] = $table['SystemTable']['id'];
	}	
}
$curr_month = ($this->request->data['Report']['month'])?  $this->request->data['Report']['month'] : ''  ;
if ($curr_month) {
	$curr_month = date('Y-m',strtotime($this->request->data['Report']['month']));	
} else {
	$curr_month = date('Y-m');
}
?>
<?php 
	$date1 = strtotime(date("Y-m-d", strtotime($curr_month)) . " -1 month"); 
	$date2 = strtotime(date("Y-m-d", strtotime($curr_month)) . " -2 month"); 
	
?>
<style type="text/css">
#summery{margin: 20px; width: 96%}
.btn-group-vertical{ padding-top: 28px !important;}
  .chartist-tooltip {
    position: relative;
    display: inline-block ;
    opacity: 0;
    min-width: 5em;
    padding: .5em;
    background: #F4C63D;
    color: #453D3F;
    font-family: Oxygen,Helvetica,Arial,sans-serif;
    font-weight: 700;
    text-align: center;
    pointer-events: none;
    z-index: 1;
    -webkit-transition: opacity .2s linear;
    -moz-transition: opacity .2s linear;
    -o-transition: opacity .2s linear;
    transition: opacity .2s linear; 
}
  .chartist-tooltip:before {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    width: 0;
    height: 0;
    margin-left: -15px;
    border: 15px solid transparent;
    border-top-color: #F4C63D; }
  .chartist-tooltip.tooltip-show {
    opacity: 1; }
</style>
<div class="main">
	<?php echo $this->Form->create('Report', array('role' => 'form', 'class' => 'form')); ?>
	<div class="row">
		<div class="col-md-12"><h4 class="text-success"><?php echo __("Summery for "); ?><?php echo date('M-Y',strtotime($curr_month)) ?>
			<?php echo $this->Html->link('<span class="glyphicon glyphicon-plus pull-right" id="sh"></span>','#',array('escape'=>false,'id'=>'sh')); ?></h4>		
		</div>		
	</div>
	<?php
		if(!$branches){
			$branches = $this->Session->read('User.branch_id');
		}

		if(!$departments){
			$departments = $this->Session->read('User.department_id');
		}
	?>
	<div class="row" id="src-panel">
        <div class="col-md-5">
        	<?php echo $this->Form->input('branches', array('id' => 'branches', 'label' => __('Add/remove Branches'), 'options' => $PublishedBranchList,'multiple'=>'true','default'=>$branches)); ?></div>
        <div class="col-md-5"><?php echo $this->Form->input('departments', array('id' => 'departments', 'label' => __('Add/remove Departments'), 'options' => $PublishedDepartmentList,'multiple'=>'true','default'=>$departments)); ?></div>
        <div class="col-md-2">
            
            <?php
            // $end_date = date('Y-m-1');
            // $date = date("Y-m-d", strtotime("-36 month", strtotime($end_date)));
            // while ($date < $end_date) {
            //     $options[date('Y', strtotime($end_date))][date('Y-m', strtotime($end_date))] = date('M-Y', strtotime($end_date));
            //     $end_date = date("Y-m-d", strtotime("-1 month", strtotime($end_date)));
            // }
            // echo $this->Form->input('month', array('id' => 'month', 'label' => __('Change Month'), 'options' => $options,'default'=>date('m-Y')));
            echo $this->Form->input('dates');
            ?>
        </div>        
        <div class="col-md-10">
			<?php 
        		echo $this->Form->input('system_tables[]',array(
						'label'=>'Linked Tables',
						'id'=> 'tables',
						'name'=>'system_tables[]',
						'options'=>$system_tables,
						'selected'=>$t,'multiple'
					)) ; 
			?>        	
        </div>
        <div class="col-md-2">
        	<div class="btn-group-vertical" role="group">        		
        	<?php echo $this->Form->submit('Reload',array('class'=>'btn btn-sm btn-primary','div'=>false)); ?>
        	<?php //echo $this->Html->Link('Download Excel',array('controller'=>'reports','action'=>'download_report_summery',base64_encode(json_encode($this->request->data))),array('class'=>'btn btn-default btn-sm')); ?>
        </div>
        </div>
        
    </div>
    <?php echo $this->Form->end(); ?>
<?php

if(!empty($summery)){ 
	foreach ($summery as $sum) {
		$series1[] = $sum[1];
		$series2[] = $sum[2];
		$series3[] = $sum[3];		
	}
	
	if(array_sum($series1) ==0 && array_sum($series2) == 0 && array_sum($series3) == 0){ ?>
		<div class="ct-chart " id="summery">No Data To Display. Please change the month from drop down.</div>
	<?php }else{ ?>
<style type="text/css">
.p-height,.ct-chart{ height: 340px}
.p-height{padding: 130px 0 0 0; font-size: 28px; font-weight: 800; color: #e2e2e2;-webkit-transform: rotate(330deg);-moz-transform: rotate(330deg);-o-transform: rotate(330deg);writing-mode: lr-tb;};
.ct-label,.ct-labels{font-size: 1.75rem !important;color: #000 !important}
/*.ct-chart .ct-series.ct-series-c .ct-bar {stroke: #6597C9 !important;}
.ct-chart .ct-series.ct-series-b .ct-bar {stroke: #BECEDE !important;}
.ct-chart .ct-series.ct-series-a .ct-bar {stroke: #DCE8F5 !important;}*/
.ct-chart .ct-bar{stroke-width:30px !important;}
.form{ margin-bottom: 0}
</style>
<?php
    echo $this->Html->script(array('chartist/chartist.min','chartist/chartist-plugin-axistitle.min','chartist/chartist-plugin-tooltip.min'));    
    echo $this->fetch('script');
    echo $this->Html->css(array('chartist/chartist.min','tooltip.min'));
    echo $this->fetch('css');
?>
<?php 
	$data = "[['Name','This Month','Last Month','Last Month'],";
	foreach ($summery as $sum) {
		$labels[]= $sum[0];
		$series1[] = $sum[1];
		$series2[] = $sum[2];
		$series3[] = $sum[3];
	}	
?>
<div class="ct-chart " id="summery"></div>  
</div>
<script type="text/javascript">
	new Chartist.Bar('#summery', {
	  labels: <?php echo json_encode($labels);?>,
	  series: [
	  	<?php if($series3) { ?> {'name':'<?php echo date("M-Y",$date2);?>','data':<?php echo json_encode($series3);?>}, <?php } ?>
	  	<?php if($series2) { ?>{'name':'<?php echo date("M-Y",$date1);?>','data':<?php echo json_encode($series2);?>},<?php } ?>
	  	<?php if($series1) { ?>{'name':'<?php echo date("M-Y",strtotime($curr_month)) ?>','data':<?php echo json_encode($series1);?>}<?php } ?>
	  	]

	}
	, {
		fullWidth: true,
		// axisY: {onlyInteger: true},
		axisX:{offset: 80},
		chartPadding: {left: 0,right:20},
		plugins: [
          Chartist.plugins.ctAxisTitle({
            axisY: {
              axisTitle: '#',
              axisClass: 'ct-axis-title',
              offset: {
                x: 0,
                y: 0
              },
              textAnchor: 'middle',
              flipTitle: false
            }
          }),
          Chartist.plugins.tooltip()
        ]
	}
	);
// $.noConflict();
</script>
<?php } ?>
<?php }?>

<script type="text/javascript">
	$().ready(function(){
		$('#tables').width('100%');
		$('#tables').chosen();
		$('#branches').width('100%');
		$('#branches').chosen();
		$('#departments').width('100%');
		$('#departments').chosen();
		$('#month').width('80%');
		$('#month').chosen();	
	});
	
</script>
<div class="row">
	<?php if(!empty($capa_cats)) { 
		$values = $labels = NULL;
	?>
	<div class="col-md-4 text-center">
		<?php
			foreach ($capa_cats as $key => $value) {
				$labels[] = $key;
				$values[] = $value;
			}
			if(array_sum($values)){
		?>
		<h4><?php echo __('CAPA Categorywise'); ?></h4>
		<div class="ct-chart " id="capa_cats"></div>
		<script type="text/javascript">
			var chart = new Chartist.Pie('#capa_cats', {
			  series: <?php echo json_encode($values);?>,
			  labels: <?php echo json_encode($labels);?>
			}, {
			  donut: true,
			  showLabel: true,
			  labelDirection: 'middle',
			  chartPadding: {left: 40, right:40},
			});

			chart.on('draw', function(data) {
			  if(data.type === 'slice') {
			    var pathLength = data.element._node.getTotalLength();
				data.element.attr({
			      'stroke-dasharray': pathLength + 'px ' + pathLength + 'px'
			    });
				var animationDefinition = {
			      'stroke-dashoffset': {
			        id: 'anim' + data.index,
			        dur: 400,
			        from: -pathLength + 'px',
			        to:  '0px',
			        easing: Chartist.Svg.Easing.easeOutQuint,
			        fill: 'freeze'
			      }
			    };
				if(data.index !== 0) {
			      animationDefinition['stroke-dashoffset'].begin = 'anim' + (data.index - 1) + '.end';
			    }
				data.element.attr({
			      'stroke-dashoffset': -pathLength + 'px'
			    });
				data.element.animate(animationDefinition, false);
			  }
			});

			
		</script>
		<br />	
		<table class="table table-bordered table-responsive">
			<tr>
				<?php foreach ($labels as $lkey => $lvalue) { ?>
					<th><?php echo $lvalue;?></th>
				<?php } ?>
			</tr><tr>
				<?php foreach ($values as $vkey => $vvalue) { ?>
					<td><?php echo $vvalue;?></td>
				<?php } ?>
			</tr>
		</table>
		 <?php }else{ ?><h4><?php echo __('CAPA Categorywise'); ?></h4><p class="p-height"> No Data </p><?php } ?>	
	</div>
	<?php }?>	
	<?php if(!empty($capa_srcs)) { 
		$values = $labels = NULL;
	?>
	<div class="col-md-4 text-center">
		<?php
			foreach ($capa_srcs as $key => $value) {
				$labels[] = $key;
				$values[] = $value;
			}
			if(array_sum($values)){
		?>
		<h4><?php echo __('CAPA Sourcewise'); ?></h4>
		<div class="ct-chart " id="capa_srcs"></div>
		<script type="text/javascript">
			var chart = new Chartist.Pie('#capa_srcs', {
			  series: <?php echo json_encode($values);?>,
			  labels: <?php echo json_encode($labels);?>
			}, {
			  donut: true,
			  showLabel: true,
			  labelDirection: 'middle',
			  chartPadding: {left: 40, right:40},
			  });
			var data = {
			  series: <?php echo json_encode($values);?>,
			  labels: <?php echo json_encode($labels);?>
			};
			var sum = function(a, b) { return a + b };

			chart.on('draw', function(data) {

			  if(data.type === 'slice') {
			    var pathLength = data.element._node.getTotalLength();
				data.element.attr({
			      'stroke-dasharray': pathLength + 'px ' + pathLength + 'px'
			    });
				var animationDefinition = {
			      'stroke-dashoffset': {
			        id: 'anim' + data.index,
			        dur: 400,
			        from: -pathLength + 'px',
			        to:  '0px',
			        easing: Chartist.Svg.Easing.easeOutQuint,
			        // We need to use `fill: 'freeze'` otherwise our animation will fall back to initial (not visible)
			        fill: 'freeze'
			      }
			    };
				if(data.index !== 0) {
			      animationDefinition['stroke-dashoffset'].begin = 'anim' + (data.index - 1) + '.end';
			    }
				data.element.attr({
			      'stroke-dashoffset': -pathLength + 'px'
			    });
				data.element.animate(animationDefinition, false);
			  }
			});			
		</script>
		<br />
		<table class="table table-bordered table-responsive">
			<tr>
				<?php foreach ($labels as $lkey => $lvalue) { ?>
					<th><?php echo $lvalue;?></th>
				<?php } ?>
			</tr><tr>
				<?php foreach ($values as $vkey => $vvalue) { ?>
					<td><?php echo $vvalue;?></td>
				<?php } ?>
			</tr>
		</table>
		<?php }else{ ?><h4><?php echo __('CAPA Sourcewise'); ?></h4><p class="p-height"> No Data <p><?php } ?>			
	</div>
	<?php }?>
	<?php if(!empty($pros)) { ?>
	<div class="col-md-4 text-center">
		<?php

		$labels = $values = '';
			foreach ($pros as $key => $value) {
				$labels[] = $key;
				$values[] = $value;
			}
			if(array_sum($values)){		
		?>
		<h4><?php echo __('Procuctwise NCs'); ?></h4>
		<div class="ct-chart " id="pros"></div>
		<script type="text/javascript">
			var chart = new Chartist.Pie('#pros', {
			  series: <?php echo json_encode($values);?>,
			  labels: <?php echo json_encode($labels);?>
			}, {
			  donut: true,
			  showLabel: true,
			  labelDirection: 'middle',
			  chartPadding: {left: 40, right:40},
			  });
			var data = {
			  series: <?php echo json_encode($values);?>,
			  labels: <?php echo json_encode($labels);?>
			};
			var sum = function(a, b) { return a + b };

			chart.on('draw', function(data) {

			  if(data.type === 'slice') {
			    var pathLength = data.element._node.getTotalLength();
				data.element.attr({
			      'stroke-dasharray': pathLength + 'px ' + pathLength + 'px'
			    });
				var animationDefinition = {
			      'stroke-dashoffset': {
			        id: 'anim' + data.index,
			        dur: 400,
			        from: -pathLength + 'px',
			        to:  '0px',
			        easing: Chartist.Svg.Easing.easeOutQuint,
			        fill: 'freeze'
			      }
			    };
				if(data.index !== 0) {
			      animationDefinition['stroke-dashoffset'].begin = 'anim' + (data.index - 1) + '.end';
			    }
				data.element.attr({
			      'stroke-dashoffset': -pathLength + 'px'
			    });
				data.element.animate(animationDefinition, false);
			  }
			});			
		</script>
		<br />
		<table class="table table-bordered table-responsive">
			<tr>
				<?php foreach ($labels as $lkey => $lvalue) { ?>
					<th><?php echo $lvalue;?></th>
				<?php } ?>
			</tr><tr>
				<?php foreach ($values as $vkey => $vvalue) { ?>
					<td><?php echo $vvalue;?></td>
				<?php } ?>
			</tr>
		</table>
		<?php }else{ ?><h4><?php echo __('Procuctwise NCs'); ?></h4><p class="p-height"> No Data </p> <?php } ?>			
	</div>
	<?php }?>
	<?php if(!empty($materials)) { ?>
	<div class="col-md-4 text-center">
		<?php
		$labels = $values = '';
			foreach ($materials as $key => $value) {
				$labels[] = $key;
				$values[] = $value;
			}
			if(array_sum($values)){
		?>
		<h4><?php echo __('Materialwise NCs'); ?></h4>
		<div class="ct-chart " id="materials"></div>
		<script type="text/javascript">
			var chart = new Chartist.Pie('#materials', {
			  series: <?php echo json_encode($values);?>,
			  labels: <?php echo json_encode($labels);?>
			}, {
			  donut: true,
			  showLabel: true,
			  labelDirection: 'middle',
			  chartPadding: {left: 40, right:40},
			  });
			var data = {
			  series: <?php echo json_encode($values);?>,
			  labels: <?php echo json_encode($labels);?>
			};
			var sum = function(a, b) { return a + b };

			chart.on('draw', function(data) {

			  if(data.type === 'slice') {
			    var pathLength = data.element._node.getTotalLength();
				data.element.attr({
			      'stroke-dasharray': pathLength + 'px ' + pathLength + 'px'
			    });
				var animationDefinition = {
			      'stroke-dashoffset': {
			        id: 'anim' + data.index,
			        dur: 400,
			        from: -pathLength + 'px',
			        to:  '0px',
			        easing: Chartist.Svg.Easing.easeOutQuint,
			        fill: 'freeze'
			      }
			    };
				if(data.index !== 0) {
			      animationDefinition['stroke-dashoffset'].begin = 'anim' + (data.index - 1) + '.end';
			    }
				data.element.attr({
			      'stroke-dashoffset': -pathLength + 'px'
			    });
				data.element.animate(animationDefinition, false);
			  }
			});			
		</script>
		<br />
		<table class="table table-bordered table-responsive">
			<tr>
				<?php foreach ($labels as $lkey => $lvalue) { ?>
					<th><?php echo $lvalue;?></th>
				<?php } ?>
			</tr><tr>
				<?php foreach ($values as $vkey => $vvalue) { ?>
					<td><?php echo $vvalue;?></td>
				<?php } ?>
			</tr>
		</table>	
		<?php }else{ ?> <h4><?php echo __('Materialwise NCs'); ?></h4> <p class="p-height">No Data </p><?php } ?>		
	</div>
	<?php }?>
	
	</div>
	
	<div class="row">
		<div class="col-md-6">
			<h3>Processes</h3>
			<table class="table table-bordered table-responsive">
			<?php foreach ($ncProcess as $ncProces) { ?>
				<tr>
					<td><?php echo $ncProces['Process']['title'];?></td>
					<td><?php echo $ncProces['CorrectivePreventiveAction']['name'];?></td>
					<td><?php echo $ncProces['CorrectivePreventiveAction']['current_status'];?></td>
				</tr>	
			<?php } ?>
			</table>
		</div>
		<div class="col-md-6">
			<h3>Risks</h3>
			<table class="table table-bordered table-responsive">
			<?php foreach ($ncRisks as $ncRisk) { ?>
				<tr>
					<td><?php echo $ncRisk['RiskAssessment']['title'];?></td>
					<td><?php echo $ncRisk['CorrectivePreventiveAction']['name'];?></td>
					<td><?php echo $ncRisk['CorrectivePreventiveAction']['current_status'];?></td>
				</tr>	
			<?php } ?>
			</table>
		</div>
	</div>
<script type="text/javascript">
	// $("#src-panel").hide();
	$(".glyphicon").on('click',function(){
		$("#src-panel").slideToggle("slow");
	});
</script>
</div>
