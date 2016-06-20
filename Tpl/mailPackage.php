<?php 
//$heroConfig = Common::getConfig( 'hero' );
$equipConfig = Common::getConfig( 'equip' );
$itemConfig = Common::getConfig( 'items' );
$heroConfig = Common::getConfig( 'hero' );
include 'left.php';
?>

<script>
function submitForm( obj )
{
	if( confirm( "确定要生成礼包"))
	{	
		obj.submit();
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
    <h1>邮件设定</h1>
  </div>
  <div class="container-fluid">
   
    <div class="row-fluid">
      <div class="span12">
      	
       
        <div class="widget-content nopadding">
          <form action="Admin.php?act=mailPackage"   method="POST" class="form-horizontal" >
            <div class="control-group">
            
              <label class="control-label">选择道具</label>
              <div class="controls">
                <select style="width:300px;"  name="itemId"   >
                  <option value="0" >请选择</option>
                  <?php 
                  	foreach ( $itemConfig as $itemInfo )
                  	{
                  ?>
                  <option value="<?php echo $itemInfo['id'];?>"><?php echo $itemInfo['name'];?></option>
                  <?php 
                  	}
                  ?>
                </select>
              </div>
              
              <label class="control-label">道具数量</label>
	              <div class="controls">
	              <input class="span3"  name="itemNum"   onchange=""  style="width:300px;" type="text" placeholder="" >
              </div>
              
              
              <label class="control-label">选择装备</label>
              <div class="controls">
                <select style="width:300px;" id="selectEquip" name="equipId"   >
                  <option value="0" >请选择</option>
                  <?php 
                  	foreach ( $equipConfig as $equipInfo )
                  	{
                  ?>
                  <option value="<?php echo $equipInfo['id'];?>"><?php echo $equipInfo['name'];?></option>
                  <?php 
                  	}
                  ?>
                </select>
              </div>
              
              
              <label class="control-label">选择武将</label>
              <div class="controls">
                <select style="width:300px;" id="selectHero" name="heroId"  >
                  <option value="0" >请选择</option>
                  <?php 
                  	foreach ( $heroConfig as $heroInfo )
                  	{
                  ?>
                  <option value="<?php echo $heroInfo['id'];?>"><?php echo $heroInfo['name'];?></option>
                  <?php 
                  	}
                  ?>
                 
                </select>
              </div>
              
              
              
              <label class="control-label">金币数量</label>
	              <div class="controls">
	              <input class="span3"  name="gold" onchange=""  style="width:300px;" type="text" placeholder="" >
              </div>
              
              
              <label class="control-label">元宝数量</label>
	              <div class="controls">
	              <input class="span3"  name="coin" onchange=""  style="width:300px;" type="text" placeholder="" >
              </div>
              
               <label class="control-label">邮件名称</label>
	              <div class="controls">
	              <input class="span3"  name="packName" onchange=""  style="width:300px;" type="text" placeholder="" >
              </div>
              
              
              <label class="control-label">邮件说明</label>
	              <div class="controls">
	              <input class="span3"  name="packDesc" onchange=""  style="width:800px;" type="text" placeholder="" >
              </div>
              
              
               <label class="control-label"></label>
	           <div class="controls">
	             <button  class="btn btn-success" onclick="submitForm(this)" >将以上设定道具生成邮件模板</button>
               </div>
              
              <input type="hidden" name="mod" value="addPack" />
              <label class="control-label"></label>
            </div>
          </form>
        </div>
      
        <div class="widget-box">
          <div class="widget-title"> <span class="icon">  </span>
            <h5>邮件模板</h5>
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
                  <th   style="width:120px"  ><label class="control-label">添加时间</label></th>
               
                 
                  <th style="width:40px"><label class="control-label">操作</label></th>
                </tr>
              </thead>
              <tbody>
              
              	<?php 
              	foreach ( $packList as  $info )
              	{
              		
              	?>
	                <tr>
	                  <td><input type="checkbox" /></td>
	                  <td><?php echo $info['id'];?></td>
	                  <td><?php echo $info['packName'];?></td>
	                  <td><?php echo $info['packDesc'];?></td>
	                  <td><?php echo $info['packInfo'];?></td>
	                   <td><?php echo date( "Y-m-d H:i:s" , $info['addTime'] );?></td>
	                  <td>

	                  	<button  class="btn btn-success btn-mini" onclick="delPack(<?php echo $info['id'];?>)" >删除</button>
	                 
	                  	
	                  </td>
	                 
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
