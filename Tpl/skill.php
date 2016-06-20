<?php
$skillConfig = Common::getConfig( 'skill' );
$heroConfig = Common::getConfig( 'hero' );
include 'left.php';
?>

<script>
function submitSkill()
{
	var id = $("#selectSkill").val();
	if( id > 0 )
	{
		if( confirm( "确定添加要新增一个技能"))
		{	
			$("#skillForm").submit();
		}
	}
	
}


function selectHero( obj , id )
{
	var skillUid = id;
	var heroUid = obj.value;

	if( confirm( "确定要装备此技能"))
	{	
		window.location.href="Admin.php?act=skill&heroUid="+heroUid+"&skillUid="+skillUid+"&oper=equipup";
	}
}




function delSkill( obj )
{
	if( confirm( "确定要删除此技能"))
	{	
		window.location.href="Admin.php?act=skill&tag="+obj.id;
	}
}

</script>

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom">
    <i class="icon-home"></i> Home</a> <a href="#" class="current">Tables</a> </div>
    <h1>被动技能</h1>
  </div>
  <div class="container-fluid">
   
    <div class="row-fluid">
      <div class="span12">
      	
       
        <div class="widget-content nopadding">
          <form action="Admin.php?act=skill"  id="skillForm" method="POST" class="form-horizontal" >
            <div class="control-group">
              <label class="control-label">新增技能</label>
              <div class="controls">
                <select style="width:700px;" id="selectSkill" name="skillId"  onchange="submitSkill()" >
                  <option value="0" >选择一个被动技能</option>
                  <?php 
                  	foreach ( $skillConfig as $skillInfo )
                  	{
                  		if( $skillInfo['type'] != Hero_Model::SKILL_TYPE_PASSIVE )
                  		{
                  			continue;
                  		}
                  		
                  ?>
                  <option value="<?php echo $skillInfo['id'];?>">
                 	 <?php 
                 	 	echo $skillInfo['level']."级".$skillInfo['name'];
                 	 	echo "({$skillInfo['id']})";
                 	 
                 	 ?>
                  
                  </option>
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
            <h5>被动技能列表</h5>
            <span class="label label-info">Featured</span> </div>
          <div class="widget-content ">
            <table class="table table-bordered table-striped with-check">
              <thead>
                <tr>
                  <th><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" /></th>
                  <th  style="width:60px"><label class="control-label">技能ID</label></th>
                  <th  style="width:250px" ><label class="control-label">技能名称</label></th>
                  <th><label class="control-label">等级</label></th>
                  <th><label class="control-label">装备英雄</label></th>
                  <th style="width:150px"><label class="control-label">操作</label></th>
                </tr>
              </thead>
              <tbody>
              
              	<?php 
              	
           
              	foreach ( $skillList as  $info )
              	{
              		if( $skillConfig[$info['skillCid']]['type'] != Hero_Model::SKILL_TYPE_PASSIVE )
              		{
              			continue;
              		}
              		
              		
              	?>
	                <tr>
	                  <td><input type="checkbox" /></td>
	                  <td><?php echo $info['id'];?></td>
	                  <td><?php echo $skillConfig[$info['skillCid']]['name']."({$skillConfig[$info['skillCid']]['id']})";?></td>
	                  <td><?php echo $skillConfig[$info['skillCid']]['level']."级";?></td>
	                 
	                 
	                  <!--选择防具-->
	                  <td>
	                  	<select style="width:200px;" id="selectHero_<?php echo $info['id'];?>" name="heroId"  onchange="selectHero(  this , <?php echo $info['id'];?> )" >
		                  <option value="0" >选择</option>
		                  <?php 
		                  	foreach ( $heroList as $einfo )
		                  	{
		                  		$heroConf =  $heroConfig[$einfo['heroCid']];
		                  		
		                  		
		                  ?>
		                  <option 
		                  <?php 
		                  	if( $info['heroId'] == $einfo['id'] )
		                  	{
		                  		echo "selected";
		                  	}
		                  
		                  ?> value="<?php echo $einfo['id'];?>">
		                  	<?php 
		                  		echo $heroConf['name'];
		                  		echo "(ID:{$einfo['id']})";
		                  	?>
		                  </option>
		                  <?php 
		                  		
		                  	}
		                  ?>
		                </select>
	                  </td>
	                 
	                 
	                  <td>
	                  
	                  	<button id="delskill_<?php echo $info['id'];?>" class="btn btn-success btn-mini" onclick="delSkill(this)" > 删除</button>
	                  	
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
