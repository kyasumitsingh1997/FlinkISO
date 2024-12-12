<div id="companies_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav  panel panel-default">
        <div class="companies form col-md-8">
            <h4><?php echo __('View Company'); ?>
                <?php echo $this->Html->link(__('Edit'), '#edit', array('id' => 'edit', 'class' => 'label btn-info', 'data-toggle' => 'modal')); ?>
                <?php if ($company['Company']['sample_data'] == 1): ?>
                    <?php echo $this->Form->postLink(__('Remove Sample Data'), array('action' => 'remove_sample', $company['Company']['id']), array('class' => 'label btn-info'), __('Are you sure to remove sample data of %s?', $company['Company']['name'])); ?>
                <?php endif; ?>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
            </h4>
            <table class="table table-responsive">
                <tr>
                    <td colspan="4">
                        <h3><?php echo h($company['Company']['name']); ?></h3>
                        &nbsp;
                    </td>
                </tr>
                <tr><th colspan="4"><h4><?php echo __('Company Description'); ?></h4></th></tr>
                <tr>
                    <td colspan="4">
                        <?php echo html_entity_decode($company['Company']['description']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><th colspan="4"><h4><?php echo __('Welcome Message for Users'); ?></h4></th></tr>
                <tr>
                    <td colspan="4">
                        <?php echo html_entity_decode($company['Company']['welcome_message']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><th colspan="4"><h4><?php echo __('Quality Policy'); ?></h4></th></tr>
                <tr>
                    <td colspan="4">
                        <?php echo html_entity_decode($company['Company']['quality_policy']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><th colspan="4"><h4><?php echo __('Vision Statement'); ?></h4></th></tr>
                <tr>
                    <td colspan="4">
                        <?php echo html_entity_decode($company['Company']['vision_statement']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><th colspan="4"><h4><?php echo __('Mission Statement'); ?></h4></th></tr>
                <tr>
                    <td colspan="4">
                        <?php echo html_entity_decode($company['Company']['mission_statement']); ?>
                        &nbsp;
                    </td>
                </tr>
                <th colspan="4"><h4><?php echo __('Scope Of QMS'); ?></h4></th></tr>
                <tr>
                    <td colspan="4">
                        <?php echo html_entity_decode($company['Company']['scope_of_qms']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><th colspan="4"><h4><?php echo __('Company Audit Plan'); ?></h4></th></tr>
                <tr>
                    <td colspan="4">
                        <?php echo htmlspecialchars_decode($company['Company']['audit_plan']); ?>
                        &nbsp;
                    </td>
                </tr>

            </table>
        </div>
        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
    <?php if ($this->Session->read('User.is_mr') == true) { ?>
        <div class="nav">
            <div  class="col-md-12 no-padding">
                <div id="files-tabs" class="no-margin">
                    <ul>
                        <li><?php echo $this->Html->link(__('Quality System Manual'), array('controller' => 'users', 'action' => 'dashboard_files', 'quality_system_manual', NULL, NULL), array('escape' => false)); ?></li>
                        <li><?php echo $this->Html->link(__('Quality System Procedures'), array('controller' => 'users', 'action' => 'dashboard_files', 'quality_system_procedures', NULL, NULL), array('escape' => false)); ?> </li>
                        <li><?php echo $this->Html->link(__('Process Chart'), array('controller' => 'users', 'action' => 'dashboard_files', 'process_chart', NULL, NULL), array('escape' => false)); ?> </li>
                        <li><?php echo $this->Html->link(__('Guidelines'), array('controller' => 'users', 'action' => 'dashboard_files', 'guidelines', NULL, NULL), array('escape' => false)); ?> </li>
                        <li><?php echo $this->Html->link(__('Work Instructions'), array('controller' => 'users', 'action' => 'dashboard_files', 'work_instructions', NULL, NULL), array('escape' => false)); ?> </li>
                        <li><?php echo $this->Html->link(__('Formats'), array('controller' => 'users', 'action' => 'dashboard_files', 'formats', NULL, NULL), array('escape' => false)); ?> </li>
                        <li><?php echo $this->Html->image('indicator.gif', array('id' => 'file-busy-indicator', 'class' => 'pull-right')); ?></li>
                    </ul>
                </div>
            </div>

            <script>
                $(document).ready(function () {

                    $.ajaxSetup({
                        cache: false,
                        // success: function() {$("#message-busy-indicator").hide();}
                    });
                    $("#files-tabs").tabs({
                        load: function (event, ui) {
                            $("#file-busy-indicator").hide();
                        },
                        ajaxOptions: {
                            error: function (xhr, status, index, anchor) {
                                $(anchor.hash).html(
                                        "Couldn't load this tab. We'll try to fix this as soon as possible. " +
                                        "If this wouldn't be a demo.");
                            }
                        }
                    });

                    $("#files-tabs li").click(function () {
                        $("#file-busy-indicator").show();
                    });
                });
            </script>
        </div>
    <?php } ?>
</div>
<?php echo $this->Js->get('#list'); ?>
<?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#companies_ajax'))); ?>

<?php echo $this->Js->get('#edit'); ?>
<?php echo $this->Js->event('click', $this->Js->request(array('action' => 'edit', $company['Company']['id'], 'ajax'), array('async' => true, 'update' => '#companies_ajax'))); ?>
<?php echo $this->Js->writeBuffer(); ?>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
