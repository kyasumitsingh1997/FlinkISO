<?php if ($this->Session->read('User.is_mr') != false) { ?>
<div class="row">
  <div class="col-md-8">
    <div class="panel panel-default" id="department_guage"> 
      <script>$('#department_guage').load('<?php echo Router::url('/', true); ?>histories/department_guage')</script> 
      <?php echo $this->element('department_gauge'); ?> </div>
  </div>
  <div class="col-md-4">
    <div class="panel panel-default" id="branch_guage"> 
      <script>$('#branch_guage').load('<?php echo Router::url('/', true); ?>histories/branch_guage')</script> 
      <?php echo $this->element('branch_gauge'); ?> </div>
  </div>
</div>
<?php } ?>
<script>
  $("[name*='date']").datepicker({
    changeMonth: true,
    changeYear: true,
    format: 'yyyy-mm-dd',
      autoclose:true,
  });
</script>
