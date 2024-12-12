<?php echo $this->element('checkbox-script'); ?>

<div  id="main">
    <?php echo $this->Session->flash(); ?>
    <div class="housekeepingResponsibilities ">
        <?php echo $this->element('nav-header-lists', array('postData' => array('pluralHumanName' => 'Housekeeping Responsibilities', 'modelClass' => 'HousekeepingResponsibility', 'options' => array("sr_no" => "Sr No", "description" => "Description"), 'pluralVar' => 'housekeepingResponsibilities'))); ?>

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
                    <th><input type="checkbox" id="selectAll"></th>
                    <th><?php echo $this->Paginator->sort('housekeeping_checklist_id', __('Housekeeping Checklist')); ?></th>
                    <th><?php echo $this->Paginator->sort('employee_id', __('Employee')); ?></th>
                    <th><?php echo $this->Paginator->sort('schedule_id', __('Schedule')); ?></th>
                    <th><?php echo $this->Paginator->sort('prepared_by'); ?></th>
                    <th><?php echo $this->Paginator->sort('approved_by'); ?></th>
                    <th><?php echo $this->Paginator->sort('publish', __('Publish')); ?></th>
                </tr>
                <?php
                    if ($housekeepingResponsibilities) {
                        $x = 0;
                        foreach ($housekeepingResponsibilities as $housekeepingResponsibility):
                ?>
                <tr class="on_page_src">
                    <td class=" actions">
                        <?php echo $this->element('actions', array('created' => $housekeepingResponsibility['HousekeepingResponsibility']['created_by'], 'postVal' => $housekeepingResponsibility['HousekeepingResponsibility']['id'], 'softDelete' => $housekeepingResponsibility['HousekeepingResponsibility']['soft_delete'])); ?>
                    </td>
                    <td>
                        <?php echo $this->Html->link($housekeepingResponsibility['HousekeepingChecklist']['title'], array('controller' => 'housekeeping_checklists', 'action' => 'view', $housekeepingResponsibility['HousekeepingChecklist']['id'])); ?>
                    </td>
                    <td>
                        <?php echo $this->Html->link($housekeepingResponsibility['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $housekeepingResponsibility['Employee']['id'])); ?>
                    </td>
                    <td>
                        <?php echo $this->Html->link($housekeepingResponsibility['Schedule']['name'], array('controller' => 'schedules', 'action' => 'view', $housekeepingResponsibility['Schedule']['id'])); ?>
                    </td>
                    <td><?php echo h($PublishedEmployeeList[$housekeepingResponsibility['HousekeepingResponsibility']['prepared_by']]); ?>&nbsp;</td>
                    <td><?php echo h($PublishedEmployeeList[$housekeepingResponsibility['HousekeepingResponsibility']['approved_by']]); ?>&nbsp;</td>
                    <td width="60">
                        <?php if ($housekeepingResponsibility['HousekeepingResponsibility']['publish'] == 1) { ?>
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
                <tr><td colspan=16><?php echo __('No results found'); ?></td></tr>
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

<?php echo $this->element('export'); ?>
<?php echo $this->element('advanced-search', array('postData' => array("sr_no" => "Sr No", "description" => "Description"), 'PublishedBranchList' => array($PublishedBranchList))); ?>
<?php echo $this->element('import', array('postData' => array("sr_no" => "Sr No", "description" => "Description"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
<?php echo $this->Js->writeBuffer(); ?>

<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>
