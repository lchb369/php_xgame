<?php 
//$heroConfig = Common::getConfig( 'hero' );
$equipConfig = Common::getConfig( 'equip' );
$itemConfig = Common::getConfig( 'items' );
$heroConfig = Common::getConfig( 'hero' );
include 'left.php';
?>

<script>
function submitForm()
{
	if( confirm( "确定要发送礼包"))
	{	
		$("#mailForm").submit();
	}	
}


function delPack( id )
{
	if( confirm( "确定删除礼包"))
	{	
		window.location.href="Admin.php?act=mailPackage&oper=del&id="+id;
	}
}


</script>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">
    <i class="icon-home"></i> Home</a> <a href="#" class="current">Tables</a> </div>
    <h1>邮件列表</h1>
  </div>
  <div class="container-fluid">
   
    <div class="row-fluid">
      <div class="span12">
      	
       
        <div class="widget-content nopadding">
          <form action="Admin.php?act=mail" id="mailForm"   method="POST" class="form-horizontal" >
            <div class="control-group">
            
              <label class="control-label">发送邮件</label>
              <div class="controls">
                <select style="width:300px;"  name="packId"  onchange="submitForm()"  >
                  <option value="0" >请选择</option>
                  <?php 
                  	foreach ( $packList as $info )
                  	{
                  ?>
                  <option value="<?php echo $info['id'];?>"><?php echo $info['packName'];?></option>
                  <?php 
                  	}
                  ?>
                </select>
              </div>
              
             
              <input type="hidden" name="mod" value="selectPack" />
              <label class="control-label"></label>
            </div>
          </form>
        </div>
      
        <div class="widget-box">
          <div class="widget-title"> <span class="icon">  </span>
            <h5>邮件列表</h5>
            <span class="label label-info">Featured</span> </div>
          <div class="widget-content ">
            <table class="table table-bordered table-striped with-check">
              <thead>
                <tr>
                  <th><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" /></th>
                  <th  style="width:40px"><label class="control-label">邮件ID</label></th>
                  <th  style="width:60px" ><label class="control-label">邮件名称</label></th>
                  <th style="width:300px" ><label class="control-label">邮件描述</label></th>
                  <th  ><label class="control-label">邮件道具</label></th>
                  <th   style="width:120px"  ><label class="control-label">发送时间</label></th>
               
                </tr>
              </thead>
              <tbody>
              
              	<?php 
              	foreach ( $mailList as  $info )
              	{
              		
              	?>
	                <tr>
	                  <td><input type="checkbox" /></td>
	                  <td><?php echo $info['id'];?></td>
	                  <td><?php echo $info['title'];?></td>
	                  <td><?php echo $info['content'];?></td>
	                  <td><?php echo $info['items'];?></td>
	                   <td><?php echo date( "Y-m-d H:i:s" , $info['sendTime'] );?></td>
	                 
	                </tr>
              <?php 
              	}
              ?>
              </tbody>
            </table>
          </div>
        </div>
       
      
        
        
      </div>
    </div>
  </div>
</div>
<!--Footer-part-->
<div class="row-fluid">
  <div id="footer" class="span12"> 2013 &copy; Matrix Admin. Brought to you by <a href="http://themedesigner.in/">Themedesigner.in</a> </div>
</div>
<!--end-Footer-part-->
<script src="../Tpl/js/jquery.min.js"></script> 
<script src="../Tpl/js/jquery.ui.custom.js"></script> 
<script src="../Tpl/js/bootstrap.min.js"></script> 
<script src="../Tpl/js/bootstrap-colorpicker.js"></script> 
<script src="../Tpl/js/bootstrap-datepicker.js"></script> 
<script src="../Tpl/js/jquery.toggle.buttons.html"></script> 
<script src="../Tpl/js/masked.js"></script> 
<script src="../Tpl/js/jquery.uniform.js"></script> 
<script src="../Tpl/js/select2.min.js"></script> 
<script src="../Tpl/js/matrix.js"></script> 
<script src="../Tpl/js/matrix.form_common.js"></script> 
<script src="../Tpl/js/wysihtml5-0.3.0.js"></script> 
<script src="../Tpl/js/jquery.peity.min.js"></script> 
<script src="../Tpl/js/bootstrap-wysihtml5.js"></script> 

<script>
	$('.textarea_editor').wysihtml5();
</script>



</body>
</html>
