<div class="col-md-12">
    <h3><?php echo __('Material used');?></h3>
    <table class="table table-bordered table-responsive table-condensed">
        <tr>
            <th><?php echo __('Material');?></th>
            <th><?php echo __('Available Quantity');?></th>
            <th><?php echo __('Qty Consumed');?></th>
            <th><?php echo __('Unit');?></th>
            <th><?php echo __('Scale');?></th>
            <th><?php echo __('Action');?></th>
        </tr>
    <?php 
    $x = 0;
    foreach ($stocks as $stock) { ?>
    <?php echo $this->Form->hidden('Stock.'.$x.'.material_id',array('default'=>$stock['stock']['material']['id']));?>
    <?php echo $this->Form->hidden('Stock.'.$x.'.production_date',array('default'=>date('Y-m-d')));?> 
    <?php echo $this->Form->hidden('Stock.'.$x.'.inhand',array('default'=>$stock['stock']['stock']));?>    
         <tr>
            <td><?php echo $stock['stock']['material']['name'];?></td>
            <td><?php echo $stock['stock']['stock'];?></td>
            <td><?php echo $this->Form->input('Stock.'.$x.'.quantity_consumed',array('label'=>false));?></td>
            <td id="unit_<?php echo $x;?>"><?php echo $units[$stock['stock']['material']['unit_id']];?></td>
            <td id="scale_<?php echo $x;?>"><?php echo $stock['ProductMaterial']['quantity'];?></td>
            <td><?php echo $this->Html->link('Place Order',array('controller'=>'purchase_orders','action'=>'lists','material_id'=>$stock['stock']['material']['id'],'type'=>'1'),array('class'=>'btn btn-xs btn-danger'));?></td>
        </tr>
        <script type="text/javascript">
            $().ready(function(){
                var scale = 0;
                var total = 0;
                scale = $("#scale_<?php echo $x;?>").html();
                total = parseInt(scale) * $("#ProductionActualProductionNumber").val();
                $("#Stock<?php echo $x;?>QuantityConsumed").val(total);
            });
            $("#ProductionActualProductionNumber").keyup(function(){
                var scale = 0;
                var total = 0;
                scale = $("#scale_<?php echo $x;?>").html();
                total = parseInt(scale) * $("#ProductionActualProductionNumber").val();
                $("#Stock<?php echo $x;?>QuantityConsumed").val(total);
                // alert(total);

            });
        </script>   
        <?php $x++;} ?>
    </table>
</div>
