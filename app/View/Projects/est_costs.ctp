
      <h4>Other Estimated Costs
        <?php                         
        echo $this->Html->link('Add New',"#",
            array(
              'class'=>'btn btn-xs btn-default pull-right',
              'onclick'=>'openmodel(
                "project_estimates",
                "add_ajax",
                "'.$projectResource['ProjectResource']['id'].'",
                "'.$this->request->params['pass']['0'].'",
                "'.$milestone['Milestone']['id'].'",
                "'.$activity['ProjectActivity']['id'].'",
                ""
              )'
            )); 
      ?>
      </h4>
          <table class="table table-responsive table-condensed table-bordered draggable">
            <tr class="warning">
              <th>Cost Category</th>
              <th>Cost</th>
              <th>Details</th>  
              <th></th>              
            </tr>
            <?php 
            $subT = 0;
            
            foreach ($projectEstimates as $head) { ?>
              <tr class="warning">                        
                <td><?php echo $head['CostCategory']['name']?></td>
                <td><?php echo $this->Number->currency($head['ProjectEstimate']['cost'],'INR. ')?></td>
                <td><?php echo $head['ProjectEstimate']['details']?></td>
                <td width="90">
                  <div class="btn-group">
                  <?php
                    echo $this->Html->link('Edit',"#",
                        array(
                          'class'=>'btn btn-xs btn-default',
                          'onclick'=>'openmodel(
                            "project_estimates",
                            "edit",
                            "'.$head['ProjectEstimate']['id'].'",
                            "'.$project_id.'",
                            "'.$milestone['Milestone']['id'].'",
                            "'.$activity['ProjectActivity']['id'].'",
                            ""
                          )'
                        )); 
                    // echo $this->Html->link('Edit',array('action'=>'project_','model'=>'ProjectEstimate','id'=>$head['ProjectEstimate']['id']),array('class'=>'btn btn-warning btn-xs'));
                    echo $this->Html->link('Delete',array('action'=>'delete_childrecs',
                      'model'=>'ProjectEstimate',
                      'id'=>$head['ProjectEstimate']['id'],
                      'project_id'=>$project_id
                    ),array('class'=>'btn btn-danger btn-xs'));
                  ?>
                  </div>
                </td>
              </tr>
            <?php 
            $subT = $subT + $head['ProjectEstimate']['cost'];
          } 
          $total = $total + $subT;
          ?>
            <tr class="warning">
              <th colspan="4" class="text-right"><h4>Total : <?php echo $this->Number->currency($subT,'INR. ');?></h4></th>
            </tr>
          </table>
