<style>
    .badge-sm{ padding:1px 4px !important; font-size:85% !important}
</style>
<div id="main">
    <div class="">
        <h4><?php echo __('Business Development Dashboard'); ?> 
            <div class="btn-group">
                <?php
                echo $this->Html->link('Add Customer', array('controller' => 'customers', 'action' => 'lists'), array('class' => 'btn btn-xs btn-default'));
                echo $this->Html->link('Add Proposal', array('controller' => 'proposals', 'action' => 'lists'), array('class' => 'btn btn-xs btn-default'));
                echo $this->Html->link('Add Followup', array('controller' => 'proposal_followups', 'action' => 'lists'), array('class' => 'btn btn-xs btn-default'));
                echo $this->Html->link('Add Followup Rule', array('controller' => 'proposal_followup_rules', 'action' => 'index'), array('class' => 'btn btn-xs btn-default'));
                echo $this->Html->link('Today\'s Follow Ups <span class="badge badge-sm label-danger">' . $todays_followups . '</span>', array('controller' => 'proposals', 'action' => 'today_followups'), array('class' => 'btn btn-xs btn-default', 'escape' => false));
                echo $this->Html->link('Proposals Not Sent <span class="badge badge-sm label-danger">' . $not_sent . '</span>', array('controller' => 'proposals', 'action' => 'proposals_not_sent'), array('class' => 'btn btn-xs btn-default', 'escape' => false));
                echo $this->Html->link('Proposals Lost <span class="badge badge-sm label-danger">' . $not_sent . '</span>', array('controller' => 'proposals', 'action' => 'proposals_lost'), array('class' => 'btn btn-xs btn-default', 'escape' => false));
                ?>
            </div>
        </h4>
    </div>
    <div class="main nav panel">
        <div class="nav panel-body">
            <div class="row  panel-default">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-group">
                                <?php echo $this->Html->link('This Month', array('controller' => 'dashboards', 'action' => 'bd', date('Y-m-1'), date('Y-m-d'), 'duration' => 'Tm'), array('class' => 'btn btn-default', 'id' => 'Tm')); ?>
                                <?php echo $this->Html->link('Last Month', array('controller' => 'dashboards', 'action' => 'bd', date('Y-m-1', strtotime('-1 month')), date('Y-m-t', strtotime('-1 month')), 'duration' => 'Lm'), array('class' => 'btn btn-default', 'id' => 'Lm')); ?>
                                <?php echo $this->Html->link('Q1', array('controller' => 'dashboards', 'action' => 'bd', date('Y-1-1'), date('Y-3-t'), 'duration' => 'Q1'), array('class' => 'btn btn-default', 'id' => 'Q1')); ?>
                                <?php echo $this->Html->link('Q2', array('controller' => 'dashboards', 'action' => 'bd', date('Y-4-1'), date('Y-6-t'), 'duration' => 'Q2'), array('class' => 'btn btn-default', 'id' => 'Q2')); ?>
                                <?php echo $this->Html->link('Q3', array('controller' => 'dashboards', 'action' => 'bd', date('Y-7-1'), date('Y-9-t'), 'duration' => 'Q3'), array('class' => 'btn btn-default', 'id' => 'Q3')); ?>
                                <?php echo $this->Html->link('Q4', array('controller' => 'dashboards', 'action' => 'bd', date('Y-10-1'), date('Y-12-t'), 'duration' => 'Q4'), array('class' => 'btn btn-default', 'id' => 'Q4')); ?>
                                <?php echo $this->Html->link('This Year', array('controller' => 'dashboards', 'action' => 'bd', date('Y-1-1'), date('Y-12-t'), 'duration' => 'Y'), array('class' => 'btn btn-default', 'id' => 'Y')); ?>
                                <?php echo $this->Html->link('All', array('controller' => 'dashboards', 'action' => 'bd', 'duration' => 'all'), array('class' => 'btn btn-default', 'id' => 'all')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row"><div class="col-md-12">&nbsp;</div></div>
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="btn btn-warning col-md-12 col-sm-12 col-xs-12">
                                <strong>Customer Pipeline</strong>
                                <h1><?php echo $pipeline_customers; ?></h1>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <?php if ($new_customers > 0) { ?>
                                <div class="btn btn-success col-md-12 col-sm-12 col-xs-12">
                                    <strong>New Customers</strong>
                                    <h1><?php echo $new_customers; ?> <small style="color:#fff"><span class="glyphicon glyphicon-thumbs-up"></span></small></h1>
                                </div>
                            <?php } else { ?>
                                <div class="btn btn-danger col-md-12 col-sm-12 col-xs-12">
                                    <strong>New Customers Won</strong>
                                    <h1><?php echo $new_customers; ?> <small style="color:#fff"><span class="glyphicon glyphicon-thumbs-down"></span></small></h1>
                                </div>
                            <?php } ?>		
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <?php if ($lost_customers > 0) { ?>
                                <div class="btn btn-danger col-md-12 col-sm-12 col-xs-12">
                                    <strong>Customers Lost</strong>
                                    <h1><?php echo $lost_customers; ?> <small style="color:#fff"><span class="glyphicon glyphicon-thumbs-down"></span></small></h1>
                                </div>
                            <?php } else { ?>
                                <div class="btn btn-success col-md-12 col-sm-12 col-xs-12">
                                    <strong>Customers Lost</strong>
                                    <h1><?php echo $lost_customers; ?> <small style="color:#fff"><span class="glyphicon glyphicon-thumbs-up"></span></small></h1>
                                </div>
                            <?php } ?>
                        </div>	
                        <div class="col-md-3 col-sm-12 col-xs-12">						
                            <div class="btn btn-default col-md-12 col-sm-12 col-xs-12">
                                <strong>Total</strong>
                                <h1><?php echo $new_customers + $lost_customers + $pipeline_customers; ?> <small style="color:#000"><span class="glyphicon glyphicon-thumbs-up"></span></small></h1>
                            </div>
                        </div>
                    </div>
                    <div class="row"><div class="col-md-12">&nbsp;</div></div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="btn btn-warning col-md-12 col-sm-12 col-xs-12">
                                <strong>Pending Proposals</strong>
                                <h1><?php echo $pipeline_proposals; ?></h1>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <?php if ($won_proposals > 0) { ?>
                                <div class="btn btn-success col-md-12 col-sm-12 col-xs-12">
                                    <strong>Proposals Won</strong>
                                    <h1><?php echo $won_proposals; ?> <small style="color:#fff"><span class="glyphicon glyphicon-thumbs-up"></span></small></h1>
                                </div>
                            <?php } else { ?>
                                <div class="btn btn-warning col-md-12 col-sm-12 col-xs-12">
                                    <strong>Proposals Won</strong>
                                    <h1><?php echo $won_proposals; ?> <small style="color:#fff"><span class="glyphicon glyphicon-thumbs-down"></span></small></h1>
                                </div>
                            <?php } ?>	
                        </div>
                        <div class="col-md-3">
                            <?php if ($won_proposals > 0) { ?>
                                <div class="btn btn-danger col-md-12 col-sm-12 col-xs-12">
                                    <strong>Proposals Lost</strong>
                                    <h1><?php echo $lost_proposals; ?> <small style="color:#fff"><span class="glyphicon glyphicon-thumbs-down"></span></small></h1>
                                </div>
                            <?php } else { ?>
                                <div class="btn btn-success col-md-12 col-sm-12 col-xs-12">
                                    <strong>Proposals Lost</strong>
                                    <h1><?php echo $lost_proposals; ?> <small style="color:#fff"><span class="glyphicon glyphicon-thumbs-up"></span></small></h1>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12">						
                            <div class="btn btn-default col-md-12 col-sm-12 col-xs-12">
                                <strong>Total</strong>
                                <h1><?php echo $won_proposals + $lost_proposals + $pipeline_proposals; ?> <small style="color:#000"><span class="glyphicon glyphicon-thumbs-up"></span></small></h1>
                            </div>
                        </div>						
                    </div>
                    <div class="row"><div class="col-md-12">&nbsp;</div></div>
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <?php if ($followup_details['Done'] > 0) { ?>
                                <div class="btn btn-success col-md-12 col-sm-12 col-xs-12">
                                    <strong>Follow Ups On Time</strong>
                                    <h1><?php echo $followup_details['Done']; ?></h1>
                                </div>
                            <?php } else { ?>
                                <div class="btn btn-warning col-md-12 col-sm-12 col-xs-12">
                                    <strong>Follow Ups On Time</strong>
                                    <h1><?php echo $followup_details['Done']; ?></h1>
                                </div>
                            <?php } ?>	
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <?php if ($followup_details['Pending'] > 0) { ?>
                                <div class="btn btn-warning col-md-12 col-sm-12 col-xs-12">
                                    <strong>Pending Follow Ups</strong>
                                    <h1><?php echo $followup_details['Pending']; ?> <small style="color:#fff"><span class="glyphiconup"></span></small></h1>
                                </div>
                            <?php } else { ?>
                                <div class="btn btn-success col-md-12 col-sm-12 col-xs-12">
                                    <strong>Pending Follow Ups</strong>
                                    <h1><?php echo $followup_details['Pending']; ?> <small style="color:#fff"><span class="glyphicon glyphicon-thumbs-up"></span></small></h1>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <?php if ($followup_details['NotDone'] > 0) { ?>
                                <div class="btn btn-danger col-md-12 col-sm-12 col-xs-12">
                                    <strong>Missed Follow Ups</strong>
                                    <h1><?php echo $followup_details['NotDone']; ?> <small style="color:#fff"><span class="glyphicon glyphicon-thumbs-down"></span></small></h1>
                                </div>
                            <?php } else { ?>
                                <div class="btn btn-success col-md-12 col-sm-12 col-xs-12">
                                    <strong>Missed Follow Ups</strong>
                                    <h1><?php echo $followup_details['NotDone']; ?> <small style="color:#fff"><span class="glyphicon glyphicon-thumbs-up"></span></small></h1>
                                </div>
                            <?php } ?>	
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12">						
                            <div class="btn btn-default col-md-12 col-sm-12 col-xs-12">
                                <strong>Total</strong>
                                <h1><?php echo $followup_details['Done'] + $followup_details['Pending'] + $followup_details['NotDone']; ?> <small style="color:#000"><span class="glyphicon glyphicon-thumbs-up"></span></small></h1>
                            </div>
                        </div>							
                    </div>	

                </div>
                <div class="col-md-4">
                    <?php echo $this->element('helps'); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel-default">
                        <div class="panel-body">
                            <div id="loadFollowUps"></div>

                        </div>
                    </div>
                    <div class="col-md-12">
                        <?php echo $this->Form->create('dashboard', array('controller' => 'dashboard', 'action' => 'bd', 'class' => 'no-margin no-padding'), array()); ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-md-8"><h3 class="list-group-item-heading"><?php echo __('Conversion Rate'); ?></h3></div>
                                    <div class="col-md-2"><?php echo $this->Form->input('start_date', array('placeholder' => 'Select Start Date', 'div' => false, 'label' => false, 'class' => ('disabled'), 'readonly')); ?></div>
                                    <div class="col-md-2"><?php echo $this->Form->input('end_date', array('placeholder' => 'Select End Date', 'div' => false, 'label' => false, 'class' => ('disabled'), 'readonly')); ?></div>
                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>
                            <div class="panel-body" id="mapping">
                                <?php echo $this->element('result_mapping'); ?>
                            </div>						
                        </div>	
                    </div>

                    <div class="col-md-12">
                        <?php echo $this->Form->create('dashboard', array('controller' => 'dashboard', 'action' => 'bd', 'class' => 'no-margin no-padding'), array()); ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-md-8"><h3 class="list-group-item-heading"><?php echo __('Customer / Proposals / Proposal Follow Up Graph'); ?></h3></div>
                                    <div class="col-md-2"><?php echo $this->Form->input('start_date', array('placeholder' => 'Select Start Date', 'div' => false, 'label' => false, 'class' => ('disabled'), 'readonly')); ?></div>
                                    <div class="col-md-2"><?php echo $this->Form->input('end_date', array('placeholder' => 'Select End Date', 'div' => false, 'label' => false, 'class' => ('disabled'), 'readonly')); ?></div>
                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>
                            <div class="panel-body" id="graph">

                            </div>						
                        </div>	
                    </div>
                    <div class="col-md-12">

                        <div class="row">

                            <div class="col-md-4">
                                <div class="thumbnail">
                                    <div class="caption">
                                        <h4><?php echo __('Customers'); ?></h4>
                                        <p>
                                            <?php echo __('Click Add to add new customer or click on See All to view list of existing customers') ?>
                                        </p>
                                        <div class="btn-group">
                                            <?php echo $this->Html->link(__('Add'), array('controller' => 'customers', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                            <?php echo $this->Html->link(__('See All'), array('controller' => 'customers', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                            <?php echo $this->Html->link(' ' . $countCustomers, array('controller' => 'customers', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('Customers'))); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="col-md-4">
                                <div class="thumbnail">
                                    <div class="caption">
                                        <h4><?php echo __('Proposals'); ?></h4>
                                        <p>
                                            <?php
                                            echo __('Before you add proposals make sure you have added ');
                                            echo $this->Html->link(__('Customers'), array('controller' => 'customers', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                            echo $this->Html->link(__('Followup Rules'), array('controller' => 'proposal_followup_rules', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                            echo $this->Html->link(__('Employees'), array('controller' => 'employees', 'action' => 'index'), array('class' => 'text-primary'));
                                            ?>
                                        </p>
                                        <div class="btn-group">
                                            <?php echo $this->Html->link(__('Add'), array('controller' => 'proposals', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                            <?php echo $this->Html->link(__('See All'), array('controller' => 'proposals', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                            <?php echo $this->Html->link(' ' . $countClientProposals, array('controller' => 'proposals', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('No. of Proposals'))); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="thumbnail">
                                    <div class="caption">
                                        <h4><?php echo __('Proposal Followups'); ?></h4>
                                        <p>
                                            <?php
                                            echo __('Before you add proposal followups make sure you have added ');
                                            echo $this->Html->link(__('Proposal'), array('controller' => 'proposals', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                            echo $this->Html->link(__('Employees'), array('controller' => 'employees', 'action' => 'index'), array('class' => 'text-primary'));
                                            ?>
                                        </p>
                                        <div class="btn-group">
                                            <?php echo $this->Html->link(__('Add'), array('controller' => 'proposal_followups', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                            <?php echo $this->Html->link(__('See All'), array('controller' => 'proposal_followups', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                            <?php echo $this->Html->link(' ' . $countProposalFollowups, array('controller' => 'proposal_followups', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('No. of Proposals Followups'))); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <br />
                        <div class="row">
                            <div class="col-md-4">
                                <div class="thumbnail">
                                    <div class="caption">
                                        <h4><?php echo __('Customer Meetings'); ?></h4>
                                        <p>
                                            <?php
                                            echo __('Before you add customer meetings make sure you have added ');
                                            echo $this->Html->link(__('Employees'), array('controller' => 'employees', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                            echo $this->Html->link(__('Customers'), array('controller' => 'customers', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                            ?>
                                        </p><br />
                                        <div class="btn-group">
                                            <?php echo $this->Html->link(__('Add'), array('controller' => 'customer_meetings', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                            <?php echo $this->Html->link(__('See All'), array('controller' => 'customer_meetings', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                            <?php echo $this->Html->link(' ' . $countCustomerMeetings, array('controller' => 'customer_meetings', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('No. of Meetings'))); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="col-md-4">
                                <div class="thumbnail">
                                    <div class="caption">
                                        <h4><?php echo __('Audits Schedules'); ?></h4>
                                        <p>
                                            <?php
                                            echo __('Before you add proposal followups make sure you have added ');
                                            echo $this->Html->link(__('Proposal'), array('controller' => 'proposals', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                            echo $this->Html->link(__('Employees'), array('controller' => 'employees', 'action' => 'index'), array('class' => 'text-primary'));
                                            ?><br /><br />
                                        </p>
                                        <div class="btn-group">
                                            <?php echo $this->Html->link(__('Add'), array('controller' => 'proposal_followups', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                            <?php echo $this->Html->link(__('See All'), array('controller' => 'proposal_followups', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                            <?php echo $this->Html->link(' ' . $countProposalFollowups, array('controller' => 'proposal_followups', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('No. of Proposals Followups'))); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="thumbnail">
                                    <div class="caption">
                                        <h4><?php echo __('Audits'); ?></h4>
                                        <p>
                                            <?php
                                            echo __('Before you add proposal followups make sure you have added ');
                                            echo $this->Html->link(__('Proposal'), array('controller' => 'proposals', 'action' => 'index'), array('class' => 'text-primary')) . ', ';
                                            echo $this->Html->link(__('Employees'), array('controller' => 'employees', 'action' => 'index'), array('class' => 'text-primary'));
                                            ?><br /><br />
                                        </p>
                                        <div class="btn-group">
                                            <?php echo $this->Html->link(__('Add'), array('controller' => 'proposal_followups', 'action' => 'lists'), array('class' => 'btn btn-default')); ?>
                                            <?php echo $this->Html->link(__('See All'), array('controller' => 'proposal_followups', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-default')); ?>
                                            <?php echo $this->Html->link(' ' . $countProposalFollowups, array('controller' => 'proposal_followups', 'action' => 'index'), array('type' => 'button', 'class' => 'btn btn-info', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip', 'title' => __('No. of Proposals Followups'))); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>						
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    $(function() {
        $("#dashboardStartDate").datepicker({
            dateFormat: 'yy-m-d',
            defaultDate: "-1m",
            changeMonth: true,
            numberOfMonths: 2,
            onClose: function(selectedDate) {
                $("#dashboardEndDate").datepicker("option", "minDate", selectedDate);
            }
        });
        $("#dashboardEndDate").datepicker({
            dateFormat: 'yy-m-d',
            defaultDate: "d",
            maxDate: "d",
            changeMonth: true,
            numberOfMonths: 2,
            onClose: function(selectedDate) {
                $("#dashboardStartDate").datepicker("option", "maxDate", selectedDate);
            }
        });
    });

    $(function() {
        $("#dashboardEndDate").change(function() {
            if ($("#dashboardEndDate").val() != '') {
                $("#mapping").load('result_mapping/' + $("#dashboardStartDate").val() + '/' + $("#dashboardEndDate").val());
            }
        });
        $("#dashboardStartDate").change(function() {
            if ($("#dashboardEndDate").val() != '') {
                $("#mapping").load('result_mapping/' + $("#dashboardStartDate").val() + '/' + $("#dashboardEndDate").val());
            }

        });
    });

    $().ready(function() {
<?php if (!isset($this->request->params['named']['duration'])) { ?>$('#Tm').removeClass('btn-default').addClass('btn-info');<?php } ?>
                        $('#<?php echo $this->request->params['named']['duration']; ?>').removeClass('btn-success').removeClass('btn-default').addClass('btn-info');
                        $('#loadFollowUps').load('<?php echo Router::url('/', true); ?>proposals/proposal_followup_status/<?php echo $this->request->params['pass'][0]; ?>/<?php echo $this->request->params['pass'][1]; ?>/duration:<?php echo $this->request->params['named']['duration']; ?>');
                                $('#graph').load('<?php echo Router::url('/', true); ?>proposals/proposal_graph/<?php echo $this->request->params['pass'][0]; ?>/<?php echo $this->request->params['pass'][1]; ?>/duration:<?php echo $this->request->params['named']['duration']; ?>');
                                    });
</script>
