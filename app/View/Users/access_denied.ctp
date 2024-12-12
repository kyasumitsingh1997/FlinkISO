<div ><?php echo $this->Session->flash(); ?></div>
<div  id="users_ajax">
    <div class="" style="margin-top:5%; text-align:center">
        <div class="container">
                <i class="fa fa-5x fa-exclamation-triangle text-danger" aria-hidden="true"></i>
                <h1><span  class="text-danger"><?php echo __("Access Denied"); ?></span></h1>
                <p><?php echo __("You do not have sufficient permissions to access this page. <br/>Contact your administrator for permissions related issues."); ?></p>
            </div>
        </div>
    </div>    
</div>
