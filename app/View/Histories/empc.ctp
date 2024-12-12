<h2>Employee Data Entry Status Between <?php echo date('Y-m-1'); ?> To <?php echo date('Y-m-d'); ?></h2>
<div class="nav-tabs">
	<?php 
		echo '<ul class="nav nav-tabs">';
		$tab = 0;
		foreach ($records as $branch_name => $users) {
			if($tab == 0)$li_active = 'active';
			else $li_active = '';
			echo '<li class='.$li_active.'><a data-toggle="tab" '.$li_active.' href="#'.str_replace(' ','-',$branch_name).'">'.$branch_name.'</a></li>';
			$tab = 1;
		}
		echo '</ul>';
		$tab = 0;
		echo '<div class="tab-content">';
		foreach ($records as $branch_name => $users) {
			if($tab == 0)$tabClass = 'active';
			else $tabClass = '';
			echo '<div id='.str_replace(' ','-',$branch_name).' class="tab-pane '.$tabClass.'">';
			$tab = 1;
			
				echo '<ul class="nav nav-tabs">';
				$div_tab = 0;
					foreach ($users as $name => $record) {
						if($div_tab == 0)$div_li_active = 'active';
						else $div_li_active = '';
						echo '<li class='.$div_li_active.'><a data-toggle="tab" href="#'.str_replace(' ','-',$name).'">'.$name.'</a></li>';
						$div_tab = 1;
						
					}
				echo '</ul>';
				echo '<div class="tab-content">';
				$div = 0;
				foreach ($users as $name => $record) {
					if($div == 0)$divClass = 'active';
					else $divClass = '';
					$div = 1;
					echo '<div id='.str_replace(' ','-',$name).' class="tab-pane '.$divClass.'">';
					echo "<table class='table table-responsive table-bordered'>";
						// echo "<tr><th colspan='2'>".$name."</th><tr>";
							foreach ($record as $key => $value) {
									if($value == 0)echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;".$key."</td><td style='width:100px'><span class='label label-danger'>".$value."</span></td><tr>";	
									else echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;".$key."</td><td style='width:100px'><span class='label label-success'>".$value."</span></td><tr>";	
							}
							echo "</table>";
		echo "</div>";
				}			
			
				echo "</div>";
			echo '</div>';
		}
		echo '</div>';
	?>
</div>