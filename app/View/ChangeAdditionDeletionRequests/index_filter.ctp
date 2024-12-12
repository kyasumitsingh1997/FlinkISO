<?php echo $this->element('checkbox-script'); ?>
<div  id="main" class="nav">
    <div class="col-md-12">
    <?php echo $this->Session->flash(); ?>
    <div class="changeAdditionDeletionRequests ">
        <?php echo $this->element('nav-header-lists', array('postData' => array('pluralHumanName' => 'Change Requests', 'modelClass' => 'ChangeAdditionDeletionRequest', 'options' => array("sr_no" => "Sr No", "request_from" => "Request From", "request_details" => "Request Details", "others" => "Others", "master_list_of_format" => "Master List Of Format", "current_document_details" => "Current Document Details", "reason_for_change" => "Reason For Change"), 'pluralVar' => 'changeAdditionDeletionRequests'))); ?>

        <script type="text/javascript">
            $(document).ready(function() {
                $('table th a, .pag_list li span a').on('click', function() {
                    var url = $(this).attr("href");
                    $('#main').load(url);
                    return false;
                });
            });
        </script>
        <div class="table-responsive">
            <?php echo $this->Form->create(array('class' => 'no-padding no-margin no-background')); ?>
			<div class="help-text"><span class="text-warning">&bull;Under Process</span><span class="text-success">  &bull; Accepted</span><span class="text-danger">  &bull;Rejected</span></div>
            <table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
                <tr>
                    <th width="100"><input type="checkbox" id="selectAll"></th>
                    <th><?php echo $this->Paginator->sort('title'); ?></th>
                    <th><?php echo $this->Paginator->sort('request_from', __('Request From')); ?></th>
                    <th><?php echo $this->Paginator->sort('master_list_of_format', __('Master List of Format')); ?></th>
                    <th><?php echo $this->Paginator->sort('prepared_by'); ?></th>
                    <th><?php echo $this->Paginator->sort('approved_by'); ?></th>
                    <th><?php echo $this->Paginator->sort('Last Updated'); ?></th>
                    <?php if($this->request->params['pass'][0] == 2){ ?>
                    <th><?php echo __('Action');?></th>
                    <?php } ?>
                    <th><?php echo $this->Paginator->sort('publish', __('Publish')); ?></th>
                </tr>
                <?php
                    if ($changeAdditionDeletionRequests) {
                        $x = 0;
                        foreach ($changeAdditionDeletionRequests as $changeAdditionDeletionRequest):

					if($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['document_change_accepted'] == 2){ echo "<tr class='text-warning on_page_src'>";
				?>
                    <td class=" actions">
                        <?php echo $this->element('actions', array('created' => $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['created_by'], 'postVal' => $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['id'], 'softDelete' => $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['soft_delete'])); ?>
                    </td>

				<?php }else{
					if($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['document_change_accepted'] == 1){ ?>
					<tr class="text-success on_page_src">
					<?php } else { ?>
					<tr class="text-danger on_page_src">
					<?php } ?>
                    <td class=" actions">
                        <div class="btn-group" >
                    <?php echo $this->Html->link('View',array('action'=>'view',$changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['id']),array('class'=>'btn  btn-sm btn-default ')); ?>
                    <?php
							$path = Configure::read('MediaPath') . 'files/' . $this->Session->read('User.company_id') . '/upload/' . $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['created_by'] . '/' . $this->params->controller . '/' . $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['id'] . '/';
							$dir = new Folder($path);
							$files = $dir->read(true);

							if (count($files[1]) > 0) {
								?>
										<button type="button" class="btn btn-sm btn-success" style="border-bottom-right-radius:3px; border-top-right-radius:3px; border-left:0px " id='<?php echo $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['id'] ?>-count' data-toggle='tooltip' data-original-title='<?php echo count($files[1]) ?> Evidence Uploaded'>&nbsp;<?php echo count($files[1]) ?></button>
							<?php } else { ?>
										<button type="button" class="btn btn-sm btn-default" style="border-bottom-right-radius:3px; border-top-right-radius:3px; border-left:0px " id='<?php echo $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['id'] ?>-count' data-toggle='tooltip' data-original-title='0 Evidence Uploaded'>&nbsp;0</button>
							<?php } ?>
                            </div>
                         <script>$('#<?php echo $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['id'] ?>-count').tooltip();</script>
                   </td>
				<?php } ?>
                    <td><?php echo h($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['title']); ?>&nbsp;</td>
                    <td>
                        <?php if (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['branch_id']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['branch_id'] != -1) { echo "<strong>Branch:</strong><br />" . h($changeAdditionDeletionRequest['Branch']['name']); ?>
                        <?php } elseif (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['department_id']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['department_id'] != -1) { echo "<strong>Department:</strong><br />" . h($changeAdditionDeletionRequest['Department']['name']); ?>
                        <?php } elseif (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['employee_id']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['employee_id'] != -1) { echo "<strong>Employee:</strong><br />" . h($changeAdditionDeletionRequest['Employee']['name']); ?>
                        <?php } elseif (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['customer_id']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['customer_id'] != -1) { echo "<strong>Customer:</strong><br />" . h($changeAdditionDeletionRequest['Customer']['name']); ?>
                        <?php } elseif (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['suggestion_form_id']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['suggestion_form_id'] != -1) { echo "<strong>Suggestion:</strong><br />" . h($changeAdditionDeletionRequest['SuggestionForm']['title']); ?>
                        <?php
			    } elseif (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['others']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['others'] != ""){
				$needle = "CAPA Number: ";
				$capaCheck = strpos($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['others'], $needle);
				if($capaCheck == 0){
				    $capaNumber = str_replace($needle, '', $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['others']);
				    echo "<strong>CAPA Number: </strong>" . $capaNumber;
				} else {
				    echo "<strong>Other : </strong>" . h($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['others']);
				}
			    }
			?>
                    </td>
                    <td><?php echo h($changeAdditionDeletionRequest['MasterListOfFormat']['title']); ?>&nbsp;</td>                    
                    <td><?php echo h($changeAdditionDeletionRequest['PreparedBy']['name']); ?>&nbsp;</td>
                    <td><?php echo h($changeAdditionDeletionRequest['ApprovedBy']['name']); ?>&nbsp;</td>
					<td><?php echo h($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['modified']); ?>&nbsp;</td>
                    <?php if($this->request->params['pass'][0] == 2){ ?>
                    <td><?php echo $this->Html->link(__('Update'),array('action'=>'edit',$changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['id']),array('class'=>'btn btn-xs btn-warning'));?></td>
                    <?php } ?>
                    <td width="60">
                        <?php if ($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['publish'] == 1) { ?>
                            <span class="fa fa-check"></span>
                        <?php } else { ?>
                            <span class="fa fa-ban"></span>
                        <?php } ?>&nbsp;
                    </td>
                </tr>
                <!-- <tr>
                    <td colspan="2"><?php echo __('Current Document Details'); ?>&nbsp;</td>
                    <td colspan="5"><?php echo __('Proposed Document changes'); ?>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2"><?php echo $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['current_document_details']; ?>&nbsp;</td>
                    <td colspan="5"><?php echo $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['proposed_document_changes']; ?>&nbsp;</td>
                </tr> -->
                <?php
                    $x++;
                    endforeach;
                    } else {
                ?>
                    <tr><td colspan=19><?php echo __('No results found'); ?></td></tr>
                <?php } ?>
            </table>
            <?php echo $this->Form->end(); ?>
        </div>
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
</div>
<?php echo $this->element('export'); ?>
<?php echo $this->element('advanced-search', array('postData' => array("sr_no" => "Sr No", "request_from" => "Request From", "request_details" => "Request Details", "others" => "Others", "master_list_of_format" => "Master List Of Format", "current_document_details" => "Current Document Details", "reason_for_change" => "Reason For Change"), 'PublishedBranchList' => array($PublishedBranchList))); ?>
<?php echo $this->element('import', array('postData' => array("sr_no" => "Sr No", "request_from" => "Request From", "request_details" => "Request Details", "others" => "Others", "master_list_of_format" => "Master List Of Format", "current_document_details" => "Current Document Details", "reason_for_change" => "Reason For Change"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
<?php echo $this->Js->writeBuffer(); ?>
<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }});
</script>
