<div id="main">
    <div class=""><h4><?php echo __('Risk Assessment & Incident Manegement'); ?></h4></div>
    <div class="main nav panel">
        <?php echo $this->Session->flash(); ?>
        <div class="nav panel-body">
            <div class="row  panel-default">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="thumbnail">
                                <div class="caption">
                                    <h4><?php echo __('Risk Assessments'); ?></h4>
                                    <p>
                                        <?php
                                            echo __('Make sure you have already added ');
                                            echo $this->Html->link(__('Hazard Types'), array('controller' => 'hazard_types', 'action' => 'index'), array('class' => 'text-primary')) .', ';
                                            echo $this->Html->link(__('Hazard Sources'), array('controller' => 'hazard_sources', 'action' => 'index'), array('class' => 'text-primary')) .', ';
                                            echo $this->Html->link(__('Accident Types'), array('controller' => 'accident_types', 'action' => 'index'), array('class' => 'text-primary')) .', ';
                                            echo $this->Html->link(__('Severiry Type'), array('controller' => 'severiry_types', 'action' => 'index'), array('class' => 'text-primary')) .', ';
                                            echo $this->Html->link(__('Risk Ratings'), array('controller' => 'risk_ratings', 'action' => 'index'), array('class' => 'text-primary')) .', ';
                                            echo $this->Html->link(__('Processes'), array('controller' => 'processes', 'action' => 'index'), array('class' => 'text-primary')) .', ';
                                        ?></p>
                                    <div class="btn-group">
                                        <?php echo $this->Html->link(__('Add'), array('controller' => 'risk_assessments', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                        <?php echo $this->Html->link(__('See All'), array('controller' => 'risk_assessments', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                        <?php echo $this->Html->link(' ' .  $this->requestAction('App/get_model_list/risk_assessments/count'), array('controller' => 'fire_extinguishers', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('#'))); ?> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="thumbnail">
                                <div class="caption">
                                    <h4><?php echo __('Incident Management'); ?></h4>
                                    <p>
                                        <?php
                                            echo __('Make sure you have already added ');
                                            echo $this->Html->link(__('Risk Assessments'), array('controller' => 'risk_assessments', 'action' => 'index'), array('class' => 'text-primary')) .', ';
                                            echo $this->Html->link(__('Processes'), array('controller' => 'processes', 'action' => 'index'), array('class' => 'text-primary')) .', ';
                                            echo $this->Html->link(__('Employees'), array('controller' => 'employees', 'action' => 'index'), array('class' => 'text-primary')) .', ';
                                            echo $this->Html->link(__('Incident Classifications'), array('controller' => 'incident_classifications', 'action' => 'index'), array('class' => 'text-primary')) .', ';                                            
                                        ?><br /><br /></p>

                                    <div class="btn-group">
                                        <?php echo $this->Html->link(__('Add'), array('controller' => 'incidents', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                        <?php echo $this->Html->link(__('See All'), array('controller' => 'incidents', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                        <?php echo $this->Html->link(' ' . $this->requestAction('App/get_model_list/incidents/count'), array('controller' => 'housekeeping_checklists', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('No. of Housekeeping Checklists'))); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <?php echo $this->element('helps'); ?>
                </div>
            </div>
        </div>
    </div>
</div>