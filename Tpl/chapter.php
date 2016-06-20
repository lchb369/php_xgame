<?php 
//$heroConfig = Common::getConfig( 'hero' );
$chapterConfig = Common::getConfig( 'chapterSection' );

include 'left.php';
?>

<script>
function submitSec()
{
	var id = $("#selectSec").val();
	if( id > 0 )
	{
		if( confirm( "确定要攻打此关"))
		{	
			$("#chapterForm").submit();
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
    <h1>关卡模块</h1>
  </div>
  <div class="container-fluid">
   
    <div class="row-fluid">
      <div class="span12">
      	
        <div class="widget-content nopadding">
          <form action="Admin.php?act=chapter"  id="chapterForm" method="POST" class="form-horizontal" >
            <div class="control-group">
              <label class="control-label">解锁关卡</label>
              <div class="controls">
                <select style="width:700px;" id="selectSec" name="secId"  onchange="submitSec()" >
                  <option value="0" >请选择</option>
                  <?php 
                  	foreach ( $chapterConfig as $chapterConf )
                  	{
                  ?>
                  		<option value="<?php echo $chapterConf['id'];?>"><?php echo $chapterConf['name'];?></option>
                  <?php 
                  	}
                  ?>
                </select>
              </div>
              <label class="control-label"></label>
            </div>
            <input type="hidden" name="pass" value="1" />
          </form>
        </div>
      
        <div class="widget-box">
          <div class="widget-title"> <span class="icon">  </span>
            <h5>通关记录列表</h5>
            <span class="label label-info">Featured</span> </div>
          <div class="widget-content ">
            <table class="table table-bordered table-striped with-check">
              <thead>
                <tr>
                  <th><input type="checkbox" id="title-table-checkbox" name="title-table-checkbox" /></th>
                  <th  style="width:40px"><label class="control-label">关卡小节ID</label></th>
                  <th  style="width:100px" ><label class="control-label">星级</label></th>
                  <th style="width:40px" ><label class="control-label">当日通关次数</label></th>
                  <th style="width:40px" ><label class="control-label">重置次数</label></th>
                  <th style="width:100px" ><label class="control-label">最后通关时间</label></th>
                  <th style="width:150px"><label class="control-label">操作</label></th>
                </tr>
              </thead>
              <tbody>
              
              	<?php 
              	foreach ( $chapterList as  $info )
              	{
              	?>
	                <tr>
	                  <td><input type="checkbox" /></td>
	                  <td><?php echo $info['id'];?></td>
	                  <td><?php echo $info['star'];?></td>
	                  <td><?php echo $info['passedTimes'];?></td>
	                  
	                  <td><?php echo $info['resetTimes'];?></td>
	                  <td><?php echo date( "Y-m-d H:i:s" , $info['lastTime'] );?></td>
	                  <td>
	                  	<button id="wash_<?php echo $info['id'];?>" class="btn btn-success btn-mini" onclick="wash(this)" >洗炼</button>
	                  	<button id="dwash_<?php echo $info['id'];?>" class="btn btn-success btn-mini" onclick="dropWash(this)" >放弃洗炼</button>
	                  	
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
