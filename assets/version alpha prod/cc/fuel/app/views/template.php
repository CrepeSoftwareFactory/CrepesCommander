<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo isset($title) ? $title : 'Crepes Commander'; ?></title>

    <!-- Bootstrap core CSS -->
    <?php echo Asset::css('bootstrap.min.css'); ?>
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
  </head>

  <body>

    <div class="container">

    <!-- Static navbar -->
    <div id="menu">
        <ul class="nav nav-pills nav-xs">
            <?php !isset($menu) and $menu = ''; ?>
            <li class="<?php echo ($menu == 'order-add' ? 'active' : ''); ?>"><?php echo Html::anchor('order/add', 'J\'ai faim...'); ?></li>
            <li class="<?php echo ($menu == 'product-order' ? 'active' : ''); ?>"><?php echo Html::anchor('product/order', 'Au boulot !'); ?></li>
            <li class="<?php echo ($menu == 'product-order-affect' ? 'active' : ''); ?>"><?php echo Html::anchor('product/order/affect', 'Commandes en cours'); ?></li>
            <li class="<?php echo ($menu == 'order-finished' ? 'active' : ''); ?>"><?php echo Html::anchor('order/finished', 'Commandes terminées'); ?></li>
            <li class="<?php echo ($menu == 'temp' ? 'active' : ''); ?>"><?php echo Html::anchor('temp', 'Rapport'); ?></li>
        </ul>
    </div>

    </div> <!-- /container -->

    <div><?php echo Session::get_flash('errors'); ?></div>
    <div><?php echo Session::get_flash('success'); ?></div>
    <div><?php echo isset($content) ? $content : ''; ?></div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <?php echo Asset::js('jquery.min.js'); ?>
    <?php echo Asset::js('jquery-ui.min.js'); ?>
    <?php echo Asset::js('bootstrap.min.js'); ?>
    <?php
        if (isset($js)) {
            foreach ($js as $js_file) {
                echo Asset::js($js_file);
            }
        } 
    ?>
  </body>
</html>
