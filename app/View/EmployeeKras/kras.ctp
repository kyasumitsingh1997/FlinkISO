<div class="row">
	<div class="col-md-12">
		<table class="table table-responsive table-bordered">
			<tr>
				<th>Employee</th>
				<th>KRA</th>
				<th>Vertical Domain</th>
				<th>Technical Skills</th>
				<th>Soft Skills</th>
			</tr>
			<?php foreach ($employeeKras as $key => $data) { ?>
				<tr>
					<td><?php echo $employees[$key] ?></td>
					<td><ol><?php 
						foreach ($data as $projectResource) {
							echo "<li>" .$projectResource['ProjectProcessPlan']['process'] . "</li>";
						}
					?></ol></td>
					<td><?php echo $projectResource['Employee']['vertical_domain']?></td>
					<td><?php echo $projectResource['Employee']['technical_skills']?></td>
					<td><?php echo $projectResource['Employee']['soft_skills']?></td>
				</tr>
			<?php } ?>
		</table>
	</div>
</div>