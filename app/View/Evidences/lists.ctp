<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="evidences ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Documents','modelClass'=>'Evidence','options'=>array("sr_no"=>"Sr No","description"=>"Description","model_name"=>"Model Name","record"=>"Record","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'evidences'))); ?>
		<div class="alert alert-info">Note : These are only evidence files and do not require document number, issue number, revision etc. Document versioning is done automatically via in-build version control system based on file names. System will automatically add <strong>-ver-x</strong> at the end of each file you upload. To add files related to list of formats,quality document, click <?php echo $this->Html->link('here',array('controller'=>'master_list_of_formats','action'=>'lists'));?>.</div>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Document'), array('action' => 'add_ajax','model'=>$this->request->params['named']['model'],'record'=>$this->request->params['named']['record'],'record_type'=>$this->request->params['named']['record_type'])); ?></li>
					<li><?php // echo $this->Html->link(__('Add Approved By'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add File Upload'), array('controller' => 'file_uploads', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add User Session'), array('controller' => 'user_sessions', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Status User Id'), array('controller' => 'users', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="evidences_tab_ajax"></div>
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

<?php //echo $this->element('export'); ?>
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","description"=>"Description","model_name"=>"Model Name","record"=>"Record","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","description"=>"Description","model_name"=>"Model Name","record"=>"Record","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
