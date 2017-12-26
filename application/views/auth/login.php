<?php
if ($this->ion_auth->logged_in()) {
    redirect('dashboard', 'refresh');
}

$identity = array(
    'name' => 'identity',
    'id' => 'identity',
    'type' => 'text',
    'value' => '',
    'class' => 'form-control form-control-danger',
    'placeholder' => lang('enter_email'),
    'required' => 'required'
);
$password = array(
    'name' => 'password',
    'id' => 'password',
    'type' => 'password',
    'class' => 'form-control form-control-danger',
    'placeholder' => lang('password'),
    'required' => 'required'
);
?>
<div id="app" class="login-wrapper">
    <div class="login-box  animation flipInX">
        <div class="logo-main">
            <a href="/">
                <img src="<?php echo base_url(); ?>assets/img/<?php echo $this->config->item('logo', 'company'); ?>"
                     alt="Laraspace Logo">
            </a>
        </div>

        <form action="<?php echo site_url('auth/login'); ?>" id="loginForm" method="post">
            <div class="form-group">
                <?php echo form_input($data['email']); ?>
            </div>
            <div class="form-group">
                <?php echo form_input($data['password']); ?>
            </div>

            <?php if (config_item('enable_captcha')): ?>
                <div class="form-group">
                    <?php echo $data['captcha_image']; ?>
                    <?php echo form_input($data['captcha']); ?>
                </div>
            <?php endif; ?>

            <button class="btn btn-theme btn-full"><?php echo lang('login'); ?></button>
            <div class="other-actions" style="text-align:center">

                <?php echo anchor('forgot_password', '<span class="fa fa-key"></span> ' . lang('forgot_password_heading')); ?>

                <?php if(config_item('allow_registration')==TRUE): ?>
                    <br/>
                    <br/>
                <?php echo anchor('register', '<span class="fa fa-user"></span> ' . lang('register')); ?>
                <?php endif; ?>
            </div>
        </form>
        <div class="page-copyright">
            <p><?php echo lang('copyright'); ?></p>
        </div>
    </div>
</div>