<style type="text/css">
.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
    border: 1px solid #c1c0c0;
}
.m-div{
  background-color:#dfdfdf;
}
.summary td{
  padding: 5px !important;
  font-weight: 800;
  font-size: 15px;
}
</style>
<h2>File Tracker</h2>
<?php 
$qucipro = $this->requestAction('projects/projectdates/5f19c3b6-3cd0-4392-a1c2-76d4db1e6cf9');
echo $this->element('projectdates',array('qucipro'=>$qucipro));?>
  <div class="row">
    <div class="col-md-12">
      <ul class="timeline">  
      <!-- timeline time label -->
      <li class="time-label">
            <span class="bg-red">
              10 Feb. 2014
            </span>
      </li>
      <!-- /.timeline-label -->
      <!-- timeline item -->
      <li>
        <i class="fa fa-envelope bg-blue"></i>

        <div class="timeline-item">
          <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

          <h3 class="timeline-header"><a href="#">Employee Name </a> sent you an file for review</h3>

          <div class="timeline-body">
            Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
            weebly ning heekya handango imeem plugg dopplr jibjab, movity
            jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
            quora plaxo ideeli hulu weebly balihoo...
          </div>
          <div class="timeline-footer">
            <a class="btn btn-primary btn-xs">Read more</a>
            <a class="btn btn-danger btn-xs">Delete</a>
          </div>
        </div>
      </li>
      <!-- END timeline item -->
      <!-- timeline item -->
      <li>
        <i class="fa fa-user bg-aqua"></i>

        <div class="timeline-item">
          <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>

          <h3 class="timeline-header no-border"><a href="#">QC Reply</a> added on 2020-07-30</h3>
          <div class="timeline-body">
            Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
            weebly ning heekya handango imeem plugg dopplr jibjab, movity
            jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
            quora plaxo ideeli hulu weebly balihoo...
          </div>
        </div>
      </li>
      <li>
        <i class="fa fa-user bg-aqua"></i>
        <div class="timeline-item">
          <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>

          <h3 class="timeline-header no-border"><a href="#">Add New Comment</a></h3>
          <div class="timeline-body">
            <?php echo $this->Form->input('comment',array('type'=>'textarea')); ?>
          </div>
        </div>
      </li>

      <li class="time-label">
            <span class="bg-red">
              10 Feb. 2014
            </span>
      </li>
    </ul>
  </div>
</div>