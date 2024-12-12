<div id="trainers_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="trainers form col-md-8">
            <h4><?php echo $this->element('breadcrumbs') . __('View Trainer'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>
                <?php echo $this->Html->link(__('Edit'), '#edit', array('id' => 'edit', 'class' => 'label btn-info', 'data-toggle' => 'modal')); ?>
                <?php echo $this->Html->link(__('Add'), '#add', array('id' => 'add', 'class' => 'label btn-info', 'data-toggle' => 'modal')); ?>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
            </h4>
            <table class="table table-responsive">
                <tr><td><?php echo __('Sr. No'); ?></td>
                    <td>
                        <?php echo $trainer['Trainer']['sr_no']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Trainer Type'); ?></td>
                    <td>
                        <?php echo $trainer['TrainerType']['title']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Name'); ?></td>
                    <td>
                        <?php echo $trainer['Trainer']['name']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Company'); ?></td>
                    <td>
                        <?php echo $trainer['Trainer']['company']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Designation'); ?></td>
                    <td>
                        <?php echo $trainer['Trainer']['designation']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Qualification'); ?></td>
                    <td>
                        <?php echo $trainer['Trainer']['qualification']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Personal Telephone'); ?></td>
                    <td>
                        <?php echo $trainer['Trainer']['personal_telephone']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Office Telephone'); ?></td>
                    <td>
                        <?php echo $trainer['Trainer']['office_telephone']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Mobile'); ?></td>
                    <td>
                        <?php echo $trainer['Trainer']['mobile']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Personal Email'); ?></td>
                    <td>
                        <?php echo $trainer['Trainer']['personal_email']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Office Email'); ?></td>
                    <td>
                        <?php echo $trainer['Trainer']['office_email']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Prepared By'); ?></td>
                    <td>
                        <?php echo h($trainer['PreparedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Approved By'); ?></td>
                    <td>
                        <?php echo h($trainer['ApprovedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Publish'); ?></td>
                    <td>
                        <?php if ($trainer['Trainer']['publish'] == 1) { ?>
                            <span class="fa fa-check"></span>
                        <?php } else { ?>
                            <span class="fa fa-ban"></span>
                        <?php } ?>&nbsp;</td>
                    &nbsp;
                </tr>
           </table>
            <?php echo $this->element('upload-edit', array('usersId' => $trainer['Trainer']['created_by'], 'recordId' => $trainer['Trainer']['id'])); ?>
        </div>
        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#trainers_ajax'))); ?>
    <?php $this->Js->get('#edit'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'edit', $trainer['Trainer']['id'], 'ajax'), array('async' => true, 'update' => '#trainers_ajax'))); ?>
    <?php $this->Js->get('#add'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'lists', null, 'ajax'), array('async' => true, 'update' => '#trainers_ajax'))); ?>
    <?php echo $this->Js->writeBuffer(); ?>
</div>

<script>
    $.ajaxSetup({
        beforeSend: function () {
            $("#busy-indicator").show();
        },
        complete: function () {
            $("#busy-indicator").hide();
        }
    });
</script>
