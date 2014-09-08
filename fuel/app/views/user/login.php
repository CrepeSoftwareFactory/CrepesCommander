<?php echo Form::open(array(
    'name'    => 'login',
)); ?>

<?php echo Form::input(array(
    'id'        => 'login',
    'name'      => 'login',
)); ?>

<?php echo Form::password(array(
    'id'        => 'password',
    'name'      => 'password',
)); ?>

<?php echo Form::submit(array(
    'id'        => 'submit',
    'name'      => 'submit',
    'value'     => 'Connexion',
)); ?>

<?php echo Form::close(); ?>