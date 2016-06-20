<?php 
//$heroConfig = Common::getConfig( 'hero' );
$equipConfig = Common::getConfig( 'equip' );
include 'left.php';
?>

<script>
function submitEquip()
{
	var id = $("#selectEquip").val();
	if( id > 0 )
	{
		if( confirm( "确定添加要新增一件装备"))
		{	
			$("#equipForm").submit();
		}
	}
	
}


function wash( obj )
{
	if( confirm( "确定洗练"))
	{	
		window.location.href="Admin.php?act=equip&oper=wash&tag="+obj.id;
	}
}


function dropWash( obj )
{
	if( confirm( "是否要放弃洗练属性"))
	{	
		window.location.href="Admin.php?act=equip&oper=dwash&tag="+obj.id;
	}
}



function confirmWash( obj )
{
	if( confirm( "是否要替换洗练属性"))
	{	
		window.location.href="Admin.php?act=equip&oper=cwash&tag="+obj.id;
	}
}



function changeEquipLevel( obj , id )
{
	var equipUid = id;
	if( confirm( "确定此操作?"))
	{	
		window.location.href="Admin.php?act=equip&equipUid="+equipUid+"&level="+obj.value+"&oper=changeLevel";
	}
}


</script>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">
    <i class="icon-home"></i> Home</a> <a href="#" class="current">Tables</a> </div>
    <h1>装备列表</h1>
  </div>
  <div class="container-fluid">
   
    <div class="row-fluid">
      <div class="span12">
      	
       
        <div class="widget-content nopadding">
          <form action="Admin.php?act=equip"  id="equipForm" method="POST" class="form-horizontal" >
            <div class="control-group">
              <label class="control-label">添加装备</label>
              <div class="controls">
                <select style="width:700px;" id="selectEquip" name="equipId"  onchange="submitEquip()" >
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
              <label class="control-label"></label>
            </div>
          </form>
        </div>
      
        <div class="widget-box">
          <div class="widget-title"> <span class="icon">  </span>
            <h5>装备列表</h5>
            <span class="label label-info">Featured</span> </div>
          <div class="widget-content ">
            <table class="table table-bordered table-striped with-check">
              <thead>
                <tr>
                  <th><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" /></th>
                  <th  style="width:40px"><label class="control-label">装备ID</label></th>
                  <th  style="width:100px" ><label class="control-label">装备名称</label></th>
                  <th style="width:40px" ><label class="control-label">装备部位</label></th>
                  <th style="width:40px" ><label class="control-label">强化等级</label></th>
                  
                  <th style="width:100px" ><label class="control-label">洗练属性</label></th>
                  <th style="width:100px" ><label class="control-label">新洗练属性</label></th>
                  
                 
                  <th style="width:150px"><label class="control-label">操作</label></th>
                </tr>
              </thead>
              <tbody>
              
              	<?php 
              	foreach ( $equipList as  $info )
              	{
              		
              	?>
	                <tr>
	                  <td><input type="checkbox" /></td>
	                  <td><?php echo $info['id'];?></td>
	                  <td><?php echo $equipConfig[$info['equipCid']]['name'];?></td>
	                  <td>
	                  <?php 
	                  	$etype = $equipConfig[$info['equipCid']]['type'];
	                  	if( $etype == Equip_Model::EQUIP_TYPE_WEAPON  )
	                  	{
	                  		echo "武器";
	                  	}
	                  	elseif( $etype == Equip_Model::EQUIP_TYPE_ARMOR )
	                  	{
	                  		echo "防具";
	                  	}
	                  	else 
	                  	{
	                  		echo "饰品";
	                  	}
	                  
	                  ?>
	                  </td>
	                 
	                  <td><input class="span3"  onchange="changeEquipLevel(this , <?php echo $info['id'];?>)"  style="width:30px;" type="text" placeholder="<?php echo $info['level'];?>"></td>
	                  <td><?php echo json_encode( $info['washAttr'] );?></td>
	                  <td><?php echo json_encode(  $info['willWashAttr'] );?></td>
	                
	                  <td>

	                  	<button id="wash_<?php echo $info['id'];?>" class="btn btn-success btn-mini" onclick="wash(this)" >洗炼</button>
	                  	<button id="dwash_<?php echo $info['id'];?>" class="btn btn-success btn-mini" onclick="dropWash(this)" >放弃洗炼</button>
	                  	<button id="cwash_<?php echo $info['id'];?>" class="btn btn-success btn-mini" onclick="confirmWash(this)" >确定洗炼</button>
	                  	
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
