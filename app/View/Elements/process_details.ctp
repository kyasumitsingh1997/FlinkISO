<ul class="list-group">
	<li class="list-group-item"><strong>Process</strong> : <?php echo $process['Process']['title']; ?></li>
	<li class="list-group-item"><strong>Clauses </strong> : <?php echo $process['Process']['clauses']; ?></li>
	<li class="list-group-item"><strong>Requirments</strong> : <?php echo $process['Process']['process_requirments']; ?></li>
</ul>
<?php if($process['ProcessObjective']) { 
	echo "<h4>Objectives</h4>";
	foreach($process['ProcessObjective'] as $objective): ?>
	<ul class="list-group">
	<li class="list-group-item"><strong>Onjective</strong> : <?php echo $objective['title']; ?></li>
	<li class="list-group-item"><strong>Clauses</strong> : <?php echo $objective['clauses']; ?></li>
	<li class="list-group-item"><strong>Details</strong> : <?php echo $objective['objective']; ?></li>
	<li class="list-group-item"><strong>Desired Output</strong> : <?php echo $objective['desired_output']; ?></li>
</ul>
<?php endforeach;	
} ?>
