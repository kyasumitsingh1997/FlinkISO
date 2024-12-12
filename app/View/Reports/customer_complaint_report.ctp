<style>
  #loadGraph{height: 310px;}
/*# sourceMappingURL=chartist-plugin-tooltip.css.map */
</style>
<h3><small><?php echo $this->Html->link('Quality Management',array('controller'=>'dashboards','action'=>'mr'));?> / </small><?php echo __('Customer Complaints Report');?></h3>
<script>
    $("document").ready(function() {
    $("#loadGraph").load('customer_complaint_history/<?php echo base64_encode($this->request->data["reports"]["from"]) ?>/<?php echo base64_encode($this->request->data["reports"]["to"]) ?>');
    });
</script><div id="loadGraph"><span class="text-danger"><?php echo __('Loading Graph Please Wait...'); ?></span></div>
<div  id="main">
    <?php echo $this->Session->flash(); ?>
    <div class="CustomerComplaints ">        
        <script type="text/javascript">
            $(document).ready(function() {
                $('table th a, .pag_list li span a').on('click', function() {
                    var url = $(this).attr("href");
                    $('#main').load(url);
                    return false;
                });
            });
        </script>
        <div class="row">
            <div class="col-md-3">
                <div class="alert alert-danger">
                    <h3><?php echo __(' OPEN: '); ?><span class="pull-right"><?php echo $open ?></span></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="alert alert-info">
                    <h3><?php echo __(' CLOSED : '); ?><span class="pull-right"><?php echo $closed ?></span></h3>
                </div>
            </div>
            <div class="col-md-6">
                <div class="alert alert-success">
                    <h3><?php echo __(' CLOSED IN TIME : '); ?><span class="pull-right"><?php echo $setteled ?></span></h3>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
                <tr>
                    <th><?php echo __(Inflector::humanize('customer')); ?></th>
                    <th><?php echo __(Inflector::humanize('complaint_number')); ?></th>
                    <th><?php echo __(Inflector::humanize('Source')); ?></th>
                    <th><?php echo __(Inflector::humanize('complaint_date')); ?></th>
                    <th><?php echo __(Inflector::humanize('employee')); ?></th>
                    <th><?php echo __(Inflector::humanize('action_taken')); ?></th>
                    <th><?php echo __(Inflector::humanize('action_taken_date')); ?></th>
                    <th><?php echo __(Inflector::humanize('current_status')); ?></th>
                    <th><?php echo __(Inflector::humanize('settled_date')); ?></th>
                    <th><?php echo __(Inflector::humanize('authorized_by')); ?></th>
                </tr>
                <?php if ($customerComplaints) {
                        $x = 0;
                        foreach ($customerComplaints as $customerComplaint):
                ?>
                <tr <?php if ($customerComplaint['CustomerComplaint']['current_status'] <> 0) echo "class='text-success'"; ?> >
                    <td><?php echo $customerComplaint['Customer']['name']; ?>&nbsp;</td>
                    <td><?php echo h($customerComplaint['CustomerComplaint']['complaint_number']); ?>&nbsp;</td>
                    <td><?php echo h($customerComplaint['Product']['name']); ?>
<?php echo h($customerComplaint['DeliveryChallan']['challan_number']); ?>&nbsp;</td>
                    <td><?php echo h($customerComplaint['CustomerComplaint']['complaint_date']); ?>&nbsp;</td>
                    <td><?php echo $customerComplaint['Employee']['name']; ?>&nbsp;</td>
                    <td><?php echo h($customerComplaint['CustomerComplaint']['action_taken']); ?>&nbsp;</td>
                    <td><?php echo h($customerComplaint['CustomerComplaint']['action_taken_date']); ?>&nbsp;</td>
                    <td><?php echo $customerComplaint['CustomerComplaint']['current_status'] ? __('Close') : __('Open'); ?>&nbsp;</td>
                    <td><?php echo h($customerComplaint['CustomerComplaint']['settled_date']); ?>&nbsp;</td>
                    <td><?php echo h($customerComplaint['AuthorisedBy']['name']); ?>&nbsp;</td>

                </tr>
                <?php
                    $x++;
                    endforeach;
                    } else {
                ?>
                <tr><td colspan=23><?php echo __('No results found'); ?></td></tr>
                <?php } ?>
            </table>
        </div>
    </div>

<?php echo $this->Js->writeBuffer(); ?>

<!--<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>-->
