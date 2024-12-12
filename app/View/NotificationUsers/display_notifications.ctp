<p class=""><?php if($notificationUsers){ ?>
<?php foreach ($notificationUsers as $notification): ?>
<strong><?php echo $notificationType[$notification['Notification']['notification_type_id']] ?> Notification : <?php echo h($notification['Notification']['title']); ?>&nbsp;</strong><br/>
<?php echo  $notification['Notification']['message']; ?>&nbsp;<br />
<?php echo $this->Time->nice($notification['Notification']['start_date']); ?>&nbsp;
<?php endforeach; ?>
</p>
<?php }else{ ?>
<div style="padding: 5px" class="text-center">
        <p>Welcome to FlinkISO&trade;</p><p>FlinkISO Ver-1.005</p><p>On Premise Non-distributable Commercial Edition.</p>
</div>
<?php } ?>

			<?php
			echo $this->Paginator->options(array(
			'update' => '#notification-center',
			'evalScripts' => true,
			'before' => $this->Js->get('#notification-indicator')->effect('fadeIn', array('buffer' => false)),
			'complete' => $this->Js->get('#notification-indicator')->effect('fadeOut', array('buffer' => false)),
			));

			?>
			<ul class="pager custom-pager">
			<?php

		echo "<li>".$this->Paginator->numbers(array('separator' => '','class'=>'badge btn-primary'))."</li>";

	?>
			</ul>

<script>$.ajaxSetup({beforeSend:function(){$("#notification-indicator").show();},complete:function(){$("#notification-indicator").hide();}});</script>
<?php echo $this->Js->writeBuffer();?>