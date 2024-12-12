<style type="text/css">
	.btn-group .btn-group{padding-top: 0px;}
	.table .color-palette:hover{background-color: #b5bbc8 !important; color: #fff; cursor: pointer !important}
</style>
<div class="row">
	<div class="col-md-12">
		<h2><?php echo __('Audit Calendar'); ?></h2>	
		<div class="btn-group" role="group">
			<?php
				if($this->request->params['named']['type'] == 'branch')$branchClass = 'btn-info'; else $branchClass = 'btn-default';
				if($this->request->params['named']['type'] == 'department')$departmentClass = 'btn-info'; else $departmentClass = 'btn-default';
				if($this->request->params['named']['type'] == 'auditor')$auditorClass = 'btn-info'; else $auditorClass = 'btn-default';
				if($this->request->params['named']['type'] == 'employee')$employeeClass = 'btn-info'; else $employeeClass = 'btn-default';
			?>
			<?php echo $this->Html->link(__('Department-wise'),array(
									'action'=>'audit_cal',
									'type'=>'department',
									'year'=>$this->request->params['named']['year'],
									'audit_type_masters'=>$this->request->params['named']['audit_type_masters'],
									'ie'=>$this->request->params['named']['ie']
									),
								array('class'=>'btn btn-md ' . $departmentClass));?>
			
			<?php echo $this->Html->link(__('Branch-wise'),array(
									'action'=>'audit_cal',
									'type'=>'branch',
									'year'=>$this->request->params['named']['year'],
									'audit_type_masters'=>$this->request->params['named']['audit_type_masters'],
									'ie'=>$this->request->params['named']['ie']
								),array('class'=>'btn btn-md ' . $branchClass));?>
			
			<?php echo $this->Html->link(__('Auditor-wise'),array(
									'action'=>'audit_cal',
									'type'=>'auditor',
									'year'=>$this->request->params['named']['year'],
									'audit_type_masters'=>$this->request->params['named']['audit_type_masters'],
									'ie'=>$this->request->params['named']['ie']
								),array('class'=>'btn btn-md '. $auditorClass));?>

			<?php echo $this->Html->link(__('Employee-wise'),array(
									'action'=>'audit_cal',
									'type'=>'employee',
									'year'=>$this->request->params['named']['year'],
									'audit_type_masters'=>$this->request->params['named']['audit_type_masters'],
									'ie'=>$this->request->params['named']['ie']
								),array('class'=>'btn btn-md '.$employeeClass));?>
			
			<div class="btn-group" role="group">
			    <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			      <?php
			      $ie = array(2=>__('Both'),0=>__('Internal'),1=>__('External'));
			      if(!isset($this->request->params['named']['ie']))echo "Both";
			      else echo $ie[$this->request->params['named']['ie']];?>
			      <span class="caret"></span>
			    </button>
			    <ul class="dropdown-menu">
			    	<?php 
			    	$ie = array(2=>__('Both'),0=>__('Internal'),1=>__('External'));
			    	foreach ($ie as $key => $value) {
			    		echo "<li>". $this->Html->link($value,array('action'=>'audit_cal','type'=>$this->request->params['named']['type'],'year'=>$this->request->params['named']['year'],'audit_type_masters'=>$this->request->params['named']['audit_type_masters'],'ie'=>$key))."</li>";
			    	} ?>		      
			    </ul>
			  </div>
			<div class="btn-group" role="group">
			    <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			      <?php if(!$this->request->params['named']['audit_type_masters'])echo __("Type");
			      else echo $audit_type_masters[$this->request->params['named']['audit_type_masters']];?>
			      <span class="caret"></span>
			    </button>
			    <ul class="dropdown-menu">
			    	<?php foreach ($audit_type_masters as $key => $value) {
			    		echo "<li>". $this->Html->link($value,array('action'=>'audit_cal','type'=>$this->request->params['named']['type'],'year'=>$this->request->params['named']['year'],'audit_type_masters'=>$key,'ie'=>$this->request->params['named']['ie']))."</li>";
			    	} ?>		      
			    </ul>
			</div>

			<div class="btn-group" role="group">
		    <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		      <?php echo $year; ?>
		      <span class="caret"></span>
		    </button>
		    <ul class="dropdown-menu">		    	
		      <li><?php echo $this->Html->link(__('2015'),array('action'=>'audit_cal','type'=>$this->request->params['named']['type'],'year'=>'2015','audit_type_masters'=>$this->request->params['named']['audit_type_masters'],'ie'=>$this->request->params['named']['ie']));?></li>
		      <li><?php echo $this->Html->link(__('2016'),array('action'=>'audit_cal','type'=>$this->request->params['named']['type'],'year'=>'2016','audit_type_masters'=>$this->request->params['named']['audit_type_masters'],'ie'=>$this->request->params['named']['ie']));?></li>
		      <li><?php echo $this->Html->link(__('2017'),array('action'=>'audit_cal','type'=>$this->request->params['named']['type'],'year'=>'2017','audit_type_masters'=>$this->request->params['named']['audit_type_masters'],'ie'=>$this->request->params['named']['ie']));?></li>
		      <li><?php echo $this->Html->link(__('2018'),array('action'=>'audit_cal','type'=>$this->request->params['named']['type'],'year'=>'2018','audit_type_masters'=>$this->request->params['named']['audit_type_masters'],'ie'=>$this->request->params['named']['ie']));?></li>
		      <li><?php echo $this->Html->link(__('2019'),array('action'=>'audit_cal','type'=>$this->request->params['named']['type'],'year'=>'2019','audit_type_masters'=>$this->request->params['named']['audit_type_masters'],'ie'=>$this->request->params['named']['ie']));?></li>
		      <li><?php echo $this->Html->link(__('2020'),array('action'=>'audit_cal','type'=>$this->request->params['named']['type'],'year'=>'2020','audit_type_masters'=>$this->request->params['named']['audit_type_masters'],'ie'=>$this->request->params['named']['ie']));?></li>
		    </ul>
		  </div>		  
		</div>
	</div>
	<div class="col-md-7">		
		<table class="table table table-bordered">
			<tr>
				<th><?php echo __('Auditor')?></th>
				<th><?php echo __('Jan')?></th>
				<th><?php echo __('Feb')?></th>
				<th><?php echo __('Mar')?></th>
				<th><?php echo __('Apr')?></th>
				<th><?php echo __('May')?></th>
				<th><?php echo __('June')?></th>
				<th><?php echo __('July')?></th>
				<th><?php echo __('Aug')?></th>
				<th><?php echo __('Sept')?></th>
				<th><?php echo __('Oct')?></th>
				<th><?php echo __('Nov')?></th>
				<th><?php echo __('Dec')?></th>
				<th><?php echo __('Total')?></th>
			</tr>	
			<?php
			foreach ($data as $auditor => $plans) {
				echo "<tr>";
				echo "<td>" . $auditor ."</td>";
				$total = 0;
				$t = 0;
				$i = 1;	
				foreach ($plans as $plan) {
					if($plan[1] > 0)$class = 'bg-gray disabled color-palette';
					else $class = '';
					echo "<td class='".$class."' id=".base64_encode($auditor)."-".$i."><span class='text-center'>" . $plan[1] ."</span></td>";
					$t = $plan[1];
					$totals[$i][] = $plan[1];
					$i++;
					$total = $total + $plan[1];
				}
				if($total > 0)$class = 'badge badge-success';
				else $class = '';
				if($total>0)echo "<td class='bg-teal disabled color-palette'>" . $total ."</td>";
				else echo "<td>0</td>";
				echo "</tr>";
				
			}
			echo "<tr>";
			echo "<td><strong>Total</strong></td>";
			$x = 0;
			for ($i=1; $i <=12 ; $i++) { 
				$x = $x + array_sum($totals[$i]);
				if(array_sum($totals[$i]) > 0)echo "<td class='bg-green disabled color-palette'>" . array_sum($totals[$i]) . "</td>";
				else echo "<td>0</td>";
			}
			echo "<td class='bg-green-active color-palette'>" . $x . "</td>";
			echo "</tr>";
		?>
		</table>
	</div>
<div class="col-md-5">
	<?php 
		$a = base64_encode(json_encode($this->request->params['named'])); 
		// echo ">>" . $a
	?>
	<div id="audit"><div class="overlay">
            <i class="fa fa-refresh fa-spin"></i>
        </div></div>
</div>
</div>

<script type="text/javascript">
	$().ready(function(){
		$(".fa-spin").hide();
	});
	$(".color-palette").click(function(){
		var url = this.id.split('-');
		$("#audit").load("<?php echo Router::url('/', true); ?>internal_audit_plans/dashboard/month:"+ url[1]+"/key:"+url[0]+"/vars:<?php echo $a?>");
	});

    $.ajaxSetup({beforeSend: function() {
            $(".fa-spin").show();
        }, complete: function() {
            $(".fa-spin").hide();
        }
    });
</script>
