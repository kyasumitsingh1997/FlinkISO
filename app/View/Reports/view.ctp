<div id="reports_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="reports form col-md-8">
            <h4><?php echo __('View Report'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>
                <?php echo $this->Html->link(__('Edit'), '#edit', array('id' => 'edit', 'class' => 'label btn-info', 'data-toggle' => 'modal')); ?>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
            </h4>
            <table class="table table-responsive">
                <tr><td><?php echo __('Sr. No'); ?></td>
                    <td>
                        <?php echo h($report['Report']['sr_no']); ?>
                        &nbsp;
                    </td></tr>
                <tr><td><?php echo __('Title'); ?></td>
                    <td>
                        <?php echo h($report['Report']['title']); ?>
                        &nbsp;
                    </td></tr>
                <tr><td><?php echo __('Branch'); ?></td>
                    <td>
                        <?php echo $this->Html->link($report['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $report['Branch']['id'])); ?>
                        &nbsp;
                    </td></tr>
                <tr><td><?php echo __('Department'); ?></td>
                    <td>
                        <?php echo $this->Html->link($report['Department']['name'], array('controller' => 'departments', 'action' => 'view', $report['Department']['id'])); ?>
                        &nbsp;
                    </td></tr>
                <tr><td><?php echo __('Description'); ?></td>
                    <td>
                        <?php echo h($report['Report']['description']); ?>
                        &nbsp;
                    </td></tr>
                <tr><td><?php echo __('Report Date'); ?></td>
                    <td>
                        <?php echo h($report['Report']['report_date']); ?>
                        &nbsp;
                    </td></tr>
                <tr><td><?php echo __('Publish'); ?></td>
                    <td>
                        <?php if ($report['Report']['publish'] == 1) { ?>
                            <span class="fa fa-check"></span>
                        <?php } else { ?>
                            <span class="fa fa-ban"></span>
                        <?php } ?>&nbsp;
                    </td>&nbsp;
                </tr>
                <tr><td><?php echo __('File'); ?></td>
                    <td>
                        <div class="" style="text-align:center; width:25%;padding: 0px 0px">
                            <h1><div class="glyphicon glyphicon-file text-lg"></div></h1>
                            <h5><?php echo Inflector::Humanize($fileDetails['basename']) ?></h5>
                            <h5><?php                            
                                if ($fileDetails['filesize'] < 1000000) {
                                    echo round($fileDetails['filesize'] / 1024) . 'kb';
                                } else {
                                    echo round($fileDetails['filesize'] / 1024) . 'kb';
                                }
                                ?></h5>
                            <?php 
                                $path = str_replace(Configure::read('MediaPath') , '', $report['Report']['details']);
                                $path = str_replace('files' . DS . $this->Session->read('User.company_id') , '', $report['Report']['details']);
                            ?>
                             <?php echo $this->Html->link('Download', array('controller' => 'file_uploads','action' => 'view_saved_file', 'file_name'=>$fileDetails['basename'], 'path' => base64_encode($path)), array('class' => 'btn btn-md btn-success')); ?>
                        </div>
                    </td>
                </tr>

            </table>

            <?php echo $this->element('upload-edit', array('usersId' => $report['Report']['created_by'], 'recordId' => $report['Report']['id'])); ?>
        </div>
        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
    <?php echo $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#reports_ajax'))); ?>

    <?php echo $this->Js->get('#edit'); ?>
<?php echo $this->Js->event('click', $this->Js->request(array('action' => 'edit', $report['Report']['id'], 'ajax'), array('async' => true, 'update' => '#reports_ajax'))); ?>


<?php echo $this->Js->writeBuffer(); ?>

</div>
<script>$.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }});</script>
