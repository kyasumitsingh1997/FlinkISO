
<div class="panel panel-danger">
		  <div class="panel-heading"><h3 class="panel-title">Blocked Users</h3>
		  </div>
		  <div class="panel-body">
            <table cellpadding="0" cellspacing="0" class="table table-condensed">
                <tr>
                    <th>Employee</th>
                    <th>Username</th>
                    <th></th>
                </tr>
                <?php if ($users) {
                        $x = 0;
                        foreach ($users as $user):
                ?>
                <tr>
                    
                    <td><?php echo $user['User']['name']; ?>&nbsp;</td>
                    <td><?php echo $user['User']['username']; ?>&nbsp;</td>                    
                    <td><?php echo $this->Html->link('Unblock',array('controller'=>'users','action'=>'unblock_user',$user['User']['id'],'redirect'=>'pm_dashboard'),array('class'=>'btn btn-sm btn-danger pull-right'));?></td>

                </tr>
                <?php
                    $x++;
                    endforeach;
                    } else {
                ?>
                <tr><td colspan=29><?php echo __('No results found'); ?></td></tr>
                <?php } ?>
            </table>
            </div>
			</div>
