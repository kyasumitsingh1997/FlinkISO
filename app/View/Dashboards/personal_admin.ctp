<div id="main">
    <div class="">
        <h4><?php echo __('Admin Dashboard'); ?></h4>
    </div>
    <div class="main nav panel">
        <?php echo $this->Session->flash(); ?>
        <div class="nav panel-body">
            <div class="row  panel-default">
                <div class="col-md-8">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="thumbnail">
                                <div class="caption">
                                    <h4><?php echo __('Fire Extinguisher'); ?></h4>
                                    <p>
                                        <?php
                                            echo __('Make sure you have already added ');
                                            echo $this->Html->link(__('Fire Extinguisher Types'), array('controller' => 'fire_extinguisher_types', 'action' => 'index'), array('class' => 'text-primary'));
                                        ?><br /><br /><br /><br /></p>
                                    <div class="btn-group">
                                        <?php echo $this->Html->link(__('Add'), array('controller' => 'fire_extinguishers', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                        <?php echo $this->Html->link(__('See All'), array('controller' => 'fire_extinguishers', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                        <?php echo $this->Html->link(' ' .  $this->requestAction('App/get_model_list/FireExtinguisher/count'), array('controller' => 'fire_extinguishers', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('No. of Fire Extinguishers'))); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="thumbnail">
                                <div class="caption">
                                    <h4><?php echo __('Check List For Housekeeping'); ?></h4>
                                    <p>
                                        <?php
                                            echo __('To create a checklist for housekeeping make sure you have already added ');
                                            echo $this->Html->link(__('Branches'), array('controller' => 'branches', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                            echo $this->Html->link(__('Departments'), array('controller' => 'departments', 'action' => 'index'), array('class' => 'text-primary'));
                                        ?>
                                    </p>

                                    <div class="btn-group">
                                        <?php echo $this->Html->link(__('Add'), array('controller' => 'housekeeping_checklists', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                        <?php echo $this->Html->link(__('See All'), array('controller' => 'housekeeping_checklists', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                        <?php echo $this->Html->link(' ' . $this->requestAction('App/get_model_list/HousekeepingChecklist/count'), array('controller' => 'housekeeping_checklists', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('No. of Housekeeping Checklists'))); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="thumbnail">
                                <div class="caption">
                                    <h4><?php echo __('Housekeeping Responsibilities'); ?></h4>
                                    <p>
                                        <?php
                                            echo __('Make sure you have already added ');
                                            echo $this->Html->link(__('Check List For Housekeeping'), array('controller' => 'housekeeping_checklists', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                            echo $this->Html->link(__('Employees'), array('controller' => 'employees', 'action' => 'index'), array('class' => 'text-primary'));
                                        ?><br><br></p>

                                    <div class="btn-group">
                                        <?php echo $this->Html->link(__('Add'), array('controller' => 'housekeeping_responsibilities', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                        <?php echo $this->Html->link(__('See All'), array('controller' => 'housekeeping_responsibilities', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                        <?php echo $this->Html->link(' ' . $this->requestAction('App/get_model_list/HousekeepingResponsibility/count'), array('controller' => 'housekeeping_responsibilities', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('No. of Housekeeping Responsibilities'))); ?><script>$('.btn').tooltip();</script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>

                </div>
                <script>
                    $(function() {
                        $("#tabs").tabs({
                            beforeLoad: function(event, ui) {
                                ui.jqXHR.error(function() {
                                    ui.panel.html(
                                            "Error Loading ... " +
                                            "Please contact administrator.");
                                });
                            }
                        });
                    });
                </script>

                <div class="col-md-4">
                    <?php echo $this->element('helps'); ?>
                </div>
            </div>

            <div class="clearfix"></div>

            <div id="main-housekeeping" class="col-md-12">
                <?php // echo $this->element('housekeeping'); ?>
                <span class="help-text">
                    <?php echo '<strong>' . __('Note: ') . '</strong>' . __('As an MR, you will be able to see tasks & updates for all users.'); ?>
                </span>
            </div>

        </div>
    </div>
</div>
