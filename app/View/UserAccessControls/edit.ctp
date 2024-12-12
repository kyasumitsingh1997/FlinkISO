<?php
	// Configure::write('debug',1);
	// debug($forms);
	// exit;
?>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?><div id="userAccessControls_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="userAccessControls form col-md-8">
<h4><?php echo __('Edit User Access Control'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('UserAccessControl',array('role'=>'form','class'=>'form')); ?>
<div class="row">			
                <?php
                echo $this->Form->input('id');
                echo "<div class='col-md-12'>".$this->Form->input('name',array()) . '</div>'; 
                echo "<div class='col-md-12'>".$this->Form->input('description',array()) . '</div>'; 
                ?>          
                <div class="col-md-12">
                    <!-- <strong>Currently Assigned Branches</strong> -->
                    <!-- <ul> -->
                    <?php 
                    $abs = json_decode($this->request->data['UserAccessControl']['users'],true);
                    // foreach ($abs as $b) {
                    //     $assigned_branches_user[$b] = $PublishedBranchList[$b];
                    //     echo "<li>".$PublishedBranchList[$b]."</li>";

                    // }
                    // echo "</ul>";
                    // $this->request->data['UserAccessControl']['selected_users'] = $selected_users;
                    
                    echo $this->Form->input('selected_users[]',array(
                            'label'=>'Select Users',
                            'id'=> 'SelectedUsers',
                            'name'=>'User.selected_users[]',
                            'multiple',
                            'options'=>$users,
                            'default'=>$abs
                        )) ; 
                
                    echo $this->Form->hidden('selected_users',array('name'=>'User.selected_users[]','value'=>$this->request->data['UserAccessControl']['selected_users']));
                    ?>
                </div>

                <div class="">
                	<div class="col-md-12">
                		 <?php foreach ($forms as $value => $data):
                ?>

                    <div class="box box-default file-box">
                        <div class="box-header">
                            <div class="panel-title">


                                <h6 class="user-access">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#<?php echo str_replace(' ', '', ucfirst($value)); ?>" style="float:none">
                                        <?php echo $value ?>
                                    </a>
                                    <?php
                                    echo "<span style='float:left;margin-top:-20px;margin-right:10px'>" . $this->Form->input('select_all_' . str_replace(' ', '', ucfirst($value)), array('type' => 'checkbox', 'default' => 0, 'style' => 'float:left', 'label' => FALSE)) . "</span>";
                                    ?>
                                </h6>



                            </div>
                        </div>
                    </div>
                    <div id="<?php echo str_replace(' ', '', ucfirst($value)); ?>" class="panel panel-collapse collapse">
                        <div class="box-body">

                            <script>

                                $('#UserAccessControlSelectAll<?php echo str_replace(' ', '', ucfirst($value)); ?>').on('click', function() {

                                    $("#<?php echo str_replace(' ', '', ucfirst($value)); ?>").find(':checkbox').prop('checked', this.checked);
                                    if (this.checked) {
                                        $("#<?php echo str_replace(' ', '', ucfirst($value)); ?>").find(':hidden').attr('value', 1);
                                    } else {
                                        $("#<?php echo str_replace(' ', '', ucfirst($value)); ?>").find(':hidden').attr('value', 0);
                                    }
                                });
                            </script>

                            <?php
                                foreach ($forms[$value] as $fkey => $fvalue):
                                    if ($fvalue) {
                            ?>

                                    <div class="row <?php echo $fkey; ?>">
                                        <div class="col-md-12" >
                                            <table>
                                                <tr><td width="25" class="chk-bk">
                                                        <h4><?php
                                                        echo $this->Form->input('select_all_' . str_replace(' ', '', ucfirst($value)) . Inflector::Humanize($fkey), array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view', 'style' => 'float:left;', 'label' => false));
                                                        echo "</h4></td><td><h4><strong>" . Inflector::Humanize($fkey) . "</strong></h4></td>";
                                                        ?>

                                                </tr>
                                            </table>
                                        </div>

                                        <script>

                                            $('#UserAccessControlSelectAll<?php echo str_replace(' ', '', ucfirst($value)); ?><?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>').on('click', function() {
                                                $("div.<?php echo $fkey; ?>").find(':checkbox').prop('checked', this.checked);
                                                if (this.checked) {
                                                    $("div.<?php echo $fkey; ?>").find(':hidden').attr('value', 1);
                                                } else {
                                                    $("div.<?php echo $fkey; ?>").find(':hidden').attr('value', 0);
                                                }
                                            });
                                        </script>

                                        <?php
                                            foreach ($fvalue['actions'] as $act):

                                                if ($act == 'index') {
                                                    echo "<div class='col-md-2'>";
                                                    echo $this->Form->input('ACL.user_access.' . $fkey . '.view', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                    if (strtolower($fkey) == 'meetings') {
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.index', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                       echo $this->Form->hidden('ACL.user_access.' . $fkey . '.advanced_search', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                    } elseif (strtolower($fkey) == 'materials') {
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.index', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                       echo $this->Form->hidden('ACL.user_access.' . $fkey . '.advanced_search', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                    } elseif (strtolower($fkey) == 'delivery_challans') {
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.index', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.advanced_search', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.inbound', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.outbound', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                    } elseif (strtolower($fkey) == 'internal_audit_plans') {
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.index', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.advanced_search', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.view_plan', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                    }  elseif (strtolower($fkey) == 'corrective_preventive_actions') {
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.index', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.advanced_search', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.capa_assigned', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.get_capa_index', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.get_ncs', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.capa_advanced_search', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.capa_status', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));

                                                    } elseif(strtolower($fkey) == 'products'){
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.product_design', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.index', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.product_upload', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                    }else {
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.index', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.advanced_search', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_view'));
                                                       
                                                    }
                                                    echo "</div>";
                                                    ?>
                                                    <script>
                                                    if($('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>View').is(':checked')){
                                                        $('#UserAccessControlSelectAll<?php echo str_replace(' ', '', ucfirst($value)); ?><?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>').prop('checked',true);
                                                        $('#UserAccessControlSelectAll<?php echo str_replace(' ', '', ucfirst($value)); ?>').prop('checked',true);

                                                    }
                                                        $('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>View').on('click', function() {
                                                        if(($('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>Add').prop('checked')) || ($('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>Edit').prop('checked'))){
                                                            return false;
                                                        }else{
                                                            if (this.checked) {
                                                                $(".<?php echo $fkey; ?>_view").attr('value', 1);
                                                            } else {
                                                                $(".<?php echo $fkey; ?>_view").attr('value', 0);
                                                                }
                                                            }
                                                        });
                                                    </script>

                                                    <?php
                                                } elseif ($act == 'add_ajax' || $act == 'plan_add_ajax') {
                                                    echo "<div class='col-md-2'>";
                                                    echo $this->Form->input('ACL.user_access.' . $fkey . '.add', array('type' => 'checkbox', 'default' => 0));


                                                   if (strtolower($fkey) == 'meetings') {
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.add_ajax', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_add'));
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.add_meeting_topics', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_add'));
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.after_meeting', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_add'));
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.meeting_detail_lists', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_add'));
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.before_meeting_view', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_add'));
                                                    }  elseif (strtolower($fkey) == 'internal_audit_plans') {
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.plan_add_ajax', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_add'));
                                                    }

                                                    else {
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.add_ajax', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_add'));
                                                    }
                                                    echo $this->Form->hidden('ACL.user_access.' . $fkey . '.lists', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_add'));

                                                    echo "</div>";
                                                    ?>

                                                    <script>
                                                        if($('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>Add').is(':checked')){
                                                            $('#UserAccessControlSelectAll<?php echo str_replace(' ', '', ucfirst($value)); ?><?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>').prop('checked',true);
                                                            $('#UserAccessControlSelectAll<?php echo str_replace(' ', '', ucfirst($value)); ?>').prop('checked',true);
                                                        }
                                                        $('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>Add').on('click', function() {
                                                            if (this.checked) {
                                                                $(".<?php echo $fkey; ?>_add").attr('value', 1);
                                                                $('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>Edit').prop('checked',true);
                                                                $('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>Edit').prop('readonly',true);
                                                                $('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>View').prop('checked',true);
                                                                $('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>View').prop('readonly',true);                                                                   $(".<?php echo $fkey; ?>_edit").attr('value', 1);
                                                                $(".<?php echo $fkey; ?>_view").attr('value', 1);
                                                             } else {
                                                                $(".<?php echo $fkey; ?>_add").attr('value', 0);
                                                                $('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>Edit').prop('checked',false);
                                                                $('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>Edit').prop('readonly',false);
                                                                $('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>View').prop('checked',false);
                                                                $('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>View').prop('readonly',false);
                                                                $(".<?php echo $fkey; ?>_edit").attr('value', 0);
                                                                $(".<?php echo $fkey; ?>_view").attr('value', 0);
                                                            }
                                                        });
                                                    </script>

                                                    <?php
                                                } elseif ($act == 'task') {
                                                    echo "<div class='col-md-2'>";
                                                    echo $this->Form->input('ACL.user_access.' . $fkey . '.task', array('type' => 'checkbox', 'default' => 0));
                                                    if (strtolower($fkey) == 'users') {
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.task', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_task'));
                                                    }
                                                    echo "</div>";
                                                    ?>
                                                    <script>
                                                        if($('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>Task').is(':checked')){
                                                            $('#UserAccessControlSelectAll<?php echo str_replace(' ', '', ucfirst($value)); ?><?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>').prop('checked',true);
                                                            $('#UserAccessControlSelectAll<?php echo str_replace(' ', '', ucfirst($value)); ?>').prop('checked',true);
                                                        }
                                                        $('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>Task').on('click', function() {
                                                            if (this.checked) {
                                                                $(".<?php echo $fkey; ?>_task").attr('value', 1);
                                                            } else {
                                                                $(".<?php echo $fkey; ?>_task").attr('value', 0);
                                                            }
                                                        });
                                                    </script>

                                                    <?php
                                                } elseif ($act == 'edit') {
                                                    echo "<div class='col-md-2'>";
                                                    echo $this->Form->input('ACL.user_access.' . $fkey . '.edit', array('type' => 'checkbox', 'default' => 0));


                                                    if (strtolower($fkey) == 'internal_audits') {
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.edit_popup', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_edit'));
                                                    } elseif (strtolower($fkey) == 'meetings') {
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.after_meeting', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_edit'));
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.meeting_detail_lists', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_edit'));
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.before_meeting_view', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_edit'));
                                                    }  elseif (strtolower($fkey) == 'supplier_evaluation_reevaluations') {
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.inplace_edit', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_edit'));
                                                    } elseif (strtolower($fkey) == 'approvals') {
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.approve_many', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_edit'));
                                                    }




                                                    echo "</div>";
                                                    ?>
                                                    <script>
                                                        if($('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>Edit').is(':checked')){
                                                            $('#UserAccessControlSelectAll<?php echo str_replace(' ', '', ucfirst($value)); ?><?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>').prop('checked',true);
                                                            $('#UserAccessControlSelectAll<?php echo str_replace(' ', '', ucfirst($value)); ?>').prop('checked',true);
                                                        }
                                                       $('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>Edit').on('click', function() {
                                                       if($('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>Add').prop('checked')){
                                                            return false;
                                                        }else{
                                                            if (this.checked) {
                                                                $(".<?php echo $fkey; ?>_edit").attr('value', 1);
                                                                $('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>View').prop('checked',true);
                                                                $('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>View').prop('readonly',true);                                                                   $(".<?php echo $fkey; ?>_view").attr('value', 1);
                                                            } else {
                                                                $(".<?php echo $fkey; ?>_edit").attr('value', 0);
                                                                $('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>View').prop('checked',false);
                                                                $('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>View').prop('readonly',false);
                                                                $(".<?php echo $fkey; ?>_view").attr('value', 0);
                                                               }
                                                            }
                                                        });
                                                    </script>

                                                    <?php
                                                } elseif ($act == 'delete') {
                                                    echo "<div class='col-md-2'>";
                                                    echo $this->Form->input('ACL.user_access.' . $fkey . '.delete', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_delete'));
                                                    echo $this->Form->hidden('ACL.user_access.' . $fkey . '.purge', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_delete'));
                                                    echo $this->Form->hidden('ACL.user_access.' . $fkey . '.delete_all', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_delete'));
                                                    echo $this->Form->hidden('ACL.user_access.' . $fkey . '.purge_all', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_delete'));
                                                    echo $this->Form->hidden('ACL.user_access.' . $fkey . '.restore', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_delete'));
                                                    echo $this->Form->hidden('ACL.user_access.' . $fkey . '.restore_all', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_delete'));

                                                    echo "</div>";
                                                    ?>
                                                    <script>
                                                        if($('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>Delete').is(':checked')){
                                                            $('#UserAccessControlSelectAll<?php echo str_replace(' ', '', ucfirst($value)); ?><?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>').prop('checked',true);
                                                            $('#UserAccessControlSelectAll<?php echo str_replace(' ', '', ucfirst($value)); ?>').prop('checked',true);
                                                        }
                                                        $('#ACLUserAccess<?php echo str_replace(' ', '', Inflector::Humanize($fkey)); ?>Delete').on('click', function() {
                                                            if (this.checked) {
                                                                $(".<?php echo $fkey; ?>_delete").attr('value', 1);
                                                            } else {
                                                                $(".<?php echo $fkey; ?>_delete").attr('value', 0);
                                                            }
                                                        });
                                                    </script>

                                                    <?php
                                                } elseif ($act == 'report') {
                                                    echo "<div class='col-md-2'>";
                                                    echo $this->Form->input('ACL.user_access.' . $fkey . '.report', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_report'));
                                                    if (strtolower($fkey) == 'internal_audits') {
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.audit_report', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_report'));
                                                    } elseif (strtolower($fkey) == 'internal_audit_plans') {
                                                        echo $this->Form->hidden('ACL.user_access.' . $fkey . '.plan_report', array('type' => 'checkbox', 'default' => 0, 'class' => $fkey . '_report'));
                                                    }
                                                    echo "</div>";
                                                } elseif (
                                                        $act != 'index' && $act != 'box' && $act != 'lists' && $act != 'add' && $act != 'approve' &&
                                                        $act != 'add_ajax' && $act != 'view' && $act != 'edit' && $act != 'search'  &&
                                                        $act != 'advanced_search' && $act != 'audit_details_add_ajax' && $act != 'send_email' &&
                                                        $act != 'meeting_detail_index' && $act != 'before_meeting_view' && $act != 'meeting_detail_lists' &&
                                                        $act != 'add_meeting_topics' && $act != 'add_after_meeting_topics' && $act != 'after_meeting' &&
                                                        $act != 'capa_status' && $act != 'get_details' && $act != 'get_supplier_registration_title' &&
                                                        $act != 'edit_popup' && $act != 'report' && $act != 'audit_report' &&
                                                        $act != 'get_purchase_order' && $act != 'get_challan_details' && $act != 'get_challan_number' &&
                                                        $act != 'inbound' && $act != 'outbound' &&
                                                        $act != 'add_purchase_order_details' && $act != 'get_purchase_order_number' &&
                                                        $act != 'add_branches' && $act != 'add_departments' && $act != 'get_dept_clauses' &&
                                                        $act != 'plan_report' && $act != 'view_plan' && $act != 'get_supplier_list' && $act != 'get_employee_email'
                                                        && $act != 'user_access' && $act != 'reset_password' && $act != 'save_user_password' && $act != 'login' &&
                                                        $act != 'logout' && $act != 'dashboard' && $act != 'access_denied' && $act != 'terms_and_conditions' &&
                                                        $act != 'check_email' && $act != 'change_password' && $act != 'branches_gauge' && $act != 'unblock_user'
                                                        && $act != 'register' && $act != 'activate' && $act != 'branches_gauge' && $act != 'add_formats' && $act != 'product_design'
                                                        && $act != 'customer_complaint_status' && $act != 'get_unique_values' && $act != 'get_branch_name' && $act != 'approve_many'
                                                        && $act != 'add_new_software' && $act != 'inplace_edit' && $act != 'meeting_view' && $act != 'add_questions'
                                                        && $act != 'appraisal_notification_email' && $act != 'self_appraisals' && $act != 'get_material_name' && $act != 'get_material_qc_required'
                                                        && $act!='get_delivered_material_qc' && $act!='get_customer_complaints' && $act!='capa_assigned' && $act!='get_capa_index' && $act!= 'get_ncs'
                                                        && $act!='capa_advanced_search' && $act!='get_task' && $act!= 'get_device_maintainance' && $act!= 'get_next_calibration' && $act!= 'product_upload'
                                                        && $act!='expiry_reminder' && $act!='login_reminder' && $act!='add_followups' && $act!='followup_count' && $act != 'get_dc_details'
                                                        && $act != 'get_material' && $act!='get_material_check' && $act!='get_process' && $act!='material_count'  && $act!='get_material_details' && $act!='get_batch' && $act!='get_batch'  && $act!='checkProdMatDate' && $act!='check_complaint_number' && $act!='quality_check' && $act!='appraisal_review' && $act!='get_questions' && $act!='internal_audit_uploads' && $act!='get_task_name' && $act!='get_questions'  && $act!='internal_audit_uploads' && $act!='welcome' && $act!='remove_sample' && $act!='insert_sample_data' && $act!='sample_file_upload' && $act!='check_username' && $act!='appraisal_answers' && $act!='smtp_details' && $act!='dashboard_files' && $act!='check_registration' && $act!='demo_delete') {
                                                    echo "<div class='col-md-2'>";
                                                    echo $this->Form->input('ACL.user_access.' . $fkey . '.' . $act, array('type' => 'checkbox', 'default' => 0));
                                                    echo "</div>";
                                                }?>
                                                   
                                            <?php endforeach;?>
                                    </div>
                                    <hr />
                                <?php } endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                	</div>
                </div>
            
<?php
		
		echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
		echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
		echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
		echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
		?>

</div>
<div class="row">
		

<?php

		
	if ($showApprovals && $showApprovals['show_panel'] == true) {
		
		echo $this->element('approval_form');
		
	} else {
		
		echo $this->Form->input('publish', array('label' => __('Publish')));
		
	}
?>
<?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id')); ?>
<?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
<?php echo $this->Form->end(); ?>

<?php echo $this->Js->writeBuffer();?>
</div>
</div>
<script> 
	$("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat:'yy-mm-dd',
    }); </script>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#userAccessControls_ajax')));?>
<?php echo $this->Js->writeBuffer();?>
</div>
<script>
    $.validator.setDefaults({
    	ignore: null,
    	errorPlacement: function(error, element) {
   //          if (
                
			// $(element).attr('name') == 'data[UserAccessControl][division_id]')
			// 			{	
   //              $(element).next().after(error);
   //          } else {
   //              $(element).after(error);
   //          }
        },
    });
    
    $().ready(function() {
    	jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");

        $('#UserAccessControlEditForm').validate({        	
            rules: {
				// "data[UserAccessControl][division_id]": {
    //             		greaterThanZero: true,
				// 	},
                
            }
        }); 
			
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
            if($('#UserAccessControlEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                $('#UserAccessControlEditForm').submit();
            }

        });

		// $('#UserAccessControlDivisionId').change(function() {
		// 	if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
		// 		$(this).next().next('label').remove();
		// 	}
		// });	

    });
</script>