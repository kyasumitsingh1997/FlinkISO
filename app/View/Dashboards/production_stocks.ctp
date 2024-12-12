<div class="row">
    <div class="col-md-12">
        <h4><?php echo __('Material Stock Status');?></h4>
        <table class="table table-bordered table-responsive table-condensed">
            <tr>
                <th><?php echo __('Material');?></th>
                <th><?php echo __('Available Quantity');?></th>
                <th><?php echo __('Action');?></th>
            </tr>
        <?php 
        $x = 0;
        foreach ($stocks as $stock) { ?>
        <tr>
                <td><?php echo $stock['material']['name'];?></td>
                <td><?php echo $stock['stock'];?></td>
                <td><?php echo $this->Html->link('Place Order',array('controller'=>'purchase_orders','action'=>'lists','material_id'=>$stock['material']['id'],'type'=>'1'),array('class'=>'btn btn-xs btn-danger'));?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>