<?php if($customerComplaint['CustomerComplaintAction']){ ?> 
<div class="col-md-12">
        <?php
            $i = 0;
            $openan = $closean = 0;
            foreach ($customerComplaint['CustomerComplaintAction'] as $customerComplaintAction) {?>
            <h2><?php echo __('Actions/Analysis');?> (<?php echo $i;?>)</h2>  
                <table class="table table-responsive">
                    <tr><th width="200"><?php echo __('Analysis'); ?></th>
                    <td>
                        <?php echo h($customerComplaintAction['analysis']); ?>
                        &nbsp;
                    </td></tr>
                    <!-- <tr><th><?php echo __('Cause'); ?></th>
                    <td>
                        <?php echo h($customerComplaintAction['cause']); ?>
                        &nbsp;
                    </td></tr> -->
                    <tr><th><?php echo __('Cause Category'); ?></th>
                    <td>
                        <?php echo h($cacustomeArray['causeCategories'][$customerComplaintAction['cause_category']]); ?>
                        &nbsp;
                    </td></tr>
                    <tr><th><?php echo __('Root Cause'); ?></th>
                    <td>
                        <?php echo h($cacustomeArray['rootCauses'][$customerComplaintAction['root_cause']]); ?>
                        &nbsp;
                    </td></tr>
                    <tr><th><?php echo __('Sub Cause'); ?></th>
                    <td>
                        <?php echo h($customerComplaintAction['sub_cause']); ?>
                        &nbsp;
                    </td></tr>
                    <tr><th><?php echo __('Solution'); ?></th>
                    <td>
                        <?php echo h($customerComplaintAction['solution']); ?>
                        &nbsp;
                    </td></tr>
                    <!-- <tr><th><?php echo __('Employee'); ?></th>
                    <td>
                        <?php echo h($PublishedEmployeeList[$customerComplaintAction['employee_id']]); ?>
                        &nbsp;
                    </td></tr>
                    <tr><th><?php echo __('Action Taken'); ?></th>
                    <td>
                        <?php echo h($customerComplaintAction['action_taken']); ?>
                        &nbsp;
                    </td></tr> -->
                    <tr><th><?php echo __('Action Taken Date'); ?></th>
                    <td>
                        <?php if($customerComplaintAction['action_taken_date'] != '1970-01-01') echo h($customerComplaintAction['action_taken_date']); ?>
                        &nbsp;
                    </td></tr>
                    <tr><th><?php echo __('Current Status'); ?></th>
                    <td>
                        <?php 
                            if($customerComplaintAction['current_status'] == 0){                                            
                                $openan++;
                            }elseif($customerComplaintAction['current_status'] == 1){                                            
                                $closean++;
                            }
                        ?>
                        <?php echo h($cacustomeArray['current_status'][$customerComplaintAction['current_status']]); ?>
                        &nbsp;
                    </td></tr>
                    <!-- <tr><th><?php echo __('Settled Date'); ?></th>
                    <td>
                        <?php if($customerComplaintAction['settled_date'] != '1970-01-01') echo h($customerComplaintAction['action_taken_date']); ?>
                        &nbsp;
                    </td></tr> -->
                    <!-- <tr><th><?php echo __('Authorized By'); ?></th>
                    <td>
                        <?php echo h($PublishedEmployeeList[$customerComplaintAction['authorized_by']]); ?>
                        &nbsp;
                    </td></tr> -->
                    
                    <tr><th><?php echo __('Weight'); ?></th>
                    <td>
                        <?php echo h($customerComplaintAction['weight']); ?>%
                        &nbsp;
                    </td></tr>
                    <tr><th><?php echo __('Compliant Rating'); ?></th>
                    <td>
                        <?php echo h($customerComplaintAction['compliant_rating']); ?>
                        &nbsp;
                    </td></tr>
                    <tr><th><?php echo __('Analysis Date'); ?></th>
                    <td>
                        <?php echo h($customerComplaintAction['analysis_date']); ?>
                        &nbsp;
                    </td></tr>
                    <!-- <tr><th><?php echo __('Analyser'); ?></th>
                    <td>
                        <?php echo h($PublishedEmployeeList[$customerComplaintAction['analyser_id']]); ?>                                    
                        &nbsp;
                    </td></tr> -->
                    <tr><th><?php echo __('Complaint Source'); ?></th>
                    <td>
                        <?php echo h($PublishedEmployeeList[$customerComplaintAction['complaint_source_id']]); ?>
                        &nbsp;
                    </td></tr>
                    <tr><th><?php echo __('Responsible Department'); ?></th>
                    <td>
                        <?php echo h($PublishedDepartmentList[$customerComplaintAction['responsible_department_id']]); ?>
                        &nbsp;
                    </td></tr>
                    <tr><th><?php echo __('Responsible Division'); ?></th>
                    <td>
                        <?php echo h($divisions[$customerComplaintAction['responsible_division_id']]); ?>
                        &nbsp;
                    </td></tr>
                    <tr><th><?php echo __('Owner'); ?></th>
                    <td>
                        <?php echo h($PublishedEmployeeList[$customerComplaintAction['owner_id']]); ?>                                    
                        &nbsp;
                    </td></tr>
                    <tr><th><?php echo __('Repetition'); ?></th>
                    <td>
                        <?php echo $customerComplaintAction['repetition']; ?>
                        &nbsp;
                    </td></tr>
                    <tr><th><?php echo __('Prepared By'); ?></th>

                <td><?php echo h($PublishedEmployeeList[$customerComplaintAction['prepared_by']]); ?>&nbsp;</td></tr>
                    <tr><th><?php echo __('Approved By'); ?></th>

                <td><?php echo h($PublishedEmployeeList[$customerComplaintAction['approved_by']]); ?>&nbsp;</td></tr>
                <tr><th><?php echo __('Publish'); ?></th>

                <td>
                <?php if($customerComplaintAction['publish'] == 1) { ?>
                <span class="fa fa-check"></span>
                <?php } else { ?>
                <span class="fa fa-ban"></span>
                <?php } ?>&nbsp;</td>
            &nbsp;</td></tr>
                <tr><th><?php echo __('Soft Delete'); ?></th>

                <td>
                <?php if($customerComplaintAction['soft_delete'] == 1) { ?>
                <span class="fa fa-check"></span>
                <?php } else { ?>
                <span class="fa fa-ban"></span>
                <?php } ?>&nbsp;</td>
            &nbsp;</td></tr>
            <tr>
                <td colspan="2"><?php echo $this->Html->link('Edit',array('controller'=>'customer_complaint_actions','action'=>'edit',$customerComplaintAction['id']),array('class'=>'btn btn-sm btn-warning'))?></td>
            </tr>
            </table>      
            <?php  $i++;} ?>                        
</div>

<?php }?>
