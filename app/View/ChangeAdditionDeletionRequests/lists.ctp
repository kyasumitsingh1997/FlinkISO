<div  id="main">
    <?php echo $this->Session->flash(); ?>
    <div class="changeAdditionDeletionRequests ">
        <?php echo $this->element('nav-header-lists', array('postData' => array('pluralHumanName' => 'Change Requests', 'modelClass' => 'ChangeAdditionDeletionRequest', 'options' => array("sr_no" => "Sr No", "request_from" => "Request From", "request_details" => "Request Details", "others" => "Others", "master_list_of_format" => "Master List Of Format", "current_document_details" => "Current Document Details", "reason_for_change" => "Reason For Change"), 'pluralVar' => 'changeAdditionDeletionRequests'))); ?>
        <?php $masterListID = (!empty($this->request->params['pass'][0]))? $this->request->params['pass'][0] : '';?>
        <div class="nav">
            <div id="tabs">
                <ul>
                    <li><?php 
                    if(isset($this->request->params['pass'][1])){
                        echo $this->Html->link(__('Document Change Request'), array('action' => 'add_document_cr', $this->request->params['pass'][0]));
                    }else{
                        echo $this->Html->link(__('Document Change Request'), array('action' => 'add_ajax', $masterListID)); 
                    } ?>
                    </li>
                    <li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator', 'class' => 'pull-right')); ?></li>
                </ul>
            </div>
        </div>
        <div id="changeAdditionDeletionRequests_tab_ajax"></div>
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
    <?php echo $this->element('advanced-search', array('postData' => array("sr_no" => "Sr No", "request_from" => "Request From", "request_details" => "Request Details", "others" => "Others", "master_list_of_format" => "Master List Of Format", "current_document_details" => "Current Document Details", "reason_for_change" => "Reason For Change"), 'PublishedBranchList' => array($PublishedBranchList))); ?>
    <?php echo $this->element('import', array('postData' => array("sr_no" => "Sr No", "request_from" => "Request From", "request_details" => "Request Details", "others" => "Others", "master_list_of_format" => "Master List Of Format", "current_document_details" => "Current Document Details", "reason_for_change" => "Reason For Change"))); ?>
</div>
<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }});
</script>
