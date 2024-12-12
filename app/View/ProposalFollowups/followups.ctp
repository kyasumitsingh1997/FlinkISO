<?php echo $this->element('checkbox-script'); ?>
<div  id="main">
    <?php echo $this->Session->flash(); ?>    
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
            <table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
                <tr>
                    <th><?php echo $this->Paginator->sort('proposal_id'); ?></th>
                    <th><?php echo $this->Paginator->sort('customer_id'); ?></th>
                    <th><?php echo $this->Paginator->sort('employee_id'); ?></th>
                    <th><?php echo $this->Paginator->sort('followup_date'); ?></th>
                    <th><?php echo $this->Paginator->sort('followup_heading'); ?></th>
                    <th><?php echo $this->Paginator->sort('next_follow_up_date'); ?></th>
                    <th><?php echo $this->Paginator->sort('status'); ?></th>
                    <th><?php echo $this->Paginator->sort('prepared_by'); ?></th>
                    <th><?php echo $this->Paginator->sort('approved_by'); ?></th>
                    <th><?php echo __('Act');?></th>
                    <th><?php echo $this->Paginator->sort('publish'); ?></th>
                </tr>
                <?php
                    if ($proposalFollowups) {
                        $x = 0;
                        foreach ($proposalFollowups as $proposalFollowup):
                ?>
                <tr class="on_page_src">
                    <td>
                        <?php echo $this->Html->link($proposalFollowup['Proposal']['title'], array('controller' => 'proposals', 'action' => 'view', $proposalFollowup['Proposal']['id'])); ?>
                        <?php if($proposalFollowup['ProposalFollowup']['followup_date'] == date('Y-m-d'))echo "<span class='badge badge-info'>Today</span>";?>
                    </td>
                    <td>
                        <?php echo $this->Html->link($proposalFollowup['Customer']['name'], array('controller' => 'customers', 'action' => 'view', $proposalFollowup['Customer']['id'])); ?>
                    </td>
                    <td>
                        <?php echo $this->Html->link($proposalFollowup['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $proposalFollowup['Employee']['id'])); ?>
                    </td>
                    <td><?php echo h($proposalFollowup['ProposalFollowup']['followup_date']); ?>&nbsp;</td>
                    <td><?php echo h($proposalFollowup['ProposalFollowup']['followup_heading']); ?>&nbsp;</td>
                    <td><?php echo h($proposalFollowup['ProposalFollowup']['next_follow_up_date']); ?>&nbsp;</td>
                    <td><span class="label label-info"><?php echo h($proposalFollowup['ProposalFollowup']['status']); ?></span>&nbsp;</td>
                    <td><?php echo h($PublishedEmployeeList[$proposalFollowup['ProposalFollowup']['prepared_by']]); ?>&nbsp;</td>
                    <td><?php echo h($PublishedEmployeeList[$proposalFollowup['ProposalFollowup']['approved_by']]); ?>&nbsp;</td>
                    <td>
                            <div class="btn-group">                                    
                                <?php echo $this->Html->link('Add Follow Ups', array('controller' => 'proposal_followups', 'action' => 'lists', $proposalFollowup['Proposal']['id']), array('class' => 'btn btn-xs btn-danger', 'data-toggle' => 'tooltip', 'data-placement' => 'bottom', 'data-original-title' => 'Add Follow up')); ?>                                
                            </div>&nbsp;                        
                    </td>
                    <td width="60">
                        <?php if ($proposalFollowup['ProposalFollowup']['publish'] == 1) { ?>
                            <span class="fa fa-check"></span>
                        <?php } else { ?>
                            <span class="fa fa-ban"></span>
                        <?php } ?>&nbsp;</td>
                </tr>
                <?php
                    $x++;
                    endforeach;
                    } else {
                ?>
                <tr><td colspan=20><?php echo __('No results found'); ?></td></tr>
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
<?php echo $this->Js->writeBuffer(); ?>
<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>
