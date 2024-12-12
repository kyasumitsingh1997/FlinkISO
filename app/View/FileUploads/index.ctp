<style type="text/css">
input[type="checkbox"]{margin-left:-20px !important;}
</style>
<div id="main-index" class="main">	
<?php echo $this->Session->flash();?>

	<div class="fileUploads ">
		<?php echo $this->element('nav-header-lists', array('postData' => array('pluralHumanName' => 'Available Documents', 'modelClass' => 'FileUpload', 'options' => array("sr_no" => "Sr No", "title" => "Title", "document_number" => "Document Number", "issue_number" => "Issue Number", "revision_number" => "Revision Number", "revision_date" => "Revision Date", "prepared_by" => "Prepared By", "approved_by" => "Approved By", "archived" => "Archived"), 'pluralVar' => 'fileUploads'))); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('table th a, .pag_list li span a').on('click', function() {
            var url = $(this).attr("href");
            $('#main-index').load(url);
            return false;
        });
    });
</script>
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
				<tr>                                    
				    <th><?php echo $this->Paginator->sort('file_details'); ?></th>
				    <th><?php echo $this->Paginator->sort('file_type'); ?></th>
				    <th><?php echo $this->Paginator->sort('version'); ?></th>
                  	<th><?php echo $this->Paginator->sort('system_table_id'); ?></th>				    
				    <th><?php echo $this->Paginator->sort('user_id'); ?></th>				    
				    <th><?php echo $this->Paginator->sort('file_status'); ?></th>
				    <th><?php echo $this->Paginator->sort('archived'); ?></th>
				    <th><?php echo $this->Paginator->sort('result'); ?></th>
				    <th><?php echo $this->Paginator->sort('comment'); ?></th>
				    <th><?php echo $this->Paginator->sort('prepared_by'); ?></th>
				    <th><?php echo $this->Paginator->sort('approved_by'); ?></th>
				    <th><?php echo $this->Paginator->sort('created'); ?></th>
				    <th><?php echo __('Date Approved'); ?></th>
				    <th></th>
				    <th><?php echo $this->Paginator->sort('publish', __('Publish')); ?></th>
				</tr>
				<?php 
if($fileUploads){ ?>
<?php $x=0;
 foreach ($fileUploads as $fileUpload): ?>
	<tr class="on_page_src">
                <td>
                    <?php //$displayPath = Router::url('/').'files/'.$this->Session->read('User.company_id').'/'.$fileUpload['FileUpload']['file_dir'];
                    $displayPath = base64_encode(str_replace(DS , '/', $fileUpload['FileUpload']['id']));
                    ?>
<!--                    <a href="<?php echo $displayPath;?>"> <?php echo $fileUpload['FileUpload']['file_details']; ?> </a>-->
                    
                  <?php echo  $this->Html->link($fileUpload['FileUpload']['file_details'], array(
				        'controller' => 'file_uploads',
				        'action' => 'view_media_file',
				        'full_base' => $displayPath
				    ),array('target'=>'_blank','escape'=>TRUE)); ?>
		&nbsp; </td>
		<td><?php echo h($fileUpload['FileUpload']['file_type']); ?>&nbsp;</td>
		<td><?php echo h($fileUpload['FileUpload']['version']); ?>&nbsp;</td>
		<td><?php 
		if($fileUpload['FileUpload']['system_table_id'] != 'dashboards') echo h($fileUpload['SystemTable']['name']); 
		else echo $fileUpload['FileUpload']['system_table_id'];
		?>&nbsp;</td>		
		<td>
			<?php echo $this->Html->link($fileUpload['User']['name'], array('controller' => 'users', 'action' => 'view', $fileUpload['User']['id'])); ?>
		</td>		
		<td><?php echo ($fileUpload['FileUpload']['file_status'])?'Available':'Deleted'; ?>&nbsp;</td>
		<td><?php echo ($fileUpload['FileUpload']['archived'])?'Yes':'No'; ?>&nbsp;</td>
		<td><?php echo h($fileUpload['FileUpload']['result']); ?>&nbsp;</td>
		<td><?php echo h($fileUpload['FileUpload']['comment']); ?>&nbsp;</td>
		<td><?php echo h($fileUpload['PreparedBy']['name']); ?>&nbsp;</td>
		<td><?php echo h($fileUpload['ApprovedBy']['name']); ?>&nbsp;</td>
		<td><?php echo h($fileUpload['FileUpload']['created']); ?>&nbsp;</td>
		<td><?php echo $fileUpload['FinalApproval'];?></td>
		<td width="60"><?php echo $this->Html->link('View',array('action'=>'view',$fileUpload['FileUpload']['id']),array('class'=>'btn btn-xs btn-warning','target'=>'_blank'));?></td>
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
		</div>
			<p>
			<?php
			echo $this->Paginator->options(array(
			'update' => '#main_index',
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
	
<?php //echo $this->Element('file_advanced_search'); ?>
<div class="row hide"><div class="col-md-12"><?php echo $this->Element('file_advanced_search',array('postData'=>array("sr_no"=>"Sr No","record"=>"Record","file_details"=>"File Details","file_type"=>"File Type","file_status"=>"File Status","result"=>"Result"),'PublishedBranchList'=>array($PublishedBranchList))); ?></div></div>
</div>
</div>
</div>

<?php echo $this->element('export'); ?>
<?php echo $this->element('advanced-search', array('postData' => array("sr_no" => "Sr No", "title" => "Title", "document_number" => "Document Number", "issue_number" => "Issue Number", "revision_number" => "Revision Number", "revision_date" => "Revision Date", "prepared_by" => "Prepared By", "approved_by" => "Approved By", "archived" => "Archived"), 'PublishedBranchList' => array($PublishedBranchList))); ?>
<?php echo $this->element('import', array('postData' => array("sr_no" => "Sr No", "title" => "Title", "document_number" => "Document Number", "issue_number" => "Issue Number", "revision_number" => "Revision Number", "revision_date" => "Revision Date", "prepared_by" => "Prepared By", "approved_by" => "Approved By", "archived" => "Archived", 'modelName' => $this->name))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
<?php echo $this->Js->writeBuffer(); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
