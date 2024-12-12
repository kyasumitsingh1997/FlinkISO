<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="evaluationCriterias ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'EvaluationCriterias','modelClass'=>'EvaluationCriteria','options'=>array("sr_no"=>"Sr No","name"=>"Name","scale_1"=>"Scale 1","scale_1_value"=>"Scale 1 Value","scale_2"=>"Scale 2","scale_2_value"=>"Scale 2 Value","scale_3"=>"Scale 3","scale_3_value"=>"Scale 3 Value","scale_4"=>"Scale 4","scale_4_value"=>"Scale 4 Value","scale_5"=>"Scale 5","scale_5_value"=>"Scale 5 Value","scale_6"=>"Scale 6","scale_6_value"=>"Scale 6 Value","scale_7"=>"Scale 7","scale_7_value"=>"Scale 7 Value","scale_8"=>"Scale 8","scale_8_value"=>"Scale 8 Value","scale_9"=>"Scale 9","scale_9_value"=>"Scale 9 Value","scale_10"=>"Scale 10","scale_10_value"=>"Scale 10 Value","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'evaluationCriterias'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Evaluation Criteria'), array('action' => 'add_ajax')); ?></li>
					<li><?php // echo $this->Html->link(__('Add Aspect Category'), array('controller' => 'aspect_categories', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Prepared By'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="aspects_tab_ajax"></div>
</div>

<script>
  $(function() {
    $( "#tabs" ).tabs({
      beforeLoad: function( event, ui ) {
	ui.jqXHR.error(function() {
	  ui.panel.html(
	    "Error Loading ... " +
	    "Please contact administrator." );
	});
      }
    });
  });
</script>

<?php echo $this->element('export'); ?>
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","scale_1"=>"Scale 1","scale_1_value"=>"Scale 1 Value","scale_2"=>"Scale 2","scale_2_value"=>"Scale 2 Value","scale_3"=>"Scale 3","scale_3_value"=>"Scale 3 Value","scale_4"=>"Scale 4","scale_4_value"=>"Scale 4 Value","scale_5"=>"Scale 5","scale_5_value"=>"Scale 5 Value","scale_6"=>"Scale 6","scale_6_value"=>"Scale 6 Value","scale_7"=>"Scale 7","scale_7_value"=>"Scale 7 Value","scale_8"=>"Scale 8","scale_8_value"=>"Scale 8 Value","scale_9"=>"Scale 9","scale_9_value"=>"Scale 9 Value","scale_10"=>"Scale 10","scale_10_value"=>"Scale 10 Value","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","scale_1"=>"Scale 1","scale_1_value"=>"Scale 1 Value","scale_2"=>"Scale 2","scale_2_value"=>"Scale 2 Value","scale_3"=>"Scale 3","scale_3_value"=>"Scale 3 Value","scale_4"=>"Scale 4","scale_4_value"=>"Scale 4 Value","scale_5"=>"Scale 5","scale_5_value"=>"Scale 5 Value","scale_6"=>"Scale 6","scale_6_value"=>"Scale 6 Value","scale_7"=>"Scale 7","scale_7_value"=>"Scale 7 Value","scale_8"=>"Scale 8","scale_8_value"=>"Scale 8 Value","scale_9"=>"Scale 9","scale_9_value"=>"Scale 9 Value","scale_10"=>"Scale 10","scale_10_value"=>"Scale 10 Value","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>