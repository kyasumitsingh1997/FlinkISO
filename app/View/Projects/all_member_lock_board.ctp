
<h3>All Project Lock Board</h3>
<table class="table table-responsive table-bordered">
<tr>
	<th>Project</th>
	<?php foreach ($dates as $dkey => $dvalue) { ?>
	<th><?php echo $dvalue?></th>	
	<?php  }?>
</tr>
	<?php foreach ($recs as $date => $datas) { ?>
		<tr>
			<td><?php echo $date ?></td>
			<?php foreach ($datas as $data) { ?>
				<td><?php 
				if($data)echo $data;
				else echo 0;
				?>
					<?php $total = $total + $data; ?>
				</td>
			<?php  }?>
		</tr>
	<?php  }?>	
</table>