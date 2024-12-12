<div  id="main">
    <?php echo $this->Session->flash(); ?>
    <div class="correctivePreventiveActions ">
        

        <?php echo $this->element('capa_navigation', array('postData' => array('pluralHumanName' => 'Corrective Preventive Actions', 'modelClass' => 'CorrectivePreventiveAction', 'options' => array("sr_no" => "Sr No", "number" => "Number", "raised_by" => "Raised By", "assigned_to" => "Assigned To", "target_date" => "Target Date", "initial_remarks" => "Initial Remarks", "proposed_immidiate_action" => "Proposed Immediate Action", "completed_by" => "Completed By", "completed_on_date" => "Completed On Date", "completion_remarks" => "Completion Remarks", "root_cause_analysis_required" => "Root Cause Analysis Required", "root_cause" => "Root Cause", "determined_by" => "Determined By", "determined_on_date" => "Determined On Date", "root_cause_remarks" => "Root Cause Remarks", "proposed_longterm_action" => "Proposed Longterm Action", "action_assigned_to" => "Action Assigned To", "action_completed_on_date" => "Action Completed On Date", "action_completion_remarks" => "Action Completion Remarks", "effectiveness" => "Effectiveness", "closed_by" => "Closed By", "closed_on_date" => "Closed On Date", "closure_remarks" => "Closure Remarks", "document_changes_required" => "Document Changes Required"), 'pluralVar' => 'correctivePreventiveActions','capa_type'=>$type,'action'=>'add'))); ?>
        <div class="nav">
            <div id="tabs">
                <ul>
                    <li><?php echo $this->Html->link(__('New Corrective Preventive Action'), array('action' => 'add_ajax')); ?></li>
                    <li><?php echo $this->Html->link(__('New CAPA Source'), array('controller' => 'capa_sources', 'action' => 'add_ajax')); ?></li>
                    <li><?php echo $this->Html->link(__('New CAPA Category'), array('controller' => 'capa_categories', 'action' => 'add_ajax')); ?></li>
                    <li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator', 'class' => 'pull-right')); ?></li>
                </ul>
            </div>
        </div>
        <div id="correctivePreventiveActions_tab_ajax"></div>
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

    <?php echo $this->element('export'); ?>
    <?php echo $this->element('capa_advanced_search_modal'); ?>
    <?php echo $this->element('import', array('postData' => array("sr_no" => "Sr No", "number" => "Number", "raised_by" => "Raised By", "assigned_to" => "Assigned To", "target_date" => "Target Date", "initial_remarks" => "Initial Remarks", "proposed_immidiate_action" => "Proposed Immediate Action", "completed_by" => "Completed By", "completed_on_date" => "Completed On Date", "completion_remarks" => "Completion Remarks", "root_cause_analysis_required" => "Root Cause Analysis Required", "root_cause" => "Root Cause", "determined_by" => "Determined By", "determined_on_date" => "Determined On Date", "root_cause_remarks" => "Root Cause Remarks", "proposed_longterm_action" => "Proposed Longterm Action", "action_assigned_to" => "Action Assigned To", "action_completed_on_date" => "Action Completed On Date", "action_completion_remarks" => "Action Completion Remarks", "effectiveness" => "Effectiveness", "closed_by" => "Closed By", "closed_on_date" => "Closed On Date", "closure_remarks" => "Closure Remarks", "document_changes_required" => "Document Changes Required"))); ?>
</div>
<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }});
</script>