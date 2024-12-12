<div class="fileUploads "  id="fileUploads_panel">
    <div class="table-responsive">

        <table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
            <?php if ($fileUploads) {
                    $x = 0;
                    foreach ($fileUploads as $fileUpload):
            ?>
                <tr>
                    <td>
                        <strong><?php echo h($fileUpload['FileUpload']['file_details']); ?>.<?php echo h($fileUpload['FileUpload']['file_type']); ?></strong>&nbsp;
                        <br />Upload on : <?php echo $this->Time->nice($fileUpload['FileUpload']['created']); ?>
                    </td>
                    <td class="col-md-2"><?php
                        $fileName = $fileUpload['FileUpload']['file_details'] . "." . $fileUpload['FileUpload']['file_type'];
                        ?>
                        <?php echo $this->Form->create('FileUpload', array('action' => 'save_imported_data', 'role' => 'form', 'class' => 'form')); ?>
                        <?php echo $this->Form->hidden('fileDetails', array('value' => 'files/import/' . $fileUpload['FileUpload']['user_id'] . '/' . $controller_name . '/' . $fileName, 'label' => false)); ?>
                        <?php echo $this->Form->hidden('id', array('value' => $fileUpload['FileUpload']['id'], 'label' => false)); ?>
                        <?php echo $this->Form->hidden('company_id', array('value' => $this->Session->read('User.company_id'), 'label' => false)); ?>

                        <?php echo $this->Form->submit(__('Import From File'), array('div' => false, 'class' => 'btn btn-info', 'style' => 'float:none')); ?>
                        <?php echo $this->Form->end(); ?>
                        <?php echo $this->Js->writeBuffer(); ?>

                    </td>
                </tr>
            <?php
                $x++;
                endforeach;
                } else {
            ?>
                <tr><td colspan=19><?php echo __('No results found'); ?></td></tr>
            <?php } ?>
        </table>

    </div>
    <p>
        <?php
            echo $this->Paginator->options(array(
                'update' => '#fileUploads_panel',
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

<?php echo "<div class='alert alert-danger'>File with incorrect data or format will be deleted</div>"; ?>
<?php echo $this->Js->writeBuffer(); ?>