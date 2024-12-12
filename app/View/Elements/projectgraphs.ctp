<div class="clear-fix">
		<div class="row">
			<div class="col-md-12">
				<ul class="list-group">
					<li class="list-group-item"><h3 class="text-center">Cost Category Wise (Estimated)</h3></li>
					<li class="list-group-item">
						<div style="width:100%"><canvas id="cost-cat-wise"></canvas></div>

							<script>
								<?php
								$data = $labels = $colors = ''; 
								foreach ($projectEstimates as $head) { 
									$data[] = $head['ProjectEstimate']['cost'];
									$labels[] = $head['CostCategory']['name'];
									$colors[] = '#'.str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
									} 
								?>
								var config1 = {
									type: 'pie',
									data: {
										datasets: [{
											data: <?php echo json_encode($data,JSON_NUMERIC_CHECK);?>,
											backgroundColor:<?php echo json_encode($colors);?>,
											label: 'Dataset 1'
										}],							
										labels: <?php echo json_encode($labels);?>
									},
									options: {
										responsive: true,
										legend: {
											fullWidth : true,
											display: true,
											position: 'bottom',
											labels: {
												// fontColor: 'rgb(255, 99, 132)'
											}
										},
									}
								};

								window.onload = function() {
									
								};

								
							</script>
						</li>
					</ul>
				</div>
		
		<?php foreach ($project_details['PurchaseOrder']['out'] as $purchaseOrder) { 
			foreach ($costCategories as $key => $value) {
				if($purchaseOrder['PurchaseOrder']['cost_category_id'] == $key){
					$res[$value] = $res[$value] + $purchaseOrder['PurchaseOrder']['po_total'];
				}			
			}
		} 
		?>

<?php foreach ($projectTimesheets as $projectTimesheet) { 
	$final[$projectTimesheet['User']['name']] = $final[$projectTimesheet['User']['name']] + $projectTimesheet['ProjectTimesheet']['total_cost'];
		$mandays = $mandays + $projectTimesheet['ProjectTimesheet']['total'];
		$mandaycost = $mandaycost + $projectTimesheet['ProjectTimesheet']['total_cost'];
	} ?>		
			<div class="col-md-12">
				<ul class="list-group">
					<li class="list-group-item"><h3 class="text-center">Cost Categoty Wise (Actual)</h3></li>
					<li class="list-group-item">						
						<div style="width:100%"><canvas id="PO-wise"></canvas></div>						
						<script>
							<?php 
							$data = $labels = $colors = '';
							foreach ($res as $key => $value) { 
								$data[] = $value;
								$labels[] = $key;
								$colors[] = '#'.str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
								}

								$labels[] = 'Resource Cost';
								$data[] = $mandaycost;
								$colors[] = '#'.str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT); 
							?>
							var config3 = {
								type: 'pie',
								data: {
									datasets: [{
										data: <?php echo json_encode($data,JSON_NUMERIC_CHECK);?>,
										backgroundColor:<?php echo json_encode($colors);?>,
										label: 'Dataset 2'
									}],							
									labels: <?php echo json_encode($labels);?>
								},
								options: {
									responsive: true,
									legend: {
										fullWidth : true,
										display: true,
										position: 'bottom',
										labels: {
											// fontColor: 'rgb(255, 99, 132)'
										}
									},
								}
							};
						</script>
					</li>
				</ul>
			</div>
		<!-- </div> -->
	<!-- </div> -->

			<div class="col-md-12">
				<ul class="list-group">
					<li class="list-group-item"><h3 class="text-center">Resource Cost Wise (Estimated)</h3></li>
					<li class="list-group-item">
						<div style="width:100%">
							<canvas id="resource-wise"></canvas>
						</div>

						<script>
							<?php 
							$data = $labels = $colors = '';
							foreach ($projectResources as $projectResource) { 
								$data[] = $projectResource['ProjectResource']['resource_cost'];
								$labels[] = $projectResource['User']['name'];
								$colors[] = '#'.str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
								} 
							?>
							var config = {
								type: 'pie',
								data: {
									datasets: [{
										data: <?php echo json_encode($data,JSON_NUMERIC_CHECK);?>,
										backgroundColor:<?php echo json_encode($colors);?>,
										label: 'Dataset 2'
									}],							
									labels: <?php echo json_encode($labels);?>
								},
								options: {
									responsive: true,
									legend: {
										fullWidth : true,
										display: true,
										position: 'bottom',
										labels: {
											// fontColor: 'rgb(255, 99, 132)'
										}
									},
								}
							};
						</script>
					<!-- </div> -->
				</li>
			</ul>
		</div>
			<div class="col-md-12">
				<ul class="list-group">
					<li class="list-group-item"><h3 class="text-center">Resource Manpower Wise (Estimated)</h3></li>
					<li class="list-group-item">
						<div style="width:100%">
							<canvas id="resource-man-wise"></canvas>
						</div>

						<script>
							<?php 
							$data = $labels = $colors = '';
							foreach ($projectResources as $projectResource) { 
								$data[] = $projectResource['ProjectResource']['mandays'];
								$labels[] = $projectResource['User']['name'];
								$colors[] = '#'.str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
								} 
							?>
							var config2 = {
								type: 'pie',
								data: {
									datasets: [{
										data: <?php echo json_encode($data,JSON_NUMERIC_CHECK);?>,
										backgroundColor:<?php echo json_encode($colors);?>,
										label: 'Dataset 2'
									}],							
									labels: <?php echo json_encode($labels);?>
								},
								options: {
									responsive: true,
									legend: {
										fullWidth : true,
										display: true,
										position: 'bottom',
										labels: {
											// fontColor: 'rgb(255, 99, 132)'
										}
									},
								}
							};
						</script>
					<!-- </div> -->
				</li>
			</ul>
		</div>
	<!-- </div> -->
	
	<?php foreach ($projectTimesheets as $projectTimesheet) { 
		$resc[$projectTimesheet['User']['name']] = $res[$projectTimesheet['User']['name']] + $projectTimesheet['ProjectTimesheet']['total_cost'];
		$rest[$projectTimesheet['User']['name']] = $res[$projectTimesheet['User']['name']] + $projectTimesheet['ProjectTimesheet']['total'];
	} ?>
	<div class="col-md-12">
		<ul class="list-group">
			<li class="list-group-item"><h3 class="text-center">Cost Resource Wise (Actual)</h3></li>
			<li class="list-group-item"><div style="width:100%"><canvas id="Re-wise"></canvas></div>
				<script>
					<?php 
					$data = $labels = $colors = '';
					foreach ($resc as $key => $value) { 
						$data[] = $value;
						$labels[] = $key;
						$colors[] = '#'.str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
						} 
					?>
					var config4 = {
						type: 'pie',
						data: {
							datasets: [{
								data: <?php echo json_encode($data,JSON_NUMERIC_CHECK);?>,
								backgroundColor:<?php echo json_encode($colors);?>,
								label: 'Dataset 2'
							}],							
							labels: <?php echo json_encode($labels);?>
						},
						options: {
							responsive: true,
							legend: {
								fullWidth : true,
								display: true,
								position: 'bottom',
								labels: {
									// fontColor: 'rgb(255, 99, 132)'
								}
							},
						}
					};
				</script>
			<!-- </div> -->
		</li>
	</ul>
</div>

<div class="col-md-12">
		<?php
			foreach ($final as $key => $value) {
				$flabels[] = $key;
				$fdata[] = $value;
				$fcolors[] = '#'.str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
			}

		?>
		<ul class="list-group">
			<li class="list-group-item"><h3 class="text-center">Costwise Breakup (Actual)</h3></li>
			<li class="list-group-item">
				<div style="width:100%"><canvas id="fcost-wise"></canvas></div>

					<script>
						var config6 = {
							type: 'pie',
							data: {
								datasets: [{
									data: <?php echo json_encode($fdata,JSON_NUMERIC_CHECK);?>,
									backgroundColor:<?php echo json_encode($fcolors);?>,
									label: 'Dataset 1'
								}],							
								labels: <?php echo json_encode($flabels);?>
							},
							options: {
								responsive: true,
								legend: {
									fullWidth : true,
									display: true,
									position: 'bottom',
									labels: {
										// fontColor: 'rgb(255, 99, 132)'
									}
								},
							}
						};

						// window.onload = function() {
						// 	var ctx6 = document.getElementById('fcost-wise').getContext('2d');
						// 	window.myPie6 = new Chart(ctx6, config6);
						// };

						
					</script>
				</li>
			</ul>
		</div>
	</div>
</div>
<script type="text/javascript">
	window.onload = function() {
		var ctx1 = document.getElementById('cost-cat-wise').getContext('2d');
		window.myPie1 = new Chart(ctx1, config1);
		
		var ctx2 = document.getElementById('resource-wise').getContext('2d');
		window.myPie2 = new Chart(ctx2, config);

		var ctx3 = document.getElementById('resource-man-wise').getContext('2d');
		window.myPie3 = new Chart(ctx3, config2);

		var ctx4 = document.getElementById('PO-wise').getContext('2d');
		window.myPie4 = new Chart(ctx4, config3);

		var ctx5 = document.getElementById('Re-wise').getContext('2d');
		window.myPie5 = new Chart(ctx5, config4);

		var ctx6 = document.getElementById('fcost-wise').getContext('2d');
		window.myPie6 = new Chart(ctx6, config6);
	};
</script>