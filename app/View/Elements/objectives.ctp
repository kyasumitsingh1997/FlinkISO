<?php if(isset($get_objectives)){ ?> 
<div class="row">
	<div class="col-md-12">
		<?php 
		if($get_objectives){ ?>
		<h3><?php echo __('Objectives, Processes & Clauses related to this form'); ?></h3>
		<div class="panel-group" id="objective_accordion" role="tablist" aria-multiselectable="true">
		<?php 
			foreach ($get_objectives as $objective) { ?>
		<div class="panel panel-default">
		    <div class="panel-heading" role="tab" id="<?php echo $objective['Objective']['id']; ?>">
		      <h4 class="panel-title">
		        <a data-toggle="collapse" data-parent="#<?php echo $objective['Objective']['id']; ?>" href="#<?php echo $objective['Objective']['id']; ?>" aria-expanded="true" aria-controls="<?php echo $objective['Objective']['id']; ?>">
		          <?php echo $objective['Objective']['title'] ; ?> : <small>(<?php echo $objective['Objective']['clauses'] ; ?>)</small> 
		          <?php echo $this->Html->link('View',array('controller'=>'objectives','action'=>'view',$objective['Objective']['id']),array('class'=>'btn btn-xs btn-primary pull-right'));?>
		        </a>
		      </h4>
		    </div>
		    <div id="<?php echo $objective['Objective']['id']; ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
		      <div class="panel-body">
		        <p><h4><?php echo __('Objective'); ?></h4><?php echo $objective['Objective']['objective'] ; ?></p>
		        <p><h4><?php echo __('Desired Output'); ?></h4><?php echo $objective['Objective']['desired_output'] ; ?></p>
		      </div>
		    </div>
		  </div>		
		<?php	}  ?>
		</div>
	<?php } ?>
	</div>
 </div>
 <br /><hr /><br />
 <?php } ?>
