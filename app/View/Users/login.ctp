<?php
/* if ($this->request->is('ajax') == true) { ?>
<?php echo $this->Session->flash('flash', array('params' => array('class' => 'alert-danger'))); ?>
<?php } else */
if ($this->request->is('ajax') != true) {
?>
<?php
    echo $this->Html->script(array(
        'jquery.validate.min',
        'jquery-form.min'
    ));
?><?php
    echo $this->fetch('script');
?>
<?php //echo $this->Session->flash('flash', array('params' => array('class' => 'alert-danger'))); 
?>
<script>
    $().ready(function() {
        $('#UserLoginForm').validate();
    })
</script>

<div  id="users_ajax"> <?php
    echo $this->Session->flash();
?>
	<div class="">
		<div class="row">
			<div class="col-md-6 col-xs-12 pull-left">
			
				<div class="panel">
					<div class="panel-body">
					<h3>Welcome To FlinkISO&trade;</h3>
					<h5>First Time Login?</h5>
					<p>Your registred email id & password which you used while installing FlinkISO is your username & passwords. Incase you have any difficulty login in, contact us at <a href="mailto:help@flinkiso.com">help@flinkiso.com</a>.</p>
					<h5>Configuring  login messages.</h5>
					<p>You can configure Welcome Message, Message from MR, Quality Policy, Vision & Mission statements after login from Companies settings page.</p>
					<p>Once configured, users will see those messages</p>			
				</div>
			</div>
		</div>
			
			<div class="col-md-6 col-xs-12 pull-right no-padding">
				<?php
    echo $this->Form->create('User', array(
        'controller' => 'login',
        'role' => 'form',
        'class' => ''
    ));
?>
				<div class="box box-info">
		            <div class="box-header with-border">
		              <h3 class="box-title">Login</h3>
		            </div>
		            <!-- /.box-header -->
		            <!-- form start -->
		            <form class="form-horizontal">
		              <div class="box-body">
		                <div class="form-group">
		                  <?php
    if (isset($this->request->params['pass'][0]))
        echo $this->Form->input('username', array(
            'value' => base64_decode($this->request->params['pass']['0']),
            'class' => 'form-control',
            'placeholder' => __('Please Enter username')
        ));
    else
        echo $this->Form->input('username', array(
            'class' => 'form-control',
            'placeholder' => __('Please Enter username')
        ));
?>
		                </div>
		                <div class="form-group">
		                  <?php
    echo $this->Form->input('password', array(
        'class' => 'form-control',
        'placeholder' => __('*********')
    ));
    ;
?>
		                </div>
		                
		              <!-- /.box-body -->
		              <div class="box-footer">
		                <?php
    echo $this->Html->link(__('Forgot password?'), array(
        'action' => 'reset_password'
    ), array(
        'class' => 'pull-left forgot-pwd'
    ));
?>
		                <?php
    echo $this->Form->submit(__('Submit'), array(
        'div' => false,
        'class' => 'btn btn-lg btn-info pull-right'
    ));
    echo $this->Form->end();
?>
		              </div>
		              <!-- /.box-footer -->
		            </form>
		          </div>
			</div>					
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title"><?php
    echo __('Latest News & Updates');
?></div>
					</div>
					<div class="panel-body">
						<?php
    try {
        $xml = Xml::build('http://www.flinkiso.com/flinkiso-updates/news.xml', array(
            'return' => 'simplexml'
        ));
        
        if ($xml) {
            foreach ($xml as $news):
                echo "<h5 class='text-info'>" . (string) $news->title . "<br /><small>" . (string) $news->date . "</small></h5>";
                echo "<p>" . (string) $news->description . "</p>";
                echo "<p><a href=" . (string) $news->link . " class='text-link' target='_blank'>" . (string) $news->link . "</a></p><br />";
            endforeach;
            
        } else {
            echo "Can not access updates";
        }
        
    }
    catch (Exception $e) {
        echo "<h5 class='text-danger'> Can not access updates</small></h5>";
    }
?>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="panel-title"><?php
    echo __('Spread The Quality Revolution!');
?><br />
							<small><?php
    echo __('Like us, connect with us ..');
?></small></div>
					</div>
					<div class="panel-body">
						<div class="fb-like" data-href="https://www.facebook.com/flinkiso" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>
						<br />
						<br />
						<a class="twitter-timeline" href="https://twitter.com/FlinkISO" data-widget-id="510512945076256768">Tweets by @FlinkISO</a> 
						<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script> 
						<br />
						<!-- Place this tag where you want the +1 button to render. -->
						<div class="g-plusone" data-annotation="inline" data-width="300" data-href="https://plus.google.com/+FlinkisoQMS/posts"></div>
						
						<!-- Place this tag after the last +1 button tag. --> 
						<script type="text/javascript">
              (function() {
                var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                po.src = 'https://apis.google.com/js/platform.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
              })();
            </script> 
					</div>
				</div>
			</div>
	</div>
</div>
<?php
}
?>
