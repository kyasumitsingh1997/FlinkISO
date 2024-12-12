<?php
    $controller = $this->request->params['controller'];
    $model = Inflector::Classify($controller);
?>
<?php 
  if(isset($belongLinks) && count($belongLinks) > 0){ ?> 
  <!-- Related belongs to -->
    <ul class="list-group">
        <li class="list-group-item active"><?php echo __('Required Masters');?></li>
          <?php foreach ($belongLinks as $link) { ?>
            <?php 
              $skip = array('CreatedBy','ModifiedBy');
              if(isset($link) && $link != NULL){
              if(!in_array($skip, $belongLinks)){
                echo "<li class='list-group-item'>".$this->Html->link(Inflector::humanize($link),array('controller'=>Inflector::tableize($link),'action'=>'lists'),array('class'=>''))."</li>";  
                }
              }
              ?>
          <?php } ?>
    </ul>
<?php } ?>
<?php if(($this->action == 'view' or $this->action == 'edit' or $this->action == 'approve' ) && $this->Session->read('User.is_mr') == true){ ?>

<div id="display_fetched_files">
<p class="text-center"><br />
    <?php echo $this->Html->link(__('<span class="pull-left">Fetch All Related Files</span>'),'#display_fetched_files',array('id'=>'fetch_files', 'style'=>'width:100%; font-wight:bold; font-size:16px; text-align:left' ,'class'=>'btn btn-primary','escape'=>false)); ?>
</p>
</div>
<script type="text/javascript">
    $('#fetch_files').click(function(){
        $('#display_fetched_files').load(
            '<?php echo Router::url('/', true); ?>/file_uploads/related_files/<?php echo $model; ?>/<?php echo $this->request->params["pass"][0]; ?>/<?php echo $this->request->controller; ?>');
    });
</script>
<?php } ?>  


<div class="panel-group" id="accordion">
    
    <?php $acc = 1 ?>
    <?php foreach ($helps as $help): ?>
        <div class="panel panel-info">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $acc; ?>">
                        <?php echo $help['Help']['title']; ?>
                    </a>
                </h4>
            </div>
            <div id="collapse<?php echo $acc; ?>" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php echo $help['Help']['help_text']; ?>
                </div>
            </div>
        </div>
    <?php $acc++; ?>
    <?php endforeach; ?>
    <?php if ($this->request->params['controller'] != 'messages' && $this->request->params['controller'] != 'dashboards' && $this->request->params['controller'] != 'benchmarks' && $this->request->params['controller'] != 'file_uploads' && $this->request->params['controller'] !='installer') { ?>
    
        <?php if(($this->action == 'add_ajax' or $this->action == 'view' or $this->action == 'edit' or $this->action == 'approve' ) && $auto_approval_steps != NULL){ ?>    
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseApp"><?php echo __('Approval Process'); ?></a><span class="badge btn-warning pull-right"> #steps : <?php echo count($auto_approval_steps); ?></span>
                        </h4>
                    </div>
                    <div id="collapseApp" class="panel-collapse collapse">
                        <div class="panel-body">
                            <h4><?php echo $auto_approval_details['AutoApproval']['name']; ?> </h5>
                            <p><?php echo __("Process"); ?> : <?php echo $auto_approval_details['AutoApproval']['details']; ?> </p>
                            <?php foreach($auto_approval_steps as $approvals):

                                    echo "<h5>". $approvals['AutoApprovalStep']['name'] . " : <small>Fwd To : <strong>" . $approvals['User']['name']. "</strong></small></h5>";
                                    if($approvals['AutoApprovalStep']['show_details'] == 1) echo  "<p>".$approvals['AutoApprovalStep']['details']."</p>";                                
                                    endforeach;
                            ?>
                        </div>
                    </div>
                </div>  
            <?php } ?> 
        
        <?php if ($this->action != 'add' and $this->action != 'add_ajax' && $this->action != 'smtp_details') { ?>


            <?php
                if (isset($this->data[Inflector::Classify($this->name)]) && $this->data[Inflector::Classify($this->name)]['publish'] == 1 or $this->viewVars[Inflector::variable($model)][$model]['publish'] == 1)
                    echo '<div class="panel panel-success">';
                else
                    echo '<div class="panel panel-danger">';
            ?>

            

            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">

                        <?php
                            if (isset($this->data[Inflector::Classify($this->name)]) && $this->data[Inflector::Classify($this->name)]['publish'] == 1 or $this->viewVars[Inflector::variable($model)][$model]['publish'] == 1)
                                echo 'Approval History (Approved)';
                            else
                                echo 'Approval History (Pending)';
                        ?>
                        <?php
                            if (isset($this->data[Inflector::Classify($this->name)]) && $this->data[Inflector::Classify($this->name)]['publish'] == 1 or $this->viewVars[Inflector::variable($model)][$model]['publish'] == 1)
                                echo '<span class="badge btn-success pull-right">' . $approvalHistory['count'] . '</span>';
                            else
                                echo '<span class="badge btn-danger pull-right">' . $approvalHistory['count'] . '</span>';
                        ?>
                    </a>
                </h4>
            </div>

               


            <div id="collapseFour" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if(!empty($approvalHistory['history'])){?>

                        <?php foreach ($approvalHistory['history'] as $history): ?>
                            <strong>From :</strong> <?php echo $history['From']['name']; ?>
                            <br /><?php echo "<strong>To :</strong> " . $history['To']['name'] . " on " . $this->Time->nice($history['Approval']['created']) ?>
                            <br /><strong>Comment:</strong><?php echo $history['Approval']['comments']; ?>
                            <br /><br />
                            <div class="btn-group">   
                                <?php if($history['Approval']['status'] == 'Approved'){?><div class="btn btn-sm btn-success"><?php echo $history['Approval']['status'];}
                                if($history['Approval']['status'] == 'Forwarded' or $history['Approval']['status'] == 'Sent Back') { ?><div class="btn btn-sm btn-warning"><?php echo $history['Approval']['status']; }
                                if($history['Approval']['status'] == NULL ) { ?>
                                    <div class="btn btn-sm btn-danger" colspan="2"><?php echo 'Pending..';} ?></div>
                                    <script>
                                        $().ready(function(){$('#countdiv<?php echo $history['Approval']['id'];?>').load('<?php echo Router::url('/', true); ?>file_uploads/approval_ajax_file_count/<?php echo $history['Approval']['id'] ?>', function(response, status, xhr){});});
                                    </script>
                                    <?php echo $this->Html->link('Add/View Files','#',array('id'=>'btn'.$history['Approval']['id'],'class'=>'btn btn-sm btn-info')); ?>                            
                                    <div id="countdiv<?php echo $history['Approval']['id'];?>" class="btn-sm btn btn-primary"></div>                                    
                                </div>
                                <script>
                                    $('#btn<?php echo $history['Approval']['id'];?>').click(function(){
                                        $('#somediv<?php echo $history['Approval']['id'];?>').load(
                                            '<?php echo Router::url('/', true); ?>file_uploads/approval_ajax/<?php echo $history['Approval']['id'] ?>/<?php echo $history['Approval']['created_by']; ?>', function(response, status, xhr){});});
                                </script>
                                <div id="somediv<?php echo $history['Approval']['id'];?>"></div>
                                    <?php if($history['Approval']['status'] == 'Approved'){?>
                                    <?php if(isset($this->viewVars[Inflector::variable($model)]['PreparedBy']['name']) || isset($this->viewVars[Inflector::variable($model)]['ApprovedBy']['name'])):?>
                                        <br />
                                        <b>Prepared By: &nbsp;&nbsp;</b> : <?php echo $this->viewVars[Inflector::variable($model)]['PreparedBy']['name']; ?> <br />
                                        <b>Approved By: &nbsp;&nbsp;</b> : <?php echo $this->viewVars[Inflector::variable($model)]['ApprovedBy']['name']; ?>
                                    <?php endif;
                                    if(isset($this->data['PreparedBy']['name']) || isset($this->data['ApprovedBy']['name'])): ?>
                                        <br /><b>Prepared By: &nbsp;&nbsp;</b> : <?php echo $this->data['PreparedBy']['name']; ?>
                                        <br /><b>Approved By: &nbsp;&nbsp;</b> : <?php echo $this->data['ApprovedBy']['name']; ?>
                                    <?php endif; }?>
                            <hr/>
                        <?php endforeach;  
                    

                     } else{?>

                        <?php if(isset($this->viewVars[Inflector::variable($model)]['PreparedBy']['name']) || isset($this->viewVars[Inflector::variable($model)]['ApprovedBy']['name'])):?>
								<br /><b>Prepared By: &nbsp;&nbsp;</b> : <?php echo $this->viewVars[Inflector::variable($model)]['PreparedBy']['name']; ?>
								<br /><b>Approved By: &nbsp;&nbsp;</b> : <?php echo $this->viewVars[Inflector::variable($model)]['ApprovedBy']['name']; ?>
                        <?php endif; if(isset($this->data['PreparedBy']['name']) || isset($this->data['ApprovedBy']['name'])): ?>
								<br /><b>Prepared By: &nbsp;&nbsp;</b> : <?php echo $this->data['PreparedBy']['name']; ?></td>
								<br /><b>Approved By: &nbsp;&nbsp;</b> : <?php echo $this->data['ApprovedBy']['name']; ?></td>                                
                        <?php endif; }?>
                </div>
            </div>
        </div>

    <?php } if($controller!='installer' && $this->action != 'smtp_details' && $documentDetails != NULL) { ?>

    <div class="panel panel-info">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
                    <?php echo __('Document Details'); ?>
                </a>
                <span class="glyphicon glyphicon-file pull-right"></span>
            </h4>
        </div>
        <div id="collapseFive" class="panel-collapse collapse">
            <div class="panel-body">
                <dl>
                    <dt><?php echo __('Document Title'); ?></dt><dd><?php echo $documentDetails['MasterListOfFormat']['title'] ?></dd>
                    <dt><?php echo __('Issue'); ?></dt><dd><?php echo $documentDetails['MasterListOfFormat']['issue_number'] ?></dd>
                    <dt><?php echo __('Revision'); ?></dt><dd><?php echo $documentDetails['MasterListOfFormat']['revision_number'] ?></dd>
                    <dt><?php echo __('Revision Date'); ?></dt><dd><?php echo $documentDetails['MasterListOfFormat']['revision_date'] ?></dd>
                </dl>
            </div>
        </div>
    </div>
    <?php } } ?>
<?php if($controller =='installer' && $this->action == 'index') { ?>
     <div class="panel panel-info">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
                    <?php echo __('Database Configuration'); ?>
                </a>
                <span class="glyphicon glyphicon-wrench pull-right"></span>
            </h4>
        </div>
        <div id="collapseFive" class="panel-collapse collapse">
            <div class="panel-body">
                <p>As any software would, FlinkISO&trade; also stores all its data into a database.</p>
                <p>You will have to enter your database host, username, password along with the name you would like to give to a new database.</p>
                <p>You can leave the prefix blank</p>
                <p>Please note: This installer only supports MySQL database at this point of time.</p>
                <p>Once you enter the correct credentials, installer will create and install new database.</p>
                <p></p>
            </div>
        </div>
    </div>
     <?php } ?>
<?php if($controller =='installer' && $this->action == 'smtp_details') { ?>
     <div class="panel panel-info">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
                    <?php echo __('Why SMTP details are rquired?'); ?>
                </a>
                <span class="glyphicon glyphicon-file pull-right"></span>
            </h4>
        </div>
        <div id="collapseFive" class="panel-collapse collapse">
            <div class="panel-body">
                <p>SMTP stands for Simple Mail Transfer Protocol. It's a set of communication guidelines that allow software to transmit email over the Internet.</p>
                <p>FlinkISO&trade; requires your or your organisation email to send emails and other system required notification, reminders etc to your users who will use FlinkISO&trade; with you.</p>
                <p>Your email, specially email password will not be transmitted over the internet and it is safe to add those details.</p>
                <p></p>
            </div>
        </div>
    </div>
     <?php } ?>
<?php if(($this->action == 'view' or $this->action == 'edit' or $this->action == 'approve' ) && $track_history != NULL){ ?>	   
<div class="panel panel-info">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseXX">
                    <?php echo __('Access history'); ?>
                </a>
                <span class="glyphicon glyphicon-wrench pull-right"></span>
            </h4>
        </div>
        <div id="collapseXX" class="panel-collapse collapse">
            <div class="panel-body">
                <?php foreach($track_history as $h):
						echo "<div class='row'><div class='col-md-12'><small>On ".$h['History']['created']. " " .$h['History']['action'] . " by " . $h['CreatedBy']['name']."</small></div></div>";
					endforeach;
					echo "<br /><div class='row'><div class='col-md-12'>" . $this->Html->link('View Edit/Approve History','#',array('class'=>'btn btn-info','escape'=>false,'id'=>'show_history_button'))  .  "</div></div>";
				?>
				<div id="display_full_history"><?php echo $this->Html->image('indicator.gif', array('id' => 'showModal-indicator-history', 'style' => 'display:none;')); ?></div>				
            </div>
        </div>
    </div>	    
<?php } ?>	
<!-- <div class="panel panel-info hide">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                    <?php echo __("Need Help") ?> ?<span class="glyphicon glyphicon-thumbs-up pull-right"></span>
                </a>
            </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse  in">
            <div class="panel-body">

                    FlinkISO is equipped with On-Page help, a unique Do-It-Yourself guide for users on how to use this application without having to rely on technical team. All the required help for the respective page is available in the section below. This help is specially designed to display only the help which you may need when you are on that particular page.
                <h4 class="text-danger"><?php echo __('Having a problem?'); ?></h4>
                <p><?php echo __('We can help you! Just send your query / issue to the following email address.'); ?><br />
                <h5><a href="mailto:help@flinkiso.com" class="text-info">help@flinkiso.com</a></h5>
                </div>
        </div>
    </div> -->    
<script>
    $(function() {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    });
</script>
<script>
$('#show_history_button').click(function(){
		$("#showModal-indicator-history").show();
		$('#display_full_history').load('<?php echo Router::url('/', true); ?>histories/view/<?php echo $history_record_id ?>');
		$('#history_crs').on('hidden.bs.modal', function (e) {$("#showModal-indicator-history").hide();});

	});</script>
