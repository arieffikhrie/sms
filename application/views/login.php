<h1><?php echo $title;?></h1>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open("main/login");?>

  <p>
    <?php echo form_label('Email','identity');?>
    <?php echo form_input($identity);?>
  </p>

  <p>
    <?php echo form_label('Password', 'password');?>
    <?php echo form_input($password);?>
  </p>

  <p>
    <?php echo form_label('Remember me', 'remember');?>
    <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
  </p>


  <p><?php echo form_submit('submit', 'Submit');?></p>

<?php echo form_close();?>