<?php 
$heroConfig = Common::getConfig( 'hero' );
$equipConfig = Common::getConfig( 'equip' );
include 'left.php';
?>

<script>
function submitHero()
{
	var id = $("#selectHero").val();
	if( id > 0 )
	{
		if( confirm( "确定添加要新增一个武将"))
		{	
			$("#heroForm").submit();
		}
	}
	
}


function explodeHero( obj )
{
	if( confirm( "确定要分解此英雄"))
	{	
		window.location.href="Admin.php?act=heros&tag="+obj.id;
	}
}


function delHero( obj )
{
	if( confirm( "确定要删除此英雄"))
	{	
		window.location.href="Admin.php?act=heros&tag="+obj.id;
	}
}



function selectWeapon( obj , id )
{
	var heroUid = id;
	var equipUid = obj.value;

	if( confirm( "确定要换装"))
	{	
		window.location.href="Admin.php?act=heros&heroUid="+heroUid+"&equipUid="+equipUid+"&type=weaponId&oper=equipup";
	}
}


function selectArmor( obj , id )
{
	var heroUid = id;
	var equipUid = obj.value;

	if( confirm( "确定要换装"))
	{	
		window.location.href="Admin.php?act=heros&heroUid="+heroUid+"&equipUid="+equipUid+"&type=armorId&oper=equipup";
	}
}



function selectAcc( obj , id )
{
	var heroUid = id;
	var equipUid = obj.value;

	if( confirm( "确定要换装"))
	{	
		window.location.href="Admin.php?act=heros&heroUid="+heroUid+"&equipUid="+equipUid+"&type=accId&oper=equipup";
	}
}


function changeHeroExp( obj , id )
{
	var heroUid = id;
	if( confirm( "确定此操作?"))
	{	
		window.location.href="Admin.php?act=heros&heroUid="+heroUid+"&exp="+obj.value+"&oper=changeExp";
	}
}


</script>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">
    <i class="icon-home"></i> Home</a> <a href="#" class="current">Tables</a> </div>
    <h1>英雄列表</h1>
  </div>
  <div class="container-fluid">
   
    <div class="row-fluid">
      <div class="span12">
      	
       
        <div class="widget-content nopadding">
          <form action="Admin.php?act=heros"  id="heroForm" method="POST" class="form-horizontal" >
            <div class="control-group">
              <label class="control-label">新增英雄</label>
              <div class="controls">
                <select style="width:700px;" id="selectHero" name="heroId"  onchange="submitHero()" >
                  <option value="0" >选择一个英雄</option>
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
              <label class="control-label"></label>
            </div>
          </form>
        </div>
      
        <div class="widget-box">
          <div class="widget-title"> <span class="icon">  </span>
            <h5>英雄列表</h5>
            <span class="label label-info">Featured</span> </div>
          <div class="widget-content ">
            <table class="table table-bordered table-striped with-check">
              <thead>
                <tr>
                  <th><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" /></th>
                  <th  style="width:60px"><label class="control-label">英雄ID</label></th>
                  <th  style="width:100px" ><label class="control-label">英雄名称</label></th>
                  <th><label class="control-label">经验</label></th>
                  <th><label class="control-label">等级</label></th>
                  <th><label class="control-label">武器</label></th>
                  <th><label class="control-label">防具</label></th>
                  <th><label class="control-label">饰品</label></th>
                  <th style="width:150px"><label class="control-label">操作</label></th>
                </tr>
              </thead>
              <tbody>
              
              	<?php 
              	foreach ( $heroList as  $info )
              	{
              		
              	?>
	                <tr>
	                  <td><input type="checkbox" /></td>
	                  <td><?php echo $info['id'];?></td>
	                  <td><?php echo $heroConfig[$info['heroCid']]['name'];?></td>
	                  <td><input class="span3"  onchange="changeHeroExp(this , <?php echo $info['id'];?>)"  style="width:100px;" type="text" placeholder="<?php echo $info['exp'];?>"></td>
	                  <td><?php echo $info['level'];?></td>
	                 
	                 <!--选择武器-->
	                  <td>
	                  	<select style="width:100px;" id="selectWeapon_<?php echo $info['id'];?>" name="heroId"  onchange="selectWeapon(  this , <?php echo $info['id'];?> )" >
		                  <option value="0" >选择</option>
		                  <?php 
		                  	foreach ( $equipList as $einfo )
		                  	{
		                  		$equipConf =  $equipConfig[$einfo['equipCid']];
		                  		if( $equipConf['type'] == Equip_Model::EQUIP_TYPE_WEAPON )
		                  		{
		                  ?>
		                  <option 
		                  <?php 
		                  	if( $info['weaponId'] == $einfo['id'] )
		                  	{
		                  		echo "selected";
		                  	}
		                  
		                  ?> value="<?php echo $einfo['id'];?>">
		                  	<?php 
		                  		echo $equipConf['name'];
		                  		echo "({$einfo['id']})";
		                  	?>
		                  </option>
		                  <?php 
		                  		}
		                  	}
		                  ?>
		                </select>
	                  </td>
	                 
	                   <!--选择防具-->
	                  <td>
	                  	<select style="width:100px;" id="selectArmor_<?php echo $info['id'];?>" name="heroId"  onchange="selectArmor(  this , <?php echo $info['id'];?> )" >
		                  <option value="0" >选择</option>
		                  <?php 
		                  	foreach ( $equipList as $einfo )
		                  	{
		                  		$equipConf =  $equipConfig[$einfo['equipCid']];
		                  		if( $equipConf['type'] == Equip_Model::EQUIP_TYPE_ARMOR )
		                  		{
		                  ?>
		                  <option 
		                  <?php 
		                  	if( $info['armorId'] == $einfo['id'] )
		                  	{
		                  		echo "selected";
		                  	}
		                  
		                  ?> value="<?php echo $einfo['id'];?>">
		                  	<?php 
		                  		echo $equipConf['name'];
		                  		echo "({$einfo['id']})";
		                  	?>
		                  </option>
		                  <?php 
		                  		}
		                  	}
		                  ?>
		                </select>
	                  </td>
	                  
	                  
	                  <!--选择饰品-->
	                  <td>
	                  	<select style="width:100px;" id="selectAcc_<?php echo $info['id'];?>" name="heroId"  onchange="selectAcc(  this , <?php echo $info['id'];?> )" >
		                  <option value="0" >选择</option>
		                  <?php 
		                  	foreach ( $equipList as $einfo )
		                  	{
		                  		$equipConf =  $equipConfig[$einfo['equipCid']];
		                  		if( $equipConf['type'] == Equip_Model::EQUIP_TYPE_ACC )
		                  		{
		                  ?>
		                  <option 
		                  <?php 
		                  	if( $info['accId'] == $einfo['id'] )
		                  	{
		                  		echo "selected";
		                  	}
		                  
		                  ?> value="<?php echo $einfo['id'];?>">
		                  	<?php 
		                  		echo $equipConf['name'];
		                  		echo "({$einfo['id']})";
		                  	?>
		                  </option>
		                  <?php 
		                  		}
		                  	}
		                  ?>
		                </select>
	                  </td>
	                  
	                 
	                 
	                 
	                  <td>
	                  	<button id="explode_<?php echo $info['id'];?>" class="btn btn-success btn-mini"  onclick="explodeHero(this)"> 分解</button>
	                  	<button id="delhero_<?php echo $info['id'];?>" class="btn btn-success btn-mini" onclick="delHero(this)" > 删除</button>
	                  	
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
