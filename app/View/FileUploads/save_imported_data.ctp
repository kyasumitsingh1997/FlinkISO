<?php
if(in_array(false,$result,true)){?>
<div class="alert alert-danger">
    <h4>Error ! </h4>
    <p>There is a error uploading some of your records. Please try again.</p>
</div>
<?php }else { ?>
<div class="panel panel-body">
    <h4 class="text-success">Success ! </h4>
    <p>Data is being imported successfully.</p>
</div>

<?php } ?>