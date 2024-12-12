<?php echo $this->element('checkbox-script'); ?>
<div  id="main">
    <?php echo $this->Session->flash(); ?>
    <div class="customers">
        <script type="text/javascript">
            $(document).ready(function() {
                $(function() {
                    $("#tabs").tabs({
                        beforeLoad: function(event, ui) {
                            $(ui.panel).siblings('.ui-tabs-panel').empty();
                            var curTab = $('#tabs.ui-tabs-selected');
                            ui.jqXHR.error(function() {
                                ui.panel.html(
                                        "Error Loading ... " +
                                        "Please contact administrator.");
                            });
                        }
                    });
                });
                $(function() {
                    $('#tabs').click('tabsselect', function(event, ui) {
                        var selectedTab = $("#tabs").tabs('option', 'active');
                        $('#SearchCapaType').val(selectedTab);
                    });
                });
            });
        </script>
        <div class="nav">
            <div id="tabs">
                <ul>
                    <li>
                        <?php echo $this->Html->link(__('Prospects (Leads) <span class="btn btn-danger badge">'.$leads.'</span>'), array(
                            'action' => 'customer_index', 0,
                            'soft_delete'=>$this->request->params['named']['soft_delete'],
                            'publish'=>$this->request->params['named']['published']
                            ),array('escape'=>false)); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link(__('Customers <span class="btn btn-success badge">'.$cust.'</span>'), array(
                            'action' => 'customer_index', 1,
                            'soft_delete'=>$this->request->params['named']['soft_delete'],
                            'publish'=>$this->request->params['named']['published']
                            ),array('escape'=>false)); ?>
                    </li>
                    <li>
                        <?php echo $this->Html->link(__('Follow up Rules'), array('controller'=>'proposal_followup_rules','action' => 'index'),array('escape'=>false)); ?></li>
                      <!--<li><?php echo $this->Html->link(__('Add New Customer'), array('action' => 'add_ajax')); ?></li>  -->
                  <li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator', 'class' => 'pull-right')); ?></li>
                </ul>
            </div>
        </div>

        <div id="CustomerActions_tab_ajax"></div>
    </div>
    <?php echo $this->element('export'); ?>
   
<?php echo $this->element('common'); ?>
<?php echo $this->element('advanced-search', array('postData' => array("name" => "Name", "customer_code" => "Customer Code", "customer_since_date" => "Customer Since Date", "date_of_birth" => "Date Of Birth", "phone" => "Phone", "mobile" => "Mobile", "email" => "Email", "age" => "Age", "residence_address" => "Residence Address", "maritial_status" => "Marital Status"), 'PublishedBranchList' => array($PublishedBranchList))); ?>
<?php echo $this->element('import', array('postData' => array("sr_no" => "Sr No", "name" => "Name", "customer_code" => "Customer Code", "customer_since_date" => "Customer Since Date", "date_of_birth" => "Date Of Birth", "phone" => "Phone", "mobile" => "Mobile", "email" => "Email", "age" => "Age", "residence_address" => "Residence Address", "maritial_status" => "Marital Status"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->Js->writeBuffer(); ?>
<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }});
</script>
