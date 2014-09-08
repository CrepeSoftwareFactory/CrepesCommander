<?php echo Form::open(array(
           'action'     => '',
           'name'       => 'product',
           'method'     => 'POST',
    )); ?>

<?php echo Form::select(array(
        'id'        => 'type',
        'name'      => 'type',
        'options'   => $types,
)); ?>

<?php echo Form::label('Nom : ', 'name'); ?>
<?php echo Form::input(array(
        'id'        => 'name',
        'name'      => 'name',
        'value'     => Input::post('name'),
)); ?>

<?php echo Form::label('Code : ', 'code'); ?>
<?php echo Form::input(array(
        'id'        => 'code',
        'name'      => 'code',
        'value'     => Input::post('code'),
)); ?>

<?php echo Form::label('Description : ', 'description'); ?>
<?php echo Form::textarea(array(
        'id'        => 'description',
        'name'      => 'description',
        'value'     => Input::post('description'),
)); ?>

<?php echo Form::label('Prix : ', 'price'); ?>
<?php echo Form::input(array(
        'id'        => 'price',
        'name'      => 'price',
        'value'     => Input::post('price'),
)); ?>

<?php echo Form::label('Préparation : ', 'time'); ?>
<?php echo Form::input(array(
        'id'        => 'time',
        'name'      => 'time',
        'value'     => Input::post('time'),
)); ?>

<?php echo Form::submit(array(
        'id'        => 'submit',
        'name'      => 'submit',
        'value'     => 'Valider',
)); ?>

<?php echo Form::close(); ?>