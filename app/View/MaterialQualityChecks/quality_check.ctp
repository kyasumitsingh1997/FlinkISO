<div  id="main">
    <?php echo $this->Session->flash(); ?>
    <div class="customers ">
        <div class="nav">
            <div id="tabs">
                <ul>
                    <?php
                        $i = 1;
                        foreach ($materialQualityChecks as $materialQualityCheck) {
                            if (isset($materialQualityCheck['disable']) && $materialQualityCheck['disable'] == 1) {
                                $class = 'disabled';
                            } else {
                                $class = '';
                            }
                    ?>
                        <li><?php echo $this->Html->link(__('Step') . ' - ' . $i, array(
                            'controller' => 'material_quality_check_details', 
                            'action' => 'add_quality_check', 
                            $materialQualityCheck['MaterialQualityCheck']['id'], 
                            $this->request->params['pass'][1],
                            'delivery_challan_id'=>$deliveryChallanId, 
                            'material_id'=> $materialId, 
                            'delivery_challan_detail_id'=> $deliveryChallanDetailId,
                            'class'=> $class,
                            'approval'=>$this->request->params['named']['approval']
                        )); ?></li>

                    <?php
                        $i++;
                        }
                    ?>
                    <li><?php echo $this->Html->link(__('Add To Stock'), array('controller' => 'material_quality_check_details', 'action' => 'add_to_stock', 
                    'delivery_challan_id'=>$deliveryChallanId, 'material_id'=> $materialId, 'class'=> $class, 'delivery_challan_detail_id'=> $deliveryChallanDetailId)); ?></li>
                </ul>
            </div>
        </div>
        <div id="customers_tab_ajax"></div>
    </div>
</div>

<script>
    $(function() {
        $("#tabs").tabs({
            beforeLoad: function(event, ui) {
                $(ui.panel).siblings('.ui-tabs-panel').empty();
                ui.jqXHR.error(function() {
                    ui.panel.html(
                            "Error Loading ... " +
                            "Please contact administrator.");
                });
            }
        });
    });
</script>

<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>
