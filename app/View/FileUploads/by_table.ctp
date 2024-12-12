<script type="text/javascript">
	$(document).ready(function(){
		$('table th a, .pag_list li span a').on('click', function() {
			var url = $(this).attr("href");
			$('#main').load(url);
			return false;
	});

	$('.user_list').on('click', function() {
		$('#main_by_table').load('<?php echo Router::url('/', true); ?><?php echo $this->request->params["controller"] ?>/index/table:' + this.id);
	});
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
		echo "<ul class='users_list'>";
		foreach ($tables as $key => $name) {
			echo "<li>".$this->Html->link($name,'#',array('class'=>'user_list','id'=>$key,'escape'=>false))."</li>";
		}
		echo "</ul>";
		?>
	</div>
	</div>
	<div class="col-md-9">
		<div  id="main_by_table">
		<div class="table-responsive">
		<?php   echo $this->Form->create(array('class'=>'no-padding no-margin no-background'));?>
			<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
				<tr>                                    
				    <th><?php echo $this->Paginator->sort('file_details'); ?></th>
					<th><?php echo $this->Paginator->sort('system_table_id'); ?></th>
				    <th><?php echo $this->Paginator->sort('version'); ?></th>
				    <th><?php echo $this->Paginator->sort('user_id'); ?></th>
				    <th><?php echo $this->Paginator->sort('file_type'); ?></th>
				    <th><?php echo $this->Paginator->sort('file_status'); ?></th>
				    <th><?php echo $this->Paginator->sort('archived'); ?></th>
				    <th><?php echo $this->Paginator->sort('result'); ?></th>
				    <th><?php echo $this->Paginator->sort('comment'); ?></th>
				    <th><?php echo $this->Paginator->sort('publish', __('Publish')); ?></th>
				</tr>
<?php if($fileUploads){ ?>
<?php $x=0;
 foreach ($fileUploads as $fileUpload): ?>
	<tr class="on_page_src">
		<td>
			<?php //$displayPath = Router::url('/').'files/'.$this->Session->read('User.company_id').'/'.$fileUpload['FileUpload']['file_dir'];
				$displayPath = base64_encode(str_replace(DS , '/', $fileUpload['FileUpload']['id']));
			?>
			<?php echo  $this->Html->link($fileUpload['FileUpload']['file_details'], array(
						        'controller' => 'file_uploads',
						        'action' => 'view_media_file',
						        'full_base' => $displayPath
						    ),array('target'=>'_blank','escape'=>TRUE)); ?>&nbsp; 
		</td>
		<td><?php 
			if($fileUpload['FileUpload']['system_table_id'] != 'dashboards') echo h($fileUpload['SystemTable']['name']); 
			else echo $fileUpload['FileUpload']['system_table_id'];
		?>&nbsp;</td>
		<td><?php echo h($fileUpload['FileUpload']['version']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($fileUpload['User']['name'], array('controller' => 'users', 'action' => 'view', $fileUpload['User']['id'])); ?>
		</td>
		<td><?php echo h($fileUpload['FileUpload']['file_type']); ?>&nbsp;</td>
		<td><?php echo ($fileUpload['FileUpload']['file_status'])?'Available':'Deleted'; ?>&nbsp;</td>
		<td><?php echo ($fileUpload['FileUpload']['archived'])?'Yes':'No'; ?>&nbsp;</td>
		<td><?php echo h($fileUpload['FileUpload']['result']); ?>&nbsp;</td>
		<td><?php echo h($fileUpload['FileUpload']['comment']); ?>&nbsp;</td>

		<td width="60">
			<?php if($fileUpload['FileUpload']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php $x++;
 endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=19><?php echo __('No results found');?></td></tr>
<?php } ?>
			</table>
<?php echo $this->Form->end();?>
		</div>
			<p>
			<?php
			echo $this->Paginator->options(array(
			'update' => '#main_by_table',
			'evalScripts' => true,
			'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
			'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)),
			));

			echo $this->Paginator->counter(array(
			'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
			));
			?>			</p>
			<ul class="pagination">
			<?php
		echo "<li class='previous'>".$this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'))."</li>";
		echo "<li>".$this->Paginator->numbers(array('separator' => ''))."</li>";
		echo "<li class='next'>".$this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'))."</li>";
	?>
			</ul>
		</div>
	</div>
	</div>
</div>
</div>
</div>
<?php echo $this->Js->writeBuffer();?>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
