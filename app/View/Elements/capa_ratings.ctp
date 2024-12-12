<?php foreach($capaRatings as $name => $values): ?>
	<div class="col-md-4">
		<ul class="list-group">
	        <li class="list-group-item">
	          <strong><?php echo $name ?> CAPA</strong>
	        </li>
	        <?php foreach($values as $type => $value): ?>
	        	<li class="list-group-item">		          
		              <span class="default badge pull-right btn-warning"><?php echo $value;?></span><?php echo $type;?>
		        </li>
			<?php endforeach;?>	        	        
	      </ul>
	  </div>
<?php endforeach;?>