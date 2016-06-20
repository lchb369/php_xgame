<!DOCTYPE html>
<html lang="en">
<head>
<title>Matrix Admin</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="../Tpl/css/bootstrap.min.css" />
<link rel="stylesheet" href="../Tpl/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="../Tpl/css/colorpicker.css" />
<link rel="stylesheet" href="../Tpl/css/datepicker.css" />
<link rel="stylesheet" href="../Tpl/css/uniform.css" />
<link rel="stylesheet" href="../Tpl/css/select2.css" />
<link rel="stylesheet" href="../Tpl/css/matrix-style.css" />
<link rel="stylesheet" href="../Tpl/css/matrix-media.css" />
<link rel="stylesheet" href="../Tpl/css/bootstrap-wysihtml5.css" />
<link href="../Tpl/font-awesome/css/font-awesome.css" rel="stylesheet" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
</head>
<body>

<!--Header-part-->
<div id="header">
  <h1><a href="../Tpl/dashboard.html">Matrix Admin</a></h1>
</div>
<!--close-Header-part--> 

<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
    <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">Welcome User</span><b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a href="#"><i class="icon-user"></i> My Profile</a></li>
        <li class="divider"></li>
        <li><a href="#"><i class="icon-check"></i> My Tasks</a></li>
        <li class="divider"></li>
        <li><a href="login.html"><i class="icon-key"></i> Log Out</a></li>
      </ul>
    </li>
    <li class="dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="icon icon-envelope"></i> <span class="text">Messages</span> <span class="label label-important">5</span> <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a class="sAdd" title="" href="#"><i class="icon-plus"></i> new message</a></li>
        <li class="divider"></li>
        <li><a class="sInbox" title="" href="#"><i class="icon-envelope"></i> inbox</a></li>
        <li class="divider"></li>
        <li><a class="sOutbox" title="" href="#"><i class="icon-arrow-up"></i> outbox</a></li>
        <li class="divider"></li>
        <li><a class="sTrash" title="" href="#"><i class="icon-trash"></i> trash</a></li>
      </ul>
    </li>
    <li class=""><a title="" href="#"><i class="icon icon-cog"></i> <span class="text">Settings</span></a></li>
    <li class=""><a title="" href="login.html"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>
  </ul>
</div>

<!--start-top-serch-->
<div id="search">
  <input type="text" placeholder="Search here..."/>
  <button type="submit" class="tip-bottom" title="Search"><i class="icon-search icon-white"></i></button>
</div>
<!--close-top-serch--> 

<!--sidebar-menu-->

<div id="sidebar"> <a href="#" class="visible-phone"><i class="icon icon-list"></i>Forms</a>
  <ul>
    <li><a href="index.html"><i class="icon icon-home"></i> <span>Dashboard</span></a> </li>
    <li><a href="?act=user"><i class="icon icon-signal"></i> <span>用户信息</span></a> </li>
    <li><a href="?act=heros"><i class="icon icon-inbox"></i> <span>英雄列表</span></a> </li>
    <li><a href="?act=skill"><i class="icon icon-th"></i> <span>被动技能</span></a></li>
    <li><a href="?act=equip"><i class="icon icon-th"></i> <span>装备列表</span></a></li>
    <li><a href="?act=item"><i class="icon icon-th"></i> <span>道具列表</span></a></li>
    <li><a href="?act=chapter"><i class="icon icon-th"></i> <span>关卡系统</span></a></li>
   
    
    <li class="submenu"> <a href="?act=mailPackage"><i class="icon icon-file"></i> <span>邮件礼包</span></a>
      <ul>
        <li><a href="?act=mailPackage">邮件设定</a></li>
        <li><a href="?act=mail">邮件列表</a></li>
      </ul>
    </li>
    
    
    
    <li><a href="http://211.144.68.31/app/projmng/Web/Api.php"><i class="icon icon-th"></i> <span>配置表</span></a></li>
    <li class="submenu"> <a href="#"><i class="icon icon-info-sign"></i> <span>Error</span> <span class="label label-important">4</span></a>
      <ul>
        <li><a href="error403.html">Error 403</a></li>
        <li><a href="error404.html">Error 404</a></li>
        <li><a href="error405.html">Error 405</a></li>
        <li><a href="error500.html">Error 500</a></li>
      </ul>
    </li>
    <li class="content"> <span>Monthly Bandwidth Transfer</span>
      <div class="progress progress-mini progress-danger active progress-striped">
        <div style="width: 77%;" class="bar"></div>
      </div>
      <span class="percent">77%</span>
      <div class="stat">21419.94 / 14000 MB</div>
    </li>
    <li class="content"> <span>Disk Space Usage</span>
      <div class="progress progress-mini active progress-striped">
        <div style="width: 87%;" class="bar"></div>
      </div>
      <span class="percent">87%</span>
      <div class="stat">604.44 / 4000 MB</div>
    </li>
  </ul>
</div>
