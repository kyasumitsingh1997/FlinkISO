<script type="text/javascript">
	$(document).ready(function(){
		$.ajaxSetup({ cache: false });
		$('table th a, .pag_list li span a').on('click', function() {
			var url = $(this).attr("href");
			$('#main_by_approvar').load(url);
			return false;
	});	
});
	$('.user_list').on('click', function() {
		$('#main_by_approvar').load('<?php echo Router::url("/", true); ?><?php echo $this->request->params["controller"] ?>/index/approvals:' + this.id);
	});
</script>
<style>
.users_list{
	margin-left: 0px;
	padding-left: 10px;
}
.users_list li{
	margin-left: 0px;
	padding: 5px 0px;
	list-style: none;
	border-bottom: 1px dotted #ccc;
}
</style>
<?php echo $this->Session->flash();?>
	<div class="fileUploads ">
	<div class="row">
	<div class="col-md-3">
		<div style="padding: 0 10px">		
		<?php
			foreach ($users as $branch => $departments) {
				echo "<h4><strong>".$branch."</strong></h4>";
				foreach ($departments as $department) {
						echo "<h5><strong>".$department['department_name']."</strong></h5>";
						echo "<ul class='users_list'>";
							foreach($department['users'] as $user_key=>$user_name){
								echo "<li>".$this->Html->link($user_name,'#',array('class'=>'user_list','id'=>$user_key,'escape'=>false))."</li>";
							}
						echo "</ul>";
				}
			}
		?>
	</div>
	</div>
	<div class="col-md-9">
		<div  id="main_by_approvar">
		</div>
</div>
<?php echo $this->Js->writeBuffer();?>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
