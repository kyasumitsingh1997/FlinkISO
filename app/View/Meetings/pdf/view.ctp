<h2><?php echo __('Meeting Details'); ?></h2>
    <table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
        <tr bgcolor="#FFFFFF"><td><?php echo __('Title'); ?></td>
            <td>
                <?php echo h($meeting['Meeting']['title']); ?>
                <span class="badge badge-info">
                    <?php 
                    echo $meetingStatuses[$meeting['Meeting']['meeting_status']];?>
                </span>
                &nbsp;
            </td>
        </tr>
        <tr bgcolor="#FFFFFF"><td><?php echo __('Standards'); ?></td>
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
        <tr bgcolor="#FFFFFF"><td><?php echo __('Previous Meeting'); ?></td>
            <td>
                <?php if($meeting['Meeting']['previous_meeting_date'] != '1970-01-01')echo h($meeting['Meeting']['previous_meeting_date']); ?>
                &nbsp;
            </td>
        </tr>
        <tr bgcolor="#FFFFFF"><td><?php echo __('Meeting Scheduled From'); ?></td>
            <td>
                <?php echo h($meeting['Meeting']['scheduled_meeting_from']); ?>
                &nbsp;
            </td>
        </tr>
        <tr bgcolor="#FFFFFF"><td><?php echo __('Meeting Scheduled To'); ?></td>
            <td>
                <?php echo h($meeting['Meeting']['scheduled_meeting_to']); ?>
                &nbsp;
            </td>
        </tr>
        <tr bgcolor="#FFFFFF"><td><?php echo __('Meeting Details'); ?></td>
            <td>
                <?php echo h($meeting['Meeting']['meeting_details']); ?>
                &nbsp;
            </td>
        </tr>
        <tr bgcolor="#FFFFFF"><td><?php echo __('Invitees'); ?></td>
            <td>
                <?php echo h($meeting['Meeting']['Invitees']); ?>
                &nbsp;
            </td>
        </tr>
        <tr bgcolor="#FFFFFF"><td><?php echo __('Chairperson'); ?></td>
            <td>
                <?php echo h($meeting['Meeting']['employee_by']); ?>
                &nbsp;
            </td>
        </tr>
        <tr bgcolor="#FFFFFF"><td><?php echo __('Branch'); ?></td>
            <td>
                <?php echo h($meeting['Meeting']['Branches']); ?>
                &nbsp;
            </td>
        </tr>
        <tr bgcolor="#FFFFFF"><td><?php echo __('Department'); ?></td>
            <td>
                <?php echo h($meeting['Meeting']['Departments']); ?>
                &nbsp;
            </td>
        </tr>
        <?php if (strtotime($meeting['Meeting']['actual_meeting_from']) >= strtotime($meeting['Meeting']['scheduled_meeting_from'])) { ?>
            <tr bgcolor="#FFFFFF"><td><?php echo __('Meeting Actual From'); ?></td>
                <td>
                    <?php echo $meeting['Meeting']['actual_meeting_from'] != '0000-00-00 00:00:00' ? $meeting['Meeting']['actual_meeting_from'] : ''; ?>
                    &nbsp;
                </td>
            </tr>
            <tr bgcolor="#FFFFFF"><td><?php echo __('Meeting Actual To'); ?></td>
                <td>
                    <?php echo $meeting['Meeting']['actual_meeting_to'] != '0000-00-00 00:00:00' ? $meeting['Meeting']['actual_meeting_to'] : '';?>
                    &nbsp;
                </td>
            </tr>
            <tr bgcolor="#FFFFFF"><td><?php echo __('Present'); ?></td>
                <td>
                    <?php echo h($meeting['Meeting']['Attendees']); ?>
                    &nbsp;
                </td>
            </tr>
            <tr bgcolor="#FFFFFF"><td><?php echo __('Absent'); ?></td>
                <td>
                    <?php echo h($meeting['Meeting']['Absent']); ?>
                    &nbsp;
                </td>
            </tr>
        <?php } ?>

            <tr bgcolor="#FFFFFF"><td><?php echo __('Prepared By'); ?></td>
                <td>
                    <?php echo h($meeting['PreparedBy']['name']); ?>
                    &nbsp;
                </td>
            </tr>
            <tr bgcolor="#FFFFFF"><td><?php echo __('Approved By'); ?></td>
                <td>
                    <?php echo h($meeting['ApprovedBy']['name']); ?>
                    &nbsp;
                </td>
            </tr>
            
    </table>
    
     <?php $i = 1; ?>     
	<?php foreach ($meetingTopics as $meetingTopic) { ?>
	<h3>Agenda <?php echo $i?></h3>
    	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
                        <?php
                            if($meetingTopic['MeetingTopic']['action_status'] == 0)$class="text-danger";
                            else $class = "text-success";
                        ?>                                     
                    <tr bgcolor="#FFFFFF"><td>
                            <?php echo __('Topic'); ?>
                        </td>
                        <td>
                            <h3><?php echo h($meetingTopic['MeetingTopic']['title']); ?></h3>
                        </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"><td>
                            <?php echo __('Details'); ?>
                        </td>
                        <td>                            
                            <?php echo nl2br($meetingTopic['MeetingTopic']['topic_text']); ?>
                        </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"><td width="25%">
                            <?php echo __('Current Status'); ?>
                        </td>
                        <td>
                            <?php echo $meetingTopic['MeetingTopic']['current_status'] ?>
                        </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"><td>
                            <?php echo __('Action Plan'); ?>
                        </td>
                        <td>
                            <?php echo h($meetingTopic['MeetingTopic']['action_plan']) ?>
                        </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"><td>
                            <?php echo __('Responsibility'); ?>
                        </td>
                        <td>
                            <?php echo h($meetingTopic['Employee']['name']) ?>
                        </td>
                    </tr>
                    <tr bgcolor="#FFFFFF">
                        <td>
                            <?php echo __('Target Date'); ?>
                        </td>
                        <td>
                            <?php if($meetingTopic['MeetingTopic']['target_date'] && $meetingTopic['MeetingTopic']['target_date'] != '1970-01-01')echo h($meetingTopic['MeetingTopic']['target_date']) ?>
                        </td>
                    </tr>
                    <tr bgcolor="#FFFFFF">
                        <td>
                            <?php echo __('Action Taken'); ?>
                        </td>
                        <td>
                            <?php echo h($meetingTopic['MeetingTopic']['action_taken']) ?>
                        </td>
                    </tr>
                    <tr bgcolor="#FFFFFF">
                        <td>
                            <?php echo __('Action Taken Date'); ?>
                        </td>
                        <td>
                            <?php if($meetingTopic['MeetingTopic']['action_taken_date'] && $meetingTopic['MeetingTopic']['action_taken_date'] != '1970-01-01')echo h($meetingTopic['MeetingTopic']['action_taken_date']) ?>
                        </td>
                    </tr>
                    <tr bgcolor="#FFFFFF">
                        <td>
                            <?php echo __('Status'); ?>
                        </td>
                        <td>
                            <?php echo ($meetingTopic['MeetingTopic']['action_status']? 'Closed' : 'Pending') ?>
                        </td>
                    </tr>
                
            </table>  
            <br />          
        <?php $i++; } ?>
