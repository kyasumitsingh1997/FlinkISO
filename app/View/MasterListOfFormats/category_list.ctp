<!-- https://bootsnipp.com/snippets/featured/bootstrap-30-treeview -->
<style type="text/css">
.tree-border{
    border-right: 4px double #ccc;
}
.sub_cats, .sub_cats:hover{
    color: #ccc;
}
.tree, .tree ul {
    margin:0;
    padding:0;
    list-style:none
}
.tree ul {
    margin-left:1em;
    position:relative
}
.tree ul ul {
    margin-left:.5em
}
.tree ul:before {
    content:"";
    display:block;
    width:0;
    position:absolute;
    top:0;
    bottom:0;
    left:0;
    border-left:1px dotted #666 !important;
}
.tree li {
    margin:0;
    padding:0 1em;
    line-height:2em;    
    color:#666 !important;
    font-weight:700;
    position:relative
}
.tree ul li:before {
    content:"";
    display:block;
    width:10px;
    height:0;
    border-top:1px dotted #666 !important;
    margin-top:-1px;
    position:absolute;
    top:1em;
    left:0
}
.tree ul li:last-child:before {
    background:#fff;
    height:auto;
    top:1em;
    bottom:0
}
.indicator {
    margin-right:5px;
}
.tree li a {
    text-decoration: none;
    /*color:#369;*/
}
.tree li button, .tree li button:active, .tree li button:focus {
    text-decoration: none;
    /*color:#369;*/
    border:none;
    background:transparent;
    margin:0px 0px 0px 0px;
    padding:0px 0px 0px 0px;
    outline: 0;
}
</style>
<div class="" id="qcdocuments">
  <div  class="col-md-12">
    <h2><?php echo __('Available Quality Documents'); ?></h2>
    <div id='standards' class='btn-group'>
      <?php foreach ($standards as $key => $value) {
        if(isset($standard_id) && $standard_id == $key){
          $class = ' btn-info';
        }else{
          $class = ' btn-default';
        }
        // echo $this->Html->link($value,array('controller'=>'file_uploads','action'=>'quality_documents','standard_id'=>$key),array('class'=>'btn btn-sm' . $class));                
        echo $this->Js->link($value, array('controller'=>'master_list_of_formats','action'=>'category_list','standard_id'=>$key,'jqload'=>0), array(
            'update' => '#qcdocuments',
            'htmlAttributes' => array('class'=>'btn btn-sm ' . $class),            
        ));
        echo $this->Js->writeBuffer();
      }?>
    </div>      

  <div class="panel panel-default no-margin">
    <div class="panel-body">
      <div class="row">
          <div class="col-md-3 tree tree-border"><?php echo $this->requestAction(array('controller'=>'master_list_of_formats','action'=>'show_cats','standard_id'=>$standard_id))?></div>
          <div class="col-md-9"><div id="documents_container"></div></div>
        </div>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
$().ready(function(){
  $(".sub-cats").on('click',function(){
    $("#documents_container").load("<?php echo Router::url('/', true);?>master_list_of_formats/categorywise_files/category_id:"+this.id+"/standard_id:<?php echo $standard_id?>/<?php echo $department_id?>");
  });
$.fn.extend({
    treed: function (o) {
      
      var openedClass = 'glyphicon-minus-sign';
      var closedClass = 'glyphicon-plus-sign';
      
      if (typeof o != 'undefined'){
        if (typeof o.openedClass != 'undefined'){
        openedClass = o.openedClass;
        }
        if (typeof o.closedClass != 'undefined'){
        closedClass = o.closedClass;
        }
      };
      
        //initialize each of the top levels
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ul").each(function () {
            var branch = $(this); //li with children ul
            branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
            branch.addClass('branch');
            branch.on('click', function (e) {
                if (this == e.target) {
                    var icon = $(this).children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children().children().toggle();
                }
            })
            branch.children().children().toggle();
        });
        //fire event from the dynamically added icon
      tree.find('.branch .indicator').each(function(){
        $(this).on('click', function () {
            $(this).closest('li').click();
        });
      });
        //fire event to open branch if the li contains an anchor instead of text
        tree.find('.branch>a').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
        //fire event to open branch if the li contains a button instead of text
        tree.find('.branch>button').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
    }
});

//Initialization of treeviews

$('.tree').treed();

//$('#tree2').treed({openedClass:'glyphicon-folder-open', closedClass:'glyphicon-folder-close'});

//$('#tree3').treed({openedClass:'glyphicon-chevron-right', closedClass:'glyphicon-chevron-down'});

});
</script>
<?php echo $this->Js->writeBuffer();?>