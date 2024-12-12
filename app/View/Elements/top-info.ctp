<div class="col-md-4">
  <ul class="list-group">
        <li class="list-group-item">
          <?php
                $capaReceived = '<span class="default badge pull-right btn-warning">' . $capaReceived . '</span>' . __('Number of CAPAs');
                echo $this->Form->postLink($capaReceived, array('controller' => 'corrective_preventive_actions', 'action' => 'index'), array('escape' => false));
              ?>
        </li>
        <li class="list-group-item">
          <?php
                $openCapa = '<span class="default badge pull-right btn-warning">' . $openCapa . '</span>' . __('Open CAPAS');
                echo $this->Form->postLink($openCapa, array('controller' => 'corrective_preventive_actions', 'action' => 'capa_status'), array('escape' => false));
              ?>
        </li>
        <li class="list-group-item">
          <?php
                $closeCapa = '<span class="default badge pull-right btn-success">' . $closeCapa . '</span>' . __('CAPAS Closed');
                echo $this->Form->postLink($closeCapa, array('controller' => 'corrective_preventive_actions', 'action' => 'capa_status', 1), array('escape' => false));
              ?>
        </li>
        <li class="list-group-item">
          <?php
                $docChangeReq = '<span class="default badge pull-right btn-success">' . $docChangeReq . '</span>' . __('Number of document change requests');
                echo $this->Form->postLink($docChangeReq, array('controller' => 'change_addition_deletion_requests', 'action' => 'index'), array('escape' => false));
              ?>
        </li>
      </ul>

</div>
<div class="col-md-4">
  <ul class="list-group">
        <li class="list-group-item">
          <?php
                $receivedNcs = '<span class="default badge pull-right btn-danger">' . $countNCs . '</span>' . __('Number of NCs found');
                echo $this->Form->postLink($receivedNcs, array('controller' => 'non_conforming_products_materials', 'action' => 'get_ncs'), array('escape' => false));
              ?>
        </li>
        <li class="list-group-item">
          <?php
                $openNcs = '<span class="default badge pull-right btn-danger">' . $countNCsOpen . '</span>' . __('Number of NCs open');
                echo $this->Form->postLink($openNcs, array('controller' => 'non_conforming_products_materials', 'action' => 'get_ncs', 0), array('escape' => false));
              ?>
        </li>
        <li class="list-group-item">
          <?php
                $receivedCc = '<span class="default badge pull-right btn-danger">' . $complaintReceived . '</span>' . __('Number of Customer Complaints recieved');
                echo $this->Form->postLink($receivedCc, array('controller' => 'customer_complaints', 'action' => 'index'), array('escape' => false));
              ?>
        </li>
        <li class="list-group-item">
          <?php
                $openCc = '<span class="default badge pull-right btn-danger">' . $complaintOpen . '</span>' . __('Number of Customer Complaints open');
                echo $this->Form->postLink($openCc, array('controller' => 'customer_complaints', 'action' => 'customer_complaint_status'), array('escape' => false));
              ?>
        </li>
      </ul>
</div>