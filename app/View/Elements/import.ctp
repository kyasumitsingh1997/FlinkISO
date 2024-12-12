<style>
    .modal-dialog {width:70%;}
    .modal-dialog {width:70%;}
    .chosen-container, .chosen-container-single, .chosen-select{min-width: 200px; width:100%;}
</style>
<script>
    $(function() {
        $("#import-tabs").tabs({
            beforeSend: function() {
                $("#busy-indicator-import-tabs").show()
            },
            complete: function() {
                $("#busy-indicator-import-tabs").hide();
            },
            beforeLoad: function(event, ui) {
                ui.jqXHR.error(function() {
                    ui.panel.html(
                            "Error Loading ... " +
                            "Please contact administrator.");
                });
            }
        });
    });
    function show_hide() {
        $('#recs_selected3').val($('#recs_selected').val());
		$('#recs_selected7').val($('#recs_selected').val());
        $('#recs_selected2').val($('#recs_selected').val());
		
        if ($('#recs_selected').val()) {
            $('#xls_show').show();
            $('#xls_notice_show').hide();
        }
        else {
            $('#xls_show').hide();
            $('#xls_notice_show').show();
        }
        
        if ($('#recs_selected2').val()) {
            $('#xls_show1').show();
            $('#xls_notice_show1').hide();
        }
        else {
            $('#xls_show1').hide();
            $('#xls_notice_show1').show();
        }
        
        if ($('#recs_selected2').val()) {
            $('#xls_show2').show();
            $('#xls_notice_show2').hide();
        }else{
            $('#xls_show2').hide();
            $('#xls_notice_show2').show();
        }
		
        if ($('#recs_selected7').val()) {
            $('#pdf_show').show();
            $('#pdf_notice_show').hide();
        }else{
            $('#pdf_show').hide();
            $('#pdf_notice_show').show();
        }

        if ($('#recs_selected3').val()) {
            $('#xls_show2').show();
            $('#xls_notice_show2').hide();
        }else{
            $('#xls_show2').hide();
            $('#xls_notice_show2').show();
        }
    };
</script>

<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo __('Import from file (excel format only)'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="uploads" id="import-tabs">
                    <ul>
					<?php if (($this->request->params['controller'] == 'employees') || 
                            ($this->request->params['controller'] == 'branches') || 
                            ($this->request->params['controller'] == 'customers') || 
                            ($this->request->params['controller'] == 'products') || 
                            ($this->request->params['controller'] == 'devices') || 
                            ($this->request->params['controller'] == 'trainers') || 
                            ($this->request->params['controller'] == 'fireExtinguishers') || 
                            ($this->request->params['controller'] == 'ListOfComputers') || 
                            ($this->request->params['controller'] == 'ListOfSoftwares') || 
                            ($this->request->params['controller'] == 'supplier_registrations') || 
                            ($this->request->params['controller'] == 'housekeeping_checklists') || 
                            ($this->request->params['controller'] == 'housekeeping_responsibilities') || 
                            ($this->request->params['controller'] == 'customer_feedback_questions') || 
                            ($this->request->params['controller'] == 'list_of_computers') || 
                            ($this->request->params['controller'] == 'courses') || 
                            ($this->request->params['controller'] == 'list_of_acceptable_suppliers') || 
                            ($this->request->params['controller'] == 'summery_of_supplier_evaluations') || 
                             ($this->request->params['controller'] == 'materials') || 
                            ($this->request->params['controller'] == 'internal_audit_questions') || 
                            ($this->request->params['controller'] == 'fire_safety_equipment_lists') || 
                            ($this->request->params['controller'] == 'corrective_preventive_actions') || 
                            ($this->request->params['controller'] == 'designations') || 
                            ($this->request->params['controller'] == 'competency_mappings') || 
                            ($this->request->params['controller'] == 'trainings')  || 
                            ($this->request->params['controller'] == 'list_of_trained_internal_auditors') ||
                            ($this->request->params['controller'] == 'customer_complaints') ||
                            ($this->request->params['controller'] ==  'customer_feedbacks') || 
                            ($this->request->params['controller'] ==  'customer_meetings') || 
                            ($this->request->params['controller'] ==  'device_maintenances')|| 
                            ($this->request->params['controller'] ==  'fire_extinguishers') || 
                            ($this->request->params['controller'] ==  'fire_safety_equipment_lists') || 
                            ($this->request->params['controller'] ==  'list_of_softwares') || 
                            ($this->request->params['controller'] ==  'proposals') || 
                            ($this->request->params['controller'] ==  'document_amendment_record_sheets') || 
                            ($this->request->params['controller'] ==  'change_addition_deletion_requests') || 
                            ($this->request->params['controller'] ==  'hazard_types') || 
                            ($this->request->params['controller'] ==  'injury_types') || 
                            ($this->request->params['controller'] ==  'body_areas') || 
                            ($this->request->params['controller'] ==  'hazard_sources') ||
                            ($this->request->params['controller'] ==  'severiry_types') ||
                            ($this->request->params['controller'] ==  'appraisal_questions') ||
                            ($this->request->params['controller'] ==  'non_conforming_products_materials') || 
                            ($this->request->params['controller'] ==  'clauses') ||
                            ($this->request->params['controller'] ==  'audit_type_masters') || 
                            ($this->request->params['controller'] ==  'objectives') || 
                            ($this->request->params['controller'] ==  'processes') ||
                            ($this->request->params['controller'] ==  'tasks' &&  
                                $this->action == 'advanced_search') 
                        ) { 
                                $show_import = 1;
                            }else{
                                $show_import = 0;
                            }
                           if($show_import == 1) {
                            ?>
                        <li><?php echo $this->Html->link('Download', array('controller' => 'file_uploads', 'action' => 'export')); ?></li>
                        <li><a href="#import-tabs1">Upload</a></li>
                        <li><?php echo $this->Html->link('Choose', array('controller' => 'file_uploads', 'action' => 'choose', $this->Session->read('User.id'), $controllerName)); ?></li>
					<?php } ?>	
                            <li><a href="#import-tabs4">Export Excel</a></li>
                            <li><a href="#import-tab7">Export PDF</a></li>
                            <li><a href="#import-tabs5">Save Excel On Server</a></li>
                            <li><a href="#import-tabs6">Email Report</a></li>
							
                        <li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator-import-tabs', 'class' => 'pull-right', 'style' => 'display:none')); ?>
                        </li>
                    </ul>
					<?php if($show_import == 1) { ?>
                    <div id="import-tabs1">
                        <?php
                            echo $this->Form->create('ImportFiles', array('controller' => 'uploads', 'action' => 'add'), array('type' => 'file'));
                            echo $this->Upload->edit('import', $this->Session->read('User.id') . '/' . $controllerName,true);
                            echo $this->Form->end();
                        ?>
                    </div>
					<?php } ?>						
<script>
    $(function() {
        $('#selectAllFields').on('click', function() {
            $(this).closest('#file_uploadsExportXlsDataForm').find(':checkbox').prop('checked', this.checked);
        });
    });
</script>
                    <div id="import-tabs4">
                        <?php
                        foreach ($tableFields as $key => $value): 
                            if ($key != 'sr_no' && $key != 'soft_delete' && $key != 'publish' && $key != 'company_id' && $key != 'created_by' && $key != 'modified_by' && $key != 'created' && $key != 'modified' && $key != 'branchid' && $key != 'departmentid' && $key != 'id' && $key != 'system_table_id' && $key != 'master_list_of_format_id' && $key != 'active_lock' && $key != 'record_status' && $key != 'status_user_id') {
                                $options[$key] = Inflector::humanize(str_replace('_id', '', $key));
                            } endforeach;
                        ?>
                        <div style="padding: 10px">
                            <div id="xls_show">
                                <?php
                                    echo $this->Form->create('file_uploads', array('action' => 'export_xls_data', 'target' => '_blank', 'class' => 'no-padding no-margin no-background zero-height'));
                                ?>
                                <div class="row">
                                    <div class="col-md-6" style="text-align: left">
                                        <input type="checkbox" id="selectAllFields"> Select All
                                        <?php
                                            echo $this->Form->hidden('rec_selected', array('id' => 'recs_selected'));
                                        ?> <?php
                                            echo $this->Form->hidden('save_type', array('value' => 0));
                                        ?> <?php
                                            echo $this->Form->input('fields', array('label' => false, 'div' => false, 'fields' => false, 'legend' => false, 'multiple' => 'checkbox', 'options' => array($options)));
                                        ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php
                                            echo $this->Form->hidden('model_name', array('value' => Inflector::camelize(Inflector::singularize($this->params['controller']))));
                                        ?>
                                        <h2>Export selected data in xls format</h2>
                                        <p>
                                            Click on the link below to download data. <br/>
                                        </p>
                                        <?php
                                            echo $this->Form->submit('Export selcted records in xls format', array('div' => false, 'class' => 'btn btn-success', 'style' => 'float:none'));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                                echo $this->Form->end();
                            ?>
                            <div id="xls_notice_show">
                                <h4>Please select records first !</h4>
                                <p class="help-text">In order to exort data or save report on server, make sure you select the records first form search or advance search function. Once you have your results ready, select the required result set and then click on "Import/Export". </p>
                            </div>
                        </div>
                    </div>
<script>
    $(function() {
        $('#selectAllFields7').on('click', function() {
            $(this).closest('#file_uploadsExportPdfDataForm').find(':checkbox').prop('checked', this.checked);
        });
    });
</script>
                    <div id="import-tab7">
                        <?php
                        foreach ($tableFields as $key => $value): if ($key != 'sr_no' && $key != 'soft_delete' && $key != 'publish' && $key != 'company_id' && $key != 'created_by' && $key != 'modified_by' && $key != 'created' && $key != 'modified' && $key != 'branchid' && $key != 'departmentid' && $key != 'id' && $key != 'system_table_id' && $key != 'master_list_of_format_id' && $key != 'active_lock' && $key != 'record_status' && $key != 'status_user_id') {
                                $options[$key] = Inflector::humanize(str_replace('_id', '', $key));
                            } endforeach;
                        ?>
                        <div style="padding: 10px">
                            <div id="pdf_show">
                                <?php
                                    echo $this->Form->create('file_uploads', array('action' => 'export_pdf_data/export_pdf_data.pdf' , 'id'=>'file_uploadsExportPdfDataForm','class' => 'no-padding no-margin no-background zero-height'));
                                ?>
                                <div class="row">
                                    <div class="col-md-6" style="text-align: left">
                                        <input type="checkbox" id="selectAllFields7"> Select All
                                        <?php
                                            echo $this->Form->hidden('rec_selected', array('id' => 'recs_selected7'));
                                        ?> <?php
                                            echo $this->Form->hidden('save_type', array('value' => 0));
                                        ?> <?php
                                            echo $this->Form->input('fields', array('label' => false, 'div' => false, 'fields' => false, 'legend' => false, 'multiple' => 'checkbox', 'options' => array($options)));
                                        ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php
                                            echo $this->Form->hidden('model_name', array('value' => Inflector::camelize(Inflector::singularize($this->params['controller']))));
                                        ?>
                                        <h2>Export selected data in PDF format</h2>
                                        <p>
                                            Click on the link below to download data. <br/>
                                        </p>
                                        <?php
                                            echo $this->Form->submit('Export selcted records in PDF format', array('div' => false, 'class' => 'btn btn-success', 'style' => 'float:none'));
                                        ?>
											<p><strong>Do not chose more than 5 fields while exporting in PDF.</strong>
                                    </div>
                                </div>
                            </div>
                            <?php
                                echo $this->Form->end();
                            ?>
                            <div id="pdf_notice_show">
                                <h4>Please select records first !</h4>
                                <p class="help-text">In order to exort data or save report on server, make sure you select the records first form search or advance search function. Once you have your results ready, select the required result set and then click on "Import/Export". </p>
                            </div>
                        </div>
                    </div>					
<script>
    $(function() {
        $('#selectAllFields2').on('click', function() {
            $(this).closest('#file_uploadsExportXlsDataForm').find(':checkbox').prop('checked', this.checked);
        });
    });
</script>
                    <div id="import-tabs5">
                        <?php
                            foreach ($tableFields as $key => $value):
                                if ($key != 'sr_no' && $key != 'soft_delete' && $key != 'publish' && $key != 'company_id' && $key != 'created_by' && $key != 'modified_by' && $key != 'created' && $key != 'modified' && $key != 'branchid' && $key != 'departmentid' && $key != 'id' && $key != 'system_table_id' && $key != 'master_list_of_format_id' && $key != 'record_status' && $key != 'status_user_id') {
                                    $options[$key] = Inflector::humanize(str_replace('_id', '', $key));
                                } endforeach;
                        ?>
                        <div style="padding: 10px">
                            <div id="xls_show1">
                                <?php echo $this->Form->create('file_uploads', array('action' => 'export_xls_data', 'class' => 'no-padding no-margin no-background zero-height')); ?>
                                <div class="row">
                                    <div class="col-md-6" style="text-align: left">
                                        <input type="checkbox" id="selectAllFields2"> Select All
                                        <?php echo $this->Form->hidden('rec_selected', array('id' => 'recs_selected2')); ?>
                                        <?php echo $this->Form->hidden('save_type', array('value' => 1)); ?>
                                        <?php echo $this->Form->input('fields', array('label' => false, 'div' => false, 'fields' => false, 'legend' => false, 'multiple' => 'checkbox', 'options' => array($options))); ?>

                                    </div>
                                    <div class="col-md-6">
                                        <?php echo $this->Form->hidden('model_name', array('value' => Inflector::camelize(Inflector::singularize($this->params['controller'])))); ?>
                                        <?php
                                            echo $this->Form->input('title');
                                            echo $this->Form->input('description', array('type' => 'textarea'));
                                            echo $this->Form->hidden('branch_id', array('value' => $this->Session->read('User.branch_id')));
                                            echo $this->Form->hidden('department_id', array('value' => $this->Session->read('User.department_id')));
                                        ?>
                                        <h2>Save selected data on server in xls format</h2>
                                        <p>
                                            Click on the link below to download data. <br/>
                                        </p>
                                        <?php echo $this->Form->submit('Save selected records on the server', array('div' => false, 'class' => 'btn btn-success', 'style' => 'float:none')); ?>
                                    </div>
                                </div>
                            </div>
                            <?php echo $this->Form->end(); ?>
                            <div id="xls_notice_show1">
                                <h4>Please select records first !</h4>
                                <p class="help-text">In order to exort data or save report on server, make sure you select the records first form search or advance search function. Once you have your results ready, select the required result set and then click on "Import/Export". </p>
                            </div>
                        </div>
                    </div>
<script>
    $(function() {
        $('#selectAllFields3').on('click', function() {
            $(this).closest('#file_uploadsExportXlsDataForm').find(':checkbox').prop('checked', this.checked);
        });
    });
</script>
                     <div id="import-tabs6">
                        <?php
                            foreach ($tableFields as $key => $value):
                                if ($key != 'sr_no' && $key != 'soft_delete' && $key != 'publish' && $key != 'company_id' && $key != 'created_by' && $key != 'modified_by' && $key != 'created' && $key != 'modified' && $key != 'branchid' && $key != 'departmentid' && $key != 'id' && $key != 'system_table_id' && $key != 'master_list_of_format_id' && $key != 'record_status' && $key != 'status_user_id') {
                                    $options[$key] = Inflector::humanize(str_replace('_id', '', $key));
                                } endforeach;
                        ?>
                        <div style="padding: 10px">
                            <div id="xls_show2">
                                <?php echo $this->Form->create('file_uploads', array('action' => 'export_xls_data', 'class' => 'no-padding no-margin no-background zero-height')); ?>
                                <div class="row">
                                    <div class="col-md-6" style="text-align: left">
                                        <input type="checkbox" id="selectAllFields3"> Select All
                                        <?php echo $this->Form->hidden('rec_selected', array('id' => 'recs_selected3')); ?>
                                        <?php echo $this->Form->hidden('save_type', array('value' => 2)); ?>
                                        <?php echo $this->Form->input('fields', array('label' => false, 'div' => false, 'fields' => false, 'legend' => false, 'multiple' => 'checkbox', 'options' => array($options))); ?>

                                    </div>
                                    <div class="col-md-6">
                                        <?php echo $this->Form->hidden('model_name', array('value' => Inflector::camelize(Inflector::singularize($this->params['controller'])))); ?>
                                        <?php
                                            echo $this->Form->input('to');
                                            echo $this->Form->input('description', array('type' => 'textarea'));
                                            echo $this->Form->hidden('branch_id', array('value' => $this->Session->read('User.branch_id')));
                                            echo $this->Form->hidden('department_id', array('value' => $this->Session->read('User.department_id')));
                                        ?>
                                        <h2>Email selected data as attachment</h2>
                                        <p>
                                            Click on the link below to download data. <br/>
                                        </p>
                                        <?php echo $this->Form->submit('Email selcted records as an attachment', array('div' => false, 'class' => 'btn btn-success', 'style' => 'float:none')); ?>
                                    </div>
                                </div>
                            </div>
                            <?php echo $this->Form->end(); ?>
                            <div id="xls_notice_show2">
                                <h4>Please select records first !</h4>
                                <p class="help-text">In order to exort data or save report on server, make sure you select the records first form search or advance search function. Once you have your results ready, select the required result set and then click on "Import/Export". </p>
                            </div>
                        </div>
                    </div>
                    <div style="padding: 10px"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Js->writeBuffer();
