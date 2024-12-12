<h3><?php echo __('Production History');?></h3>
<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
<tr>
    <th><?php echo __('Batch #'); ?></th>
    <!-- <th><?php echo __('Production Planned'); ?></th> -->
    <th><?php echo __('Production Date'); ?></th>
    <th><?php echo __('Actual Production #'); ?></th>
    <th><?php echo __('Rejections'); ?></th>
    <th><?php echo __('Balance');?></th>
    <th><?php echo __('Branch'); ?></th>
    <th><?php echo __('Prepared By'); ?></th>
    <th><?php echo __('Approved By'); ?></th>
    <!-- <th><?php echo __('Current Status'); ?></th> -->
    <th><?php echo __('publish'); ?></th>
</tr>
<?php 
$balance = $planned;
if ($productions) {
        $x = 0;
        
        foreach ($productions as $production):
        $balance = $balance - $production['Production']['actual_production_number'];
        $pro = $pro + $production['Production']['actual_production_number'];
        $rej = $rej + $production['Production']['rejections'];
        $t = $planned - $pro - $rej;
?>
<tr class="on_page_src">    
    <td><?php echo h($production['Production']['batch_number']); ?>&nbsp;</td>

    <!-- <td><?php echo h($production['ProductionWeeklyPlan']['production_planned']); ?>&nbsp;</td> -->
    <td><?php echo h($production['Production']['production_date']); ?>&nbsp;</td>
    <td><?php echo h($production['Production']['actual_production_number']); ?>&nbsp;</td>
    <td><?php echo h($production['Production']['rejections']); ?>&nbsp;</td>
    <!-- <td><?php echo h($balance); ?>&nbsp;</td> -->
    <td><?php echo round($production['Production']['balance'] + $production['Production']['rejections']);?></td>
    <td>
        <?php echo $this->Html->link($production['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $production['Branch']['id'])); ?>
    </td>
    <td><?php echo h($production['PreparedBy']['name']); ?>&nbsp;</td>
    <td><?php echo h($production['ApprovedBy']['name']); ?>&nbsp;</td>
    <!-- <td><?php echo h($currentStatus[$production['Production']['current_status']]); ?>&nbsp;</td> -->
    
    
    <td width="60">
        <?php if ($production['Production']['publish'] == 1) { ?>
            <span class="fa fa-check"></span>
        <?php } else { ?>
            <span class="fa fa-ban"></span>
        <?php } ?>&nbsp;</td>
</tr>
<?php
    $x++;
    endforeach;
    } else {
?>
<tr><td colspan=19>No results found</td></tr>
<?php } ?>
</table>
<?php echo $this->Form->hidden('Production.production_planned',array('default'=>$planned )); ?>
<?php echo $this->Form->hidden('Production.balance',array('default'=>$balance )); ?>
<div class="row"><div class="col-md-12 text-right"><h2>Balance : <?php echo $t;?></h2></div></div>
<?php if($balance == 0 && $this->data['Production']['current_status'] != 0){ ?> 
<script type="text/javascript">
    $("#submit_id").attr('disabled',true);
</script>
<?php } ?>
<script type="text/javascript">
    $("#ProductionProductionDate").attr('disabled',false);
    $("#ProductionProductionDate").datepicker({         
        startDate: '<?php echo date("yyyy-MM-dd",strtotime($weeklyplan["ProductionWeeklyPlan"]["start_date"]))?>',
        // startDate : '06/15/2017',
        // format: 'MM/DD/YYYY',
    });
</script>

