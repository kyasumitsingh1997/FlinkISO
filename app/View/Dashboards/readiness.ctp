<div id="get_readyness">
<script>
    $('document').ready(function() {
    	$('#busy-indicator-readiness').hide();
        $("#month").change(function() {
            var date_value = $(this).val();
            window.location = '<?php echo Router::url('/', true); ?>dashboards/readiness/' + date_value;
        });
        $('#month').chosen();
    });


</script>
<?php if(isset($readiness_count)){ ?>
<?php 
	
	$all = count($readiness_count['static']) + count($readiness_count['recurring']);
	$fail = 0;
	foreach ($readiness_count as $type => $type_value) {		
		foreach ($type_value as $key => $value) {
			if($value['count'] < $value['required']){
				$fail++;
			}
	}

	} 
	$recod_readiness =  round(100 - (($fail * 100) / $all));
	
	$fail = 0;
	$all = count($file_readiness_count);
	foreach ($file_readiness_count as $key => $value) {
		if($value['count'] == $value['files']){
			$fail++;
		}
	} 
	$file_readiness = round((($fail * 100) / $all));
	$total_readiness = round(($readiness + $recod_readiness + $file_readiness) / 3); 
	mkdir(Configure::read('MediaPath') . "/files/" . $this->Session->read('User.company_id') . DS . date('Y-m',strtotime($month)) , 0777);
	$file = fopen(Configure::read('MediaPath') . "/files/" . $this->Session->read('User.company_id') . DS . date('Y-m',strtotime($month)) . DS . "/rediness.txt", "w");
	fwrite($file, $total_readiness);
	fclose($file);
?>
<h3><small><?php echo $this->Html->link('Dashboard',array('controller'=>'users','action'=>'dashboard'));?> / </small><?php echo __('Readiness in ') . date('M-Y',strtotime($month))?> <span class="badge label label-danger"><?php echo $total_readiness; ?> % </span></h3>
<div class="row">
	<div class="col-md-6">
		<?php
            $end_date = date('Y-m-1');
            $date = date("Y-m-d", strtotime("-11 month", strtotime($end_date)));
            while ($date < $end_date) {
                $options[date('Y', strtotime($end_date))][date('Y-m', strtotime($end_date))] = date('M-Y', strtotime($end_date));
                $end_date = date("Y-m-d", strtotime("-1 month", strtotime($end_date)));
            }
            echo $this->Form->input('month', array('id' => 'month', 'label' => __('Change Month'), 'options' => $options));
            ?>
	</div>
	<?php 
          //$month = date('Y-m'); 
          $previous_month = date('Y-m',strtotime('-1 month',strtotime($month)));
          $next_month = date('Y-m',strtotime('+1 month',strtotime($month)));
          $this_month = date('Y-m');
        ?>
	<div class="col-md-6 text-right">
      <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator-readiness')); ?>
      <span class="btn-group btn-group-xs" role="group">
        <?php         
        echo $this->Js->link('<span class="glyphicon glyphicon-step-backward"></span>', array('controller'=>'dashboards','action'=>'readiness',$previous_month), array('type'=>'button', 'class'=>'btn  btn-info', 'escape'=>false, 'update' => '#get_readyness','evalScripts' => true,'before' => $this->Js->get('#busy-indicator-readiness')->effect('fadeIn',array('buffer' => false)),'complete' => $this->Js->get('#busy-indicator-readiness')->effect('fadeOut',array('buffer' => false)),'htmlAttributes' => array('month' => date('Y-m')))); ?>
        <?php echo $this->Js->link('<span class="glyphicon glyphicon-pause"></span>', array('controller'=>'dashboards','action'=>'readiness',date('Y-m')), array('type'=>'button', 'class'=>'btn btn-info', 'escape'=>false, 'update' => '#get_readyness','evalScripts' => true,'before' => $this->Js->get('#busy-indicator-readiness')->effect('fadeIn',array('buffer' => false)),'complete' => $this->Js->get('#busy-indicator-readiness')->effect('fadeOut',array('buffer' => false)),'htmlAttributes' => array('month' => date('Y-m')))); ?>
        <?php echo $this->Js->link('<span class="glyphicon glyphicon-step-forward"></span>', array('controller'=>'dashboards','action'=>'readiness',$next_month), array('type'=>'button', 'class'=>'btn btn-info', 'escape'=>false, 'update' => '#get_readyness','evalScripts' => true,'before' => $this->Js->get('#busy-indicator-readiness')->effect('fadeIn',array('buffer' => false)),'complete' => $this->Js->get('#busy-indicator-readiness')->effect('fadeOut',array('buffer' => false)),'htmlAttributes' => array('month' => date('Y-m')))); ?>
        <?php echo $this->Js->writeBuffer(); ?>
      </span>
    </div>
</div>
<div class="row">
	<div class="col-md-4">
		<h4><?php echo __('Suggestions') ?></h4>
		<p>we recommend atleast one user per branch / department.</p>
		<ul class="list-group">
			<?php 			
				foreach ($users_branch['Branch'] as $key => $value) {
					if($value < $users_branch['Count']){
						echo "<li class='list-group-item text-danger'>You have very few users in " . $key . " branch.";
						echo $this->Html->link('Add Users',array('controller'=>'users','action'=>'lists'),array('class'=>'btn btn-xs btn-danger pull-right')) . "</li>";
						}else{ "<li class='list-group-item text-success'>" . $key . ' branch has sufficent number of users </li>';}
				}		
				foreach ($employees_branch['Branch'] as $key => $value) {
					if($value == 0){
						echo "<li class='list-group-item text-danger'>You have no employees under " . $key . " branch.";
						echo $this->Html->link('Add Employees',array('controller'=>'employees','action'=>'lists'),array('class'=>'btn btn-xs btn-danger pull-right')) . "</li>";
					}
				}
				foreach ($users_department as $key => $value) {
					if($value == 0){
						echo "<li class='list-group-item text-danger'>You have no users under " . $key . " department.";
						echo $this->Html->link('Add Users',array('controller'=>'users','action'=>'lists'),array('class'=>'btn btn-xs btn-danger pull-right')) . "</li>";
					}
				}
			?>
		</ul>
	</div>
	<div class="col-md-4">
		<h4><?php echo __('Record Readiness') ?></h4>
		<div class="progress">
  			<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $recod_readiness; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $recod_readiness; ?>%"><?php echo $recod_readiness; ?>%
    			<span class="sr-only"><?php echo $recod_readiness; ?></span>
  			</div>
		</div>
		<div class="list-group">
			<?php 
			foreach ($readiness_count as $type => $type_value) {
				foreach ($type_value as $key => $value) {
					if($value['count'] < $value['required']){
						$message = "Few or no records found under <strong>" . Inflector::humanize(Inflector::underscore($key)) ."</strong>";
						$class = "danger";
	            	}else{
	            		$message = "<strong>" . Inflector::humanize(Inflector::underscore($key)) . "</strong> has sufficent records";	
	            		$class = "success";
	            	}
					echo "<li class='list-group-item text-".$class."'>".$message."<span class='badge lable label-".$class."'>".$value['count']."/".$value['required']."</span> </li>";
				}
			} ?>
		</div>
	</div>

	<div class="col-md-4">
		<h4><?php echo __('File Upload Readiness') ?></h4>
		<div class="progress">
  			<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $file_readiness; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $file_readiness; ?>%"><?php echo $file_readiness; ?>%
    			<span class="sr-only"><?php echo $file_readiness; ?></span>
  			</div>
		</div>
		<div class="list-group">
			<?php foreach ($file_readiness_count as $key => $value) {				
				if($value['count'] < $value['files'])$class = "warning";
				elseif($value['count'] == $value['files'])$class = "success";
				else $class = "danger";
				echo "<li class='list-group-item text-".$class."'>"."You have " . $value['count'] . " records under <strong>" . Inflector::humanize(Inflector::underscore($key)) . "</strong> and " . $value['files'] . " Files" ."<span class='badge lable label-".$class."'>".$value['files']."</span> </li>";
			} ?>
		</div>
	</div>
</div>
<?php } ?>
<h3><?php echo __('Document Readiness') ?></h3>
<div class="row">
	<div class="col-md-12">
		<?php echo $this->element('readiness')?>
	</div>
</div>
</div>
