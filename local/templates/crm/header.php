<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<!DOCTYPE html>
<html lang="ru"> 
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 
        <title>devteam CRM <?$APPLICATION->ShowTitle();?></title>
        <script src="/js/jquery.min.js"></script>
        <script src="/js/bootstrap.min.js"></script> 
        <link href="/css/bootstrap.min.css" rel="stylesheet"> 
        <link href="/fonts/css/font-awesome.min.css" rel="stylesheet">
        <link href="/css/animate.min.css" rel="stylesheet">
 
        <link href="/css/custom.css" rel="stylesheet">
        <script src="/js/custom.js"></script>
  
        <script type="text/javascript" src="/js/select/select2.full.js"></script>
        <link href="/css/select/select2.min.css" rel="stylesheet">
          
        <!-- daterangepicker -->
        <script type="text/javascript" src="/js/moment.min2.js"></script>
        <script type="text/javascript" src="/js/datepicker/daterangepicker.js"></script>
        
        <script type="text/javascript" src="/js/notify/pnotify.core.js"></script> 
  
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <?$APPLICATION->ShowHead(); ?>
    </head>
    <body class="nav-sm"> 
        <div class="container body">
            <div class="main_container"> 
                <div class="col-md-3 left_col">
                    <div class="left_col scroll-view"> 
                        <div class="navbar nav_title" style="border: 0;">
                            <a href="/index.php" class="site_title"><div class="fa fa-paw"></div></a>
                        </div>
                        <div class="clearfix"></div> 
                        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu"> 
                        <?$APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "left",
                                Array(
                                        "ALLOW_MULTI_SELECT" => "N",
                                        "CHILD_MENU_TYPE" => "top",
                                        "COMPONENT_TEMPLATE" => ".default",
                                        "DELAY" => "N",
                                        "MAX_LEVEL" => "2",
                                        "MENU_CACHE_GET_VARS" => array(""),
                                        "MENU_CACHE_TIME" => "360000",
                                        "MENU_CACHE_TYPE" => "N",
                                        "MENU_CACHE_USE_GROUPS" => "Y",
                                        "ROOT_MENU_TYPE" => "left",
                                        "USE_EXT" => "N"
                                )
                        );?> </div> 
                    </div>
                </div> 
                <!-- top navigation -->
                <div class="top_nav"> 
                    <div class="nav_menu">
                        <nav class="" role="navigation">  
                         <ul class="nav navbar-nav navbar-right">
                         <? $APPLICATION->IncludeComponent('devteam:current_user', 'nav'); ?> 
                                <li role="presentation" class="dropdown">
                                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-envelope-o"></i>
                                        <span class="badge bg-green">6</span>
                                    </a>
                                    <ul id="menu1" class="dropdown-menu list-unstyled msg_list animated fadeInDown" role="menu">
                                        <li>
                                            <a>
                                                <span class="image">
                                                    <img src="/images/user.png" alt="Profile Image" />
                                                </span>
                                                <span>
                                                    <span>John Smith</span>
                                                    <span class="time">3 mins ago</span>
                                                </span>
                                                <span class="message">
                                                    Film festivals used to be do-or-die moments for movie makers. They were where... 
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a>
                                                <span class="image">
                                                    <img src="/images/user.png" alt="Profile Image" />
                                                </span>
                                                <span>
                                                    <span>John Smith</span>
                                                    <span class="time">3 mins ago</span>
                                                </span>
                                                <span class="message">
                                                    Film festivals used to be do-or-die moments for movie makers. They were where... 
                                                </span>
                                            </a>
                                        </li> 
                                        <li>
                                            <div class="text-center">
                                                <a> 
                                                    <i class="fa fa-angle-right"></i>
                                                </a>
                                            </div>
                                        </li>
                                    </ul>
                                </li>

                            </ul> 
                                 <div class="nav toggle">
                                <div class="input-group" style="left: 20px; margin-bottom: 0px;">
                                    <input type="text" class="form-control" placeholder="Задача, комментарий, проект...">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button">Найти</button>
                                    </span>
                                </div>
                              </div> 
                        </nav>
                    </div> 
                </div>
                <!-- /top navigation --> 
                <!-- page content -->
                <div class="right_col" role="main"> 
                    <div class=""> 
                        <div class="clearfix"></div>