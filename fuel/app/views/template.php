<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo isset($title) ? $title : 'Crepes Commander'; ?> 2017 !</title>

    <!-- Bootstrap core CSS -->
    <?php echo Asset::css('bootstrap.min.css'); ?>
    <?php echo Asset::css('bootstrap-select.min.css'); ?>
    <?php echo Asset::css('jquery-ui.min.css'); ?>
    <?php echo Asset::css('jquery-ui.structure.min.css'); ?>
    <?php echo Asset::css('jquery-ui.theme.min.css'); ?>
    <?php echo Asset::css('bootstrap.min.css'); ?>
    <?php echo Asset::css('template.css'); ?>
    <?php 
        if (isset($css)) {
            foreach ($css as $css_file) {
                echo Asset::css($css_file);
            }
        }
    ?>
    <script type="text/javascript">
        
        window.oncontextmenu = function(event) {
            event.preventDefault();
            event.stopPropagation();
            return false;
       };
    </script>
  </head>

  <body>

    <div class="container">

        <?php if (Model_User::is_auth()) { ?>
            <!-- Static navbar -->
            <div id="menu">
                <ul class="nav nav-pills nav-xs">
                    <?php !isset($menu) and $menu = ''; ?>
                    <li class="<?php echo ($menu == '' ? 'active' : ''); ?>"><?php echo Html::anchor('', 'Home'); ?></li>
                    <li class="<?php echo ($menu == 'order-add' ? 'active' : ''); ?>"><?php echo Html::anchor('order/add', 'J\'ai faim...'); ?></li>
                    <li class="<?php echo ($menu == 'product-order-list' ? 'active' : ''); ?>"><?php echo Html::anchor('product/order/list', 'Au boulot !'); ?></li>
                    <li class="<?php echo ($menu == 'product-order-affect' ? 'active' : ''); ?>"><?php echo Html::anchor('product/order/affect', 'Admin cmdes'); ?></li>
                    <li class="<?php echo ($menu == 'order-finished' ? 'active' : ''); ?>"><?php echo Html::anchor('order/finished', 'Cmdes terminées'); ?></li>
                    <li class="<?php echo ($menu == 'temp' ? 'active' : ''); ?>"><?php echo Html::anchor('temp', 'Rapport'); ?></li>
                    <li class="<?php echo ($menu == 'admin' ? 'active' : ''); ?>"><?php echo Html::anchor('admin', 'Admin'); ?></li>
                    <li class="<?php echo ($menu == 'misc' ? 'active' : ''); ?>"><?php echo Html::anchor('misc', 'Quitter'); ?></li>
                    <!--<li><?php echo Html::anchor('user/logout', 'Quitter'); ?></li>-->
                    <li><div id="icon_refresh" class="well well-sm icon-autorefresh"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></div></li>
                </ul>
            </div>
        <?php } ?>

        <div class="flash_errors"><?php echo Session::get_flash('errors'); ?></div>
        <div class="flash_success"><?php echo Session::get_flash('success'); ?></div>
        <div><?php echo isset($content) ? $content : ''; ?></div>

    </div> <!-- /container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <?php echo Asset::js('jquery.min.js'); ?>
    <?php echo Asset::js('jquery-ui.min.js'); ?>
    <?php echo Asset::js('jquery.mobile.custom.min.js'); ?>
    <?php echo Asset::js('bootstrap.min.js'); ?>
    <?php echo Asset::js('bootstrap-select.min.js'); ?>
    <?php
        if (isset($js)) {
            foreach ($js as $js_file) {
                echo Asset::js($js_file);
            }
        } 
    ?>
  </body>
</html>
