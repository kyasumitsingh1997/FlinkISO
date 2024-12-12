<script type="text/javascript">
    $(document).ready(function() {
        $('table th a, .pag_list li span a').on('click', function() {
            var url = $(this).attr("href");
            $('#main_messages_inbox').load(url);
            return false;
        });
    });
</script>
<?php echo $this->Session->flash(); ?>
<div id ="main_messages_inbox">
<div class="" id="messages_inbox_ajax">
    <table class="table table-condensed">
        <tr>
            <th>Subject</th>
            <th>From</th>
        </tr>
        <?php
        if($messages){            
            $x = 0;
            foreach ($messages as $message):
                if ($message['unread'] > 0)
                    echo "<tr class='read'>";
                else
                    echo "<tr class='read'>";
        ?>            
            <td><?php echo $this->Js->link(h($message['Message']['subject']), array('action' => 'reply', $message['Message']['id'],1), array('update' => '#messages_inbox_ajax', 'escape' => false)); ?></td>
            <td><small><?php echo $message['CreatedBy']['name'] . '-(' . $message['from'][0]['Branch']['name'] . ' Branch)'; ?><small><?php echo date('D-d M Y / h:i A', strtotime(h($message['MessageUserInbox']['created']))); ?></small></small></td>
        </tr>
        <?php
            $x++;
            endforeach;
        }else{ ?>
            <tr><th>No messages found.</th></tr>
        <?php } ?>
    </table>
    <?php echo $this->Form->end(); ?>
    <?php
        $this->Paginator->options(array(
            'update' => '#main_messages_inbox',
            'evalScripts' => true,
            'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
            'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)),
        ));
    ?>

    <p class=""><?php
            echo $this->Paginator->options(array(
                'update' => '#main_messages_inbox',
                'evalScripts' => true,
                'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
                'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)),
            ));

            echo $this->Paginator->counter(array(
                'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
            ));
        ?>
    </p>
    <ul class="pagination ">
        <?php
            echo "<li class='previous'>" . $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled')) . "</li>";
            echo "<li>" . $this->Paginator->numbers(array('separator' => '')) . "</li>";
            echo "<li class='next'>" . $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled')) . "</li>";
        ?>
    </ul>
</div>
<?php echo $this->Js->writeBuffer(); ?>
</div>
