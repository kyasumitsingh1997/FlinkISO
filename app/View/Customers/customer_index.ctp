<?php echo $this->element('checkbox-script'); ?>
<div  id="customer_main"> <?php echo $this->Session->flash(); ?>
	<div class="customers nav">
		<div class="col-md-12"> 
		<?php echo $this->element('nav-header-lists', array('postData' => array('pluralHumanName' => 'Customers', 'modelClass' => 'Customer', 'options' => array("sr_no" => "Sr No", "name" => "Name", "customer_code" => "Customer Code", "customer_since_date" => "Customer Since Date", "date_of_birth" => "Date Of Birth", "phone" => "Phone", "mobile" => "Mobile", "email" => "Email", "age" => "Age", "residence_address" => "Residence Address", "maritial_status" => "Marital Status"), 'pluralVar' => 'customers'))); ?> 	
			<script type="text/javascript">
            $(document).ready(function() {
                $('table th a, .pag_list li span a').on('click', function() {
                    var url = $(this).attr("href");
                    $('#customer_main').load(url);
                    return false;
                });
            });
        </script>
			<div class=""> <?php echo $this->Form->create(array('class' => 'no-padding no-margin no-background')); ?>
				<table cellpadding="0" cellspacing="0" class="table">
					<?php
                    if ($customers) {
                        $x = 0;
                        foreach ($customers as $customer):
                ?>
					<tr>
						<td class=" actions"><?php echo $this->element('actions', array('created' => $customer['Customer']['created_by'], 'postVal' => $customer['Customer']['id'], 'softDelete' => $customer['Customer']['soft_delete'])); ?></td>
						<td>
						<div class="panel panel-default" style="margin-bottom:1px">
							<div class="panel-body">
							<strong><?php echo h($customer['Customer']['name']); ?>&nbsp;</strong><small><?php //echo $this->Html->link('Edit','#',array('id'=>'edit_'.$customer['Customer']['id'],'class'=>$customer['Customer']['id'])); ?>
							<?php if ($customer['Customer']['publish'] == 1) { ?>
							<span class="fa fa-check text-success pull-right"></span>
							<?php } else { ?>
							<span class="fa fa-ban text-danger pull-right"></span>
							<?php } ?>
							&nbsp;</small>
							<small>
								<?php echo $customer['Customer']['customer_type'] ? 'Individual' : 'Company'; ?>&nbsp;<br />
								<?php echo $customer['Customer']['email']; ?>&nbsp;<br />	
								<?php echo $customer['Customer']['phone']; ?> / <?php echo $customer['Customer']['mobile']; ?>&nbsp;<br />
								Sales Person : <?php echo $customer['Employee']['name']; ?>
							</small>
							
							</div></div>					
							</td>
						<td><div class="btn-group">
								<div class="btn-group"> 
									<?php if($customer['CustomerContacts']['Count'] > 0) $class='success'; else $class = 'danger' ?>
									<?php echo $this->html->link(__('Contacts <span class="badge label-'.$class.'">'.$customer['CustomerContacts']['Count'].'</span>'),'#',array('escape'=>false,'class'=>'btn btn-default dropdown-toggle','data-toggle' => 'dropdown' ,'aria-expanded' => 'false')); ?>
									<ul class="dropdown-menu" role="menu">
										<li><a href="#" id="add_new_contact_<?php echo $customer['Customer']['id']; ?>">Add New Contact</a></li>
										<?php if($customer['CustomerContacts']['Count']>0){ ?>
											<li class="divider"></li>
											<?php foreach ($customer['CustomerContacts'][0] as $customerContact): ?>
												<li>
													<?php echo $this->Html->link(
														($customerContact['CustomerContact']['publish'])? 
															'<span class="glyphicon glyphicon-ok text-success"></span> ' .$customerContact['CustomerContact']['name'] :
															'<span class="glyphicon glyphicon-remove text-danger"></span> ' . $customerContact['CustomerContact']['name'],
														array('controller'=>'customer_contacts','action'=>'edit',$customerContact['CustomerContact']['id']),array('escape'=>false));?> </li>
											<?php endforeach; ?>
										<li class="divider"></li>
										<!-- <li><a href="#">See All</a></li> -->
										<?php } ?>
									</ul>
								</div>
								<div class="btn-group"> 
									<?php if($customer['Proposals']['Count'] > 0) $class='success'; else $class = 'danger' ?>
									<?php echo $this->html->link(__('Proposals <span class="badge label-'.$class.'">'.$customer['Proposals']['Count'].'</span>'),'#',array('escape'=>false,'class'=>'btn btn-default dropdown-toggle','data-toggle' => 'dropdown' ,'aria-expanded' => 'false')); ?>
									<ul class="dropdown-menu" role="menu">
										<li><a href="#" id="add_new_proposal_<?php echo $customer['Customer']['id']; ?>">Add New Proposal</a></li>
										<?php if($customer['Proposals']['Count']>0){ ?>
											<li class="divider"></li>
											<?php foreach ($customer['Proposals'][0] as $proposals): ?>
											<li>
												<?php
												$proposal_status = 0;
												$badge = $status = $approval = ''; 
													if($proposals['Proposal']['publish'] == 0)
														{ 
															$badge = '<small><span class="glyphicon glyphicon-warning-sign text-danger"></span></small>';
															if($proposals['Approval'] == true)$status = 'Unpublished - Under Appoval';
															else $status = 'Unpublished';
															if($proposals['Proposal']['proposal_status'] == 0)$proposal_status = 'Not Sent';$badge = '<small><span class="glyphicon glyphicon-warning-sign text-danger"></span></small>';
															if($proposals['Proposal']['proposal_status'] == 1)$proposal_status = 'Sent';$badge = '<small><span class="glyphicon glyphicon-ok text-success"></span></small>';
															if($proposals['Proposal']['proposal_status'] == 2)$proposal_status = 'Returned';$badge = '<small><span class="glyphicon glyphicon-warning-sign text-danger"></span></small>';
															if($proposals['Proposal']['proposal_status'] == 3)$proposal_status = 'Rejected';$badge = '<small><span class="glyphicon glyphicon-warning-sign text-danger"></span></small>';
															if($proposals['Proposal']['proposal_status'] == 4)$proposal_status = 'Approved By Customer';$badge = '<small><span class="glyphicon glyphicon-ok text-success"></span></small>';
															if($proposals['Proposal']['proposal_status'] == 5)$proposal_status = 'On Hold By Customer';$badge = '<small><span class="glyphicon glyphicon-warning-sign text-danger"></span></small>';
														}else{
															$badge = '<small><span class="glyphicon glyphicon-ok text-success"></span></small>';
															$status = 'Approved ';	
															if($proposals['Proposal']['proposal_status'] == 0):$proposal_status = 'Not Sent';$badge = '<small><span class="glyphicon glyphicon-warning-sign text-danger"></span></small>';endif;
															if($proposals['Proposal']['proposal_status'] == 1):$proposal_status = 'Sent';$badge = '<small><span class="glyphicon glyphicon-ok text-success"></span></small>';endif;
															if($proposals['Proposal']['proposal_status'] == 2):$proposal_status = 'Returned';$badge = '<small><span class="glyphicon glyphicon-warning-sign text-danger"></span></small>';endif;
															if($proposals['Proposal']['proposal_status'] == 3):$proposal_status = 'Rejected';$badge = '<small><span class="glyphicon glyphicon-warning-sign text-danger"></span></small>';endif;
															if($proposals['Proposal']['proposal_status'] == 4):$proposal_status = 'Approved By Customer';$badge = '<small><span class="glyphicon glyphicon-ok text-success"></span></small>';endif;
															if($proposals['Proposal']['proposal_status'] == 5):$proposal_status = 'On Hold By Customer';$badge = '<small><span class="glyphicon glyphicon-warning-sign text-danger"></span></small>';endif;
													}
												 ?>
												<?php echo $this->Html->link($badge . " <strong>" . $proposals['Proposal']['title'] .'</strong><br /><small>'. $status .' - ' . $proposal_status . '</small>',array('controller'=>'proposals','action'=>'view',$proposals['Proposal']['id']),array('escape'=>false));?>
												
											</li>
											<?php endforeach; ?>
											<li class="divider"></li>									
											<li><?php echo $this->Html->link(__('All Proposals'),array('controller'=>'proposals','action'=>'index',$customer['Customer']['id'])); ?></li>
										<?php } ?>	
								</ul>
								</div>
								<div class="btn-group"> 
									<?php $disbaled =''; if($customer['Proposals']['Count'] == 0){ $disbaled = 'disabled'; }?>
									<?php if($customer['ProposalFollowups']['Count'] > 0) $class='success'; else $class = 'danger' ?>
									<?php echo $this->html->link(__('Follow Ups <span class="badge label-'.$class.'">'.$customer['ProposalFollowups']['Count'].'</span>'),'#',array('escape'=>false,'class'=>'btn btn-default dropdown-toggle ' .$disbaled,'data-toggle' => 'dropdown' ,'aria-expanded' => 'false')); ?>
									<ul class="dropdown-menu" role="menu">
										<li><?php echo $this->html->link(__('Add Follow Ups'),array('controller'=>'proposal_followups','action'=>'lists',$customer['Customer']['id']),array('escape'=>false)); ?> </li>
									</ul>
								</div>
								
								<?php $disbaled =''; if($customer['Proposals']['Count'] == 0){ $disbaled = 'disabled'; }?>
									<?php if($customer['Meetings']['Count'] > 0) $class='success'; else $class = 'danger' ?>
									<?php echo $this->html->link(__('Meetings <span class="badge label-'.$class.'">'.$customer['Meetings']['Count'].'</span>'),'#',array('escape'=>false,'class'=>'btn btn-default '. $disbaled)); ?> 

									
								<?php if($customer['Customer']['lead_type'] == 1){ ?>								
								<div class="btn-group hide"> 
									<?php echo $this->html->link(__('Change Type'),'#',array('escape'=>false,'class'=>'btn btn-default dropdown-toggle','data-toggle' => 'dropdown' ,'aria-expanded' => 'false')); ?>							
									<ul class="dropdown-menu" role="menu">
										<li><a href="#">Add to New</a></li>
									</ul>
								</div>
								<?php } ?>
							</div>
							<?php if($customer['CustomerContacts']['Count'] == 0){ ?>
							<div class="alert text-danger" style="padding:4px 10px"><span class="glyphicon glyphicon-warning-sign"></span> &nbsp; Please customer contact details!</div>
							<?php } ?>
							<?php if($customer['Proposals']['Count'] == 0){ ?>
							<div class="alert text-danger" style="padding:4px 10px"><span class="glyphicon glyphicon-warning-sign"></span> &nbsp; Please create & add proposal!</div>
							<?php } ?>
							<?php if($customer['Proposals']['Count'] > 0 && $customer['ProposalFollowups']['Count'] == 0){ ?>
							<div class="alert text-danger" style="padding:4px 10px"><span class="glyphicon glyphicon-warning-sign"></span> &nbsp; Please followup on proposal sent!</div>
							<?php } ?>
						</td>					
					</tr>
<div id="add_new_contact_div"></div>
<div id="add_new_proposal_div"></div>
<script>
$().ready(function(){
		$('#add_new_contact_<?php echo $customer['Customer']['id']; ?>').click(function(){
		$('#add_new_contact_div').load('<?php echo Router::url('/', true); ?>customer_contacts/add_new_contact/<?php echo $customer['Customer']['id']; ?>')
		});
		
		$('#add_new_proposal_<?php echo $customer['Customer']['id']; ?>').click(function(){
		$('#add_new_proposal_div').load('<?php echo Router::url('/', true); ?>proposals/add_ajax/model/customer_id:<?php echo $customer['Customer']['id']; ?>')
		});
	});	
</script>					
					<?php
                    $x++;
                    endforeach;
                    } else {
                ?>
					<tr>
						<td colspan=23>No results found</td>
					</tr>
					<?php } ?>
				</table>
				<?php echo $this->Form->end(); ?> </div>
			<p>
				<?php
                echo $this->Paginator->options(array(
                    'update' => '#customer_main',
                    'evalScripts' => true,
                    'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
                    'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)),
                ));

                echo $this->Paginator->counter(array(
                    'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
                ));
            ?>
			</p>
			<ul class="pagination">
				<?php
                echo "<li class='previous'>" . $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled')) . "</li>";
                echo "<li>" . $this->Paginator->numbers(array('separator' => '')) . "</li>";
                echo "<li class='next'>" . $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled')) . "</li>";
            ?>
			</ul>
		</div>
	</div>
</div>
<?php echo $this->element('export'); ?> <?php echo $this->element('common'); ?> <?php echo $this->element('advanced-search', array('postData' => array("name" => "Name", "customer_code" => "Customer Code", "customer_since_date" => "Customer Since Date", "date_of_birth" => "Date Of Birth", "phone" => "Phone", "mobile" => "Mobile", "email" => "Email", "age" => "Age", "residence_address" => "Residence Address", "maritial_status" => "Marital Status"), 'PublishedBranchList' => array($PublishedBranchList))); ?> <?php echo $this->element('import', array('postData' => array("sr_no" => "Sr No", "name" => "Name", "customer_code" => "Customer Code", "customer_since_date" => "Customer Since Date", "date_of_birth" => "Date Of Birth", "phone" => "Phone", "mobile" => "Mobile", "email" => "Email", "age" => "Age", "residence_address" => "Residence Address", "maritial_status" => "Marital Status"))); ?> <?php echo $this->element('approvals'); ?> <?php echo $this->Js->writeBuffer(); ?> 
<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }});
$.ajaxSetup ({  
    cache: false  
});
			
</script>
