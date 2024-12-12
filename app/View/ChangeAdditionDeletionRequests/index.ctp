
    <?php echo $this->Session->flash(); ?>
        <div class="nav">
            <div id="cr-tabs">
                <ul>
                    <li><?php echo $this->Html->link(__('Under Process'), array('action' => 'index_filter', 2)); ?></li>
                    <li><?php echo $this->Html->link(__('Accepted'), array('action' => 'index_filter', 1)); ?></li>
                    <li><?php echo $this->Html->link(__('Rejected'), array('action' => 'index_filter', 0)); ?></li>
                    <li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator', 'class' => 'pull-right')); ?></li>
                </ul>
            </div>
        </div>
        <div id="changeAdditionDeletionRequests_tab_ajax"></div>
    </div>

    <script>
        $(function() {
            $("#cr-tabs").tabs({
                beforeLoad: function(event, ui) {
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
        }});
</script>
