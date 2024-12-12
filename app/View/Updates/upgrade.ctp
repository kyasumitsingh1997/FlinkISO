<div class="col-md-12" style="font-family: monospace; padding: 20px" id="running-update<?php echo $update_id; ?>">
    
<?php
$i = 0;
$baseFolder = WWW_ROOT . 'files' . DS . 'Updates' . DS . $update_id;


        foreach($updates as $key=>$update):
            if($key == 'folder_path') $folder_path=$update;
            if($key == 'total_file_size') $total_file_size=$update;
            if($key == 'zip'){  ?>
                <div id="up" class="col-md-12" style="font-family: monospace; font-size: 12px; color:#666">
                    <div id="up_progress" class="col-md-12" style="font-family: monospace; font-size: 12px; color:#666">

                    </div>

                    <iframe name="myiframe" id ="myiframe" src="<?php  echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/download_progress/update:<?php echo $update; ?>/baseDir:<?php echo base64_encode($baseFolder); ?>/total_file_size:<?php echo base64_encode($total_file_size).'/time:'.time();  ?>" class="col-md-12" frameborder="0" height="50" ></iframe>

                </div>
                <script>
                    
                    $( document ).ready(function() {
                        function final_download(){
                     
                                $.ajax({
                                        type: 'GET',
                                        url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/copy_zip_file/update:<?php echo $update; ?>/baseDir:<?php echo base64_encode($baseFolder); ?>/folderPath:<?php echo base64_encode($folder_path); ?>",
                                        success: function(data) {

                                                $("#up").html("Download Complete!");
                                        }
                                });
                        }
                        final_download();

                    });


</script>  

                 

        <?php
           
       }
    

    endforeach;
?>

</div>
<?php $vars = base64_encode(json_encode($updates));?>
<script>

    $(document).ajaxStop(function() {
        $("#up").load("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/runsql/upgrade:<?php echo $update_id; ?>/baseDir:<?php echo base64_encode($baseFolder);  ?>");
        $(this).unbind('ajaxStop');
});
</script>