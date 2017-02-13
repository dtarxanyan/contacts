<?php use Core\Helpers\Form; ?>

<div class="col-md-12">

    <p>Email: demo@mailinator.com</p>
    <p>Password: 123456</p>

    <form id="loginForm" method="post">
        <div class="form-group">
            <?php echo Form::inputField($model, 'email',
                ['class' => 'form-control input-md'],
                ['class' => 'col-md-3 control-label']
            ); ?>
        </div>
        <div class="form-group">
            <?php echo Form::inputField($model, 'password',
                ['class' => 'form-control input-md', 'type' => 'password'],
                ['class' => 'col-md-3 control-label']
            ); ?>
        </div>

        <?php echo Form::button(['value'=>'Login', 'class' => 'btn btn-default',  'name' => 'submit', 'type' => 'submit']); ?>
    </form>

</div>