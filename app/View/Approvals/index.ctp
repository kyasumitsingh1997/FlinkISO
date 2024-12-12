<?php echo $this->element('checkbox-script'); ?>

<div id="main"> <?php echo $this->Session->flash(); ?>
	<div class="branches ">
		<h4>Approvals (Pending for approvals) <small class="pull-right"><?php echo $this->Html->link('View Approved Records',array('action'=>'approved')); ?></small></h4>
		<?php //echo $this->element('nav-header-lists', array('postData' => array('pluralHumanName' => 'Approvals', 'modelClass' => 'Approval', 'options' => array("sr_no" => "Sr No", "name" => "Name"), 'pluralVar' => 'approvals'))); ?>
		<script type="text/javascript">
            $(document).ready(function() {
                $('table th a, .pag_list li span a').on('click', function() {
                    var url = $(this).attr("href");
                    $('#main').load(url);
                    return false;
                });
            });
        </script>
        <script>
                    $(document).ready(function(){    
                      $("#filter").keyup(function(){
                          var filter = $(this).val(), count = 0;
                          $(".table .on_page_src").each(function(){
                              if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                                  $(this).hide();
                               } else {             
                                  $(this).show();
                                  count++;
                              }
                          });
                          var numberItems = count;
                          $("#filter-count").text(""+count);
                      });
                  });
                </script>
		<div class="alert alert-danger"><strong>Note : </strong>These records are pending for approval and are locked. You can not edit or delete these records. But as a MR you can unlock the record from "Pending..." button and then either assign them to another user or edit or delete.</div>
		<div class="row">
		<div class="col-md-8">&nbsp;</div>
			<div class="col-md-4">
			<form id="live-search" action="" class="no-padding no-margin" method="post">                    
                      <div class="input-group">
                        <input type="text" class="form-control btn-group pull-left" id="filter" value="" /> 
                        <span id="filter-count" class="text-default input-group-addon">0</span>
                      </div>                    
                  </form>
              </div>
			</div>
		<div class="table-responsive"> <?php echo $this->Form->create(array('class' => 'no-padding no-margin no-background')); ?>
			<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
				<tr>
					<th >Action</th>
					<th><?php echo $this->Paginator->sort('name', __('Name')); ?></th>
					<th><?php echo $this->Paginator->sort('created'); ?></th>
					<th><?php echo $this->Paginator->sort('from'); ?></th>
					<th><?php echo $this->Paginator->sort('to'); ?></th>
					<th><?php echo $this->Paginator->sort('comments'); ?></th>
					<th><?php echo $this->Paginator->sort('status'); ?></th>
				</tr>
				<?php
                if ($approvals) {
                    $x = 0;
                    foreach ($approvals as $approval): ?>
				<tr class="on_page_src">
					<td width="100"><div class="btn-group"> 						
						<?php echo $this->Html->link("view", array('controller' => $approval['Approval']['controller_name'], 'action' => 'view', $approval['Approval']['record']), array( 'class'=>'btn btn-xs btn-info')); ?> 	
						<?php if($approval['Approval']['app_record_status'] == 1){ ?> 
							<span class="btn btn-xs btn-danger"><span class=" glyphicon glyphicon-remove"></span></span>
						<?php } else { ?> 
				
								<?php echo $this->Html->link('<span class=" glyphicon glyphicon-cog"></span>',array('controller' => $approval['Approval']['controller_name'], 'action' => 'edit', $approval['Approval']['record']), array( 'class'=>'btn btn-xs btn-success','escape'=>false)); ?>
								
							
						<?php } ?>
							<script>
							$().ready(function(){$('#countdiv<?php echo $approval['Approval']['id'];?>').load('<?php echo Router::url('/', true); ?>file_uploads/approval_ajax_file_count/<?php echo $approval['Approval']['id'] ?>', function(response, status, xhr){});});</script>
							<div id="countdiv<?php echo $approval['Approval']['id'];?>" class="btn-xs btn btn-primary"></div>
						</div></td>
					<td><?php echo $approval['Approval']['model_name']." (".$approval['Approval']['title'].")"; ?>&nbsp;</td>
					<td><?php echo $approval['Approval']['created'];?>&nbsp;</td>
					<td><?php echo $userList[$approval['Approval']['from']]; ?>&nbsp;</td>
					<td><?php echo $userList[$approval['Approval']['user_id']]; ?>&nbsp;</td>
					<td><?php echo $approval['Approval']['comments']; ?>&nbsp;</td>
					<td>
						<?php if($approval['Approval']['status'] == 'Approved'){ echo "Approved"; }else{?>
						<?php if($this->Session->read('User.is_mr') == true){ ?>
						<div class="dropdown">
						  <?php if($approval['Approval']['app_record_status'] == 1 or $approval['Approval']['record_published'] == 1){ ?> 
						  	<button class="btn btn-default btn-xs dropdown-toggle" type="button" id="dropdownMenu<?php echo $approval['Approval']['id'];?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						    Pending...
						    <span class="caret"></span>
						  </button>
						    <ul class="dropdown-menu pull-right" aria-labelledby="dropdownMenu<?php echo $approval['Approval']['id'];?>">
						    	<li><?php echo $this->Html->Link('Send Reminder to ' . $userList[$approval['Approval']['user_id']],array('controller'=>'approvals','action'=>'send_reminder',$approval['Approval']['id'])); ?></li>
						    	<li><?php echo $this->Html->Link('Unlock Record',array('controller'=>'approvals','action'=>'unlock_record',$approval['Approval']['id'])); ?></li>
						    </ul>	
						    <?php }else { ?>				    
						    <button class="btn btn-default btn-xs dropdown-toggle" type="button" id="dropdownMenu<?php echo $approval['Approval']['id'];?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						    Approved
						    <span class="caret"></span>
						  </button>
						  <?php } ?>
						  
						</div>
						<?php } } ?>
					</td>				
				</tr>
				<?php
                $x++;
                endforeach;
                } else {
            ?>
				<tr>
					<td colspan=13><?php echo __('No results found'); ?></td>
				</tr>
				<?php } ?>
			</table>
			<?php echo $this->Form->end(); ?> </div>
		<p>
			<?php
                echo $this->Paginator->options(array(
                    'update' => '#main',
                    'evalScripts' => true,
                    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
                    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)),
                ));

                echo $this->Paginator->counter(array(
                    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
                ));
            ?>
		</p>
		<ul class="pagination">
			<?php
                echo "<li class='previous'>" . $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled')) . "</li>";
                echo "<li>" . $this->Paginator->numbers(array('separator' => '')) . "</li>";
                echo "<li class='next'>" . $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled')) . "</li>";
            ?>
		</ul>
	</div>
</div>
<?php echo $this->element('export'); ?> <?php echo $this->element('advanced-search', array('postData' => array("name" => "Name"), 'PublishedBranchList' => array($PublishedBranchList))); ?> <?php echo $this->element('import', array('postData' => array("sr_no" => "Sr No", "name" => "Name"))); ?> <?php echo $this->element('approvals'); ?> <?php echo $this->element('common'); ?>
</div>
<?php echo $this->Js->writeBuffer(); ?> 
<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }});
</script>
