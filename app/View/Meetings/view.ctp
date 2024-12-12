<div id="meetings_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="meetings form col-md-8">
            <h4><?php echo $this->element('breadcrumbs') . __('View Meeting'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>
                <?php echo $this->Html->link(__('Edit'), '#edit', array('id' => 'edit', 'class' => 'label btn-info', 'data-toggle' => 'modal')); ?>
                <?php echo $this->Html->link(__('Add'), '#add', array('id' => 'add', 'class' => 'label btn-info', 'data-toggle' => 'modal')); ?>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
            </h4>
            <table class="table table-responsive">
                <tr><td width="25%"><?php echo __('Meeting Type'); ?></td>
                    <td>
                        <h4>
                        <?php if($meeting['Meeting']['meeting_type'] == 0)echo "Internal Meeting"; ?>
                        <?php if($meeting['Meeting']['meeting_type'] == 1)echo "External Meeting"; ?>
                        &nbsp;</h4>
                    </td>
                </tr>
                <tr><td width="25%"><?php echo __('Sr. No'); ?></td>
                    <td>
                        <?php echo h($meeting['Meeting']['sr_no']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Title'); ?></td>
                    <td>
                        <?php echo h($meeting['Meeting']['title']); ?>
                        <span class="badge badge-info">
                            <?php 
                            echo $meetingStatuses[$meeting['Meeting']['meeting_status']];?>
                        </span>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Standards'); ?></td>
                    <td>
                        <?php if($meeting['Meeting']['standard_id']){
                            $stands = json_decode($meeting['Meeting']['standard_id'],true);
                            foreach ($stands as $key => $value) {
                                echo $standards[$value] .', ';
                            }
                        } ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Previous Meeting'); ?></td>
                    <td>
                        <?php if($meeting['Meeting']['previous_meeting_date'] != '1970-01-01')echo h($meeting['Meeting']['previous_meeting_date']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Meeting Scheduled From'); ?></td>
                    <td>
                        <?php echo h($meeting['Meeting']['scheduled_meeting_from']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Meeting Scheduled To'); ?></td>
                    <td>
                        <?php echo h($meeting['Meeting']['scheduled_meeting_to']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Meeting Details'); ?></td>
                    <td>
                        <?php echo h($meeting['Meeting']['meeting_details']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Invitees'); ?></td>
                    <td>
                        <?php echo h($meeting['Meeting']['Invitees']); ?>
                        <?php echo h($meeting['Meeting']['external_invities']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Chairperson'); ?></td>
                    <td>
                        <?php echo h($meeting['Meeting']['employee_by']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Internal/External Location'); ?></td>
                    <td>                        
                        <?php echo h($meeting['Meeting']['external_meeting_place']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Branch/External Location'); ?></td>
                    <td>
                        <?php echo h($meeting['Meeting']['Branches']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Department'); ?></td>
                    <td>
                        <?php echo h($meeting['Meeting']['Departments']); ?>
                        &nbsp;
                    </td>
                </tr>
                <?php if($meeting['Meeting']['meeting_type'] == 1){ ?> 
                    <?php if($meeting['Meeting']['supplier_registration_id'] && $meeting['Meeting']['supplier_registration_id'] != -1){ ?>
                        <tr><td><?php echo __('Supplier/Vendor'); ?></td>
                            <td>
                                <?php echo h($meeting['SupplierRegistration']['title']); ?>
                                &nbsp;
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if($meeting['Meeting']['customer_id'] && $meeting['Meeting']['customer_id'] != -1){ ?>
                        <tr><td><?php echo __('Customer'); ?></td>
                        <td>
                            <?php echo h($meeting['Customer']['name']); ?>
                            &nbsp;
                        </td>
                    <?php } ?>
                </tr>
                <?php } ?>
                <?php if (strtotime($meeting['Meeting']['actual_meeting_from']) >= strtotime($meeting['Meeting']['scheduled_meeting_from'])) { ?>
                    <tr><td><?php echo __('Meeting Actual From'); ?></td>
                        <td>
                            <?php echo $meeting['Meeting']['actual_meeting_from'] != '0000-00-00 00:00:00' ? $meeting['Meeting']['actual_meeting_from'] : ''; ?>
                            &nbsp;
                        </td>
                    </tr>
                    <tr><td><?php echo __('Meeting Actual To'); ?></td>
                        <td>
                            <?php echo $meeting['Meeting']['actual_meeting_to'] != '0000-00-00 00:00:00' ? $meeting['Meeting']['actual_meeting_to'] : '';?>
                            &nbsp;
                        </td>
                    </tr>
                    <tr><td><?php echo __('Present'); ?></td>
                        <td>
                            <?php echo h($meeting['Meeting']['Attendees']); ?>
                            &nbsp;
                        </td>
                    </tr>
                    <tr><td><?php echo __('Absent'); ?></td>
                        <td>
                            <?php echo h($meeting['Meeting']['Absent']); ?>
                            &nbsp;
                        </td>
                    </tr>
                <?php } ?>

                    <tr><td><?php echo __('Prepared By'); ?></td>
                        <td>
                            <?php echo h($meeting['PreparedBy']['name']); ?>
                            &nbsp;
                        </td>
                    </tr>
                    <tr><td><?php echo __('Approved By'); ?></td>
                        <td>
                            <?php echo h($meeting['ApprovedBy']['name']); ?>
                            &nbsp;
                        </td>
                    </tr>

                    <tr><td><?php echo __('Publish'); ?></td>
                    <td>
                        <?php if ($meeting['Meeting']['publish'] == 1) { ?>
                            <span class="fa fa-check"></span>
                        <?php } else { ?>
                            <span class="fa fa-ban"></span>
                        <?php } ?>&nbsp;</td>
                    &nbsp;
                </tr>
                <tr><td><?php echo __('Meeting Status'); ?></td>
                    <td><?php echo $meetingStatuses[$meeting['Meeting']['meeting_status']];?></td>
                    &nbsp;
                </tr>
            </table>

            <table class="table table-responsive">
                <?php $i = 1; ?>
                <?php foreach ($meetingTopics as $meetingTopic) { ?>
                    <tr><td colspan=2>
                        <?php
                            if($meetingTopic['MeetingTopic']['action_status'] == 0)$class="text-danger";
                            else $class = "text-success";
                        ?>
                        <h4 class="<?php echo $class;?>"><?php echo __('Meeting Topics') . " " . $i; ?> 
                            <small><?php echo $this->Html->link('Edit',array('controller'=>'meeting_topics','action'=>'edit',$meetingTopic['MeetingTopic']['id']),array('class'=>'btn btn-xs btn-info pull-right'));?></small>
                            </h4>
                    </td></tr>
                    <tr><td>
                            <?php echo __('Topic'); ?>
                        </td>
                        <td>
                            <?php echo h($meetingTopic['MeetingTopic']['title']); ?><br />
                            <?php echo nl2br($meetingTopic['MeetingTopic']['topic_text']); ?>
                        </td>
                    </tr>
                    <tr><td width="25%">
                            <?php echo __('Current Status'); ?>
                        </td>
                        <td>
                            <?php echo $meetingTopic['MeetingTopic']['current_status'] ?>
                        </td>
                    </tr>
                    <tr><td>
                            <?php echo __('Action Plan'); ?>
                        </td>
                        <td>
                            <?php echo h($meetingTopic['MeetingTopic']['action_plan']) ?>
                        </td>
                    </tr>
                    <tr><td>
                            <?php echo __('Responsibility'); ?>
                        </td>
                        <td>
                            <?php echo h($meetingTopic['Employee']['name']) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo __('Target Date'); ?>
                        </td>
                        <td>
                            <?php if($meetingTopic['MeetingTopic']['target_date'] && $meetingTopic['MeetingTopic']['target_date'] != '1970-01-01')echo h($meetingTopic['MeetingTopic']['target_date']) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo __('Action Taken'); ?>
                        </td>
                        <td>
                            <?php echo h($meetingTopic['MeetingTopic']['action_taken']) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo __('Action Taken Date'); ?>
                        </td>
                        <td>
                            <?php if($meetingTopic['MeetingTopic']['action_taken_date'] && $meetingTopic['MeetingTopic']['action_taken_date'] != '1970-01-01')echo h($meetingTopic['MeetingTopic']['action_taken_date']) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo __('Status'); ?>
                        </td>
                        <td>
                            <?php echo ($meetingTopic['MeetingTopic']['action_status']? 'Closed' : 'Pending') ?>
                        </td>
                    </tr>
                <?php $i++; } ?>
            </table>
            <?php echo $this->element('upload-edit', array('usersId' => $meeting['Meeting']['created_by'], 'recordId' => $meeting['Meeting']['id'])); ?>
        </div>
        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#meetings_ajax'))); ?>
    <?php $this->Js->get('#edit'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'edit', $meeting['Meeting']['id'], 'ajax'), array('async' => true, 'update' => '#meetings_ajax'))); ?>
    <?php $this->Js->get('#add'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'lists', null, 'ajax'), array('async' => true, 'update' => '#meetings_ajax'))); ?>
    <?php echo $this->Js->writeBuffer(); ?>
</div>

<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>
