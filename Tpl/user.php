<?php 
include 'left.php';
?>
<!--close-left-menu-stats-sidebar-->

<div id="content">
<div id="content-header">
  <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="tip-bottom">Form elements</a> <a href="#" class="current">Common elements</a> </div>
  <h1>玩家基本信息</h1>
</div>
<div class="container-fluid">
  <hr>
  <div class="row-fluid">
    <div class="span6">
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>玩家游戏信息</h5>
        </div>
        <div class="widget-content nopadding">
          <form  class="form-horizontal" action="Admin.php?method=Index.user"  method="POST"  >
            <div class="control-group">
              <label class="control-label">玩家ID(uid):</label>
              <div class="controls">
                <input type="text" class="span11"  name="uid" value="<?php echo $uid;?>" placeholder=<?php echo $uid;?> />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">昵称(nickname):</label>
              <div class="controls">
                <input type="text" class="span11"  name="nickname"  placeholder=<?php echo $userInfo['nickname'];?> />
              </div>
            </div>
            
             <div class="control-group">
              <label class="control-label">性别(sex):</label>
              <div class="controls">
                <input type="text" class="span11"  name="sex" placeholder=<?php echo $userInfo['sex'] == 1 ? "男" : "女";?> />
              </div>
            </div>
            
            <div class="control-group">
              <label class="control-label">经验(exp):</label>
              <div class="controls">
                <input type="text"  class="span11" name="exp" placeholder="<?php echo $userInfo['exp'];?>"  />
              </div>
            </div>
            
            <div class="control-group">
              <label class="control-label">等级(level):</label>
              <div class="controls">
                <input type="text" class="span11" name="level" placeholder=<?php echo $userInfo['level'];?> />
              </div>
            </div>
           
           <div class="control-group">
              <label class="control-label">VIP等级(vip):</label>
              <div class="controls">
                <input type="text" class="span11" name="vip" placeholder=<?php echo $userInfo['vip'];?> />
              </div>
            </div>
           
           
           
           <div class="control-group">
              <label class="control-label">金币(gold):</label>
              <div class="controls">
                <input type="text" class="span11" name="gold" placeholder=<?php echo $userInfo['gold'];?> />
              </div>
            </div>
           
           
            <div class="control-group">
              <label class="control-label">元宝(coin):</label>
              <div class="controls">
                <input type="text" class="span11" name="coin" placeholder=<?php echo $userInfo['coin'];?> />
              </div>
            </div>
           
           <div class="control-group">
              <label class="control-label">精力(vigor):</label>
              <div class="controls">
                <input type="text" class="span11" name="vigor" placeholder=<?php echo $userInfo['vigor'];?> />
              </div>
            </div>
           
           
            <div class="control-group">
              <label class="control-label">体力(strength):</label>
              <div class="controls">
                <input type="text" class="span11" name="strength" placeholder=<?php echo $userInfo['strength'];?>  />
              </div>
            </div>
           
            <div class="control-group">
              <label class="control-label">英雄碎片(chips):</label>
              <div class="controls">
                <input type="text" class="span11" name="chips" placeholder=<?php echo $userInfo['chips'];?>  />
              </div>
            </div>
           
           
            <div class="control-group">
              <label class="control-label">最大通关ID:</label>
              <div class="controls">
                <input type="text"  name="maxSecId"  class="span11" placeholder=<?php echo $userInfo['maxSecId'];?> />
              </div>
            </div>
           
           <div class="control-group">
              <label class="control-label">战斗小队:</label>
              <div class="controls">
                <input type="text" class="span11" name="heroTeam" placeholder=<?php echo $userInfo['heroTeam'];?> />
              </div>
            </div>
           	
            <div class="form-actions">
              <button type="submit" class="btn btn-success">Save</button>
            </div>
          </form>
        </div>
      </div>
      
	
    </div>
    
    <div class="span6">
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
          <h5>注册登录信息</h5>
        </div>
        <div class="widget-content nopadding">
          <form action="Admin.php?method=Index.user" method="get" class="form-horizontal">
            <div class="control-group">
              <label for="normal" class="control-label">创建时间</label>
              <div class="controls">
                <?php echo date( "Y-m-d H:i:s" , $userProfile['registerTime'] );?>
              </div>
            </div>
            <div class="control-group">
              <label for="normal" class="control-label">最后登录时间</label>
              <div class="controls">
                <?php echo date( "Y-m-d H:i:s" , $userProfile['loginTime'] );?>
                </div>
            </div>
            <div class="control-group">
              <label for="normal" class="control-label">连续登录天数</label>
              <div class="controls">
               <?php echo $userProfile['keepLoginDays'];?>
                </div>
            </div>
            <div class="control-group">
              <label for="normal" class="control-label">累记登录天数</label>
              <div class="controls">
                <?php echo $userProfile['totalLoginDays'];?>
                </div>
            </div>
            <div class="control-group">
              <label for="normal" class="control-label">今日登录次数</label>
              <div class="controls">
               <?php echo $userProfile['todayLoginTimes'];?>
               </div>
            </div>
            <div class="control-group">
              <label for="normal" class="control-label">登录平台</label>
              <div class="controls">
               
               </div>
            </div>
            <div class="control-group">
              <label for="normal" class="control-label">下载渠道</label>
              <div class="controls">
               
                </div>
            </div>
            <div class="control-group">
              <label for="normal" class="control-label">Percent</label>
              <div class="controls">
               
                </div>
            </div>
          </form>
        </div>
      </div>
      
    
  </div>
 
  
  
</div></div>
<!--Footer-part-->
<div class="row-fluid">
  <div id="footer" class="span12">ProjectL GM工具<a href="http://themedesigner.in/">Themedesigner.in</a> </div>
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
