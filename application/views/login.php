<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">

    <title><?php echo $title;?></title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/css/signin.css" rel="stylesheet">
    <!--[if lt IE 9]><script src="../../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
	
	<?php if($message != ""){ ?>
		<div class="alertbox1 col-md-3">
			<div class="alert alert-success alert-dismissable alertbox2">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<?php echo $message;?>
			</div>
		</div>		
	<?php } ?>
	<?php if($error != ""){ ?>
		<div class="alertbox1 col-md-3">
			<div class="alert alert-danger alert-dismissable alertbox2">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<?php echo $error;?>
			</div>
		</div>		
	<?php } ?>
<?php
	echo form_open("main/login",array('class' => 'form-signin'));
?>


        <h2 class="form-signin-heading">Please sign in</h2>
        <input type="text" name="username" id="username" class="form-control" placeholder="Username" required autofocus>
        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
        <label class="checkbox">
          <input type="checkbox" name="remember" id="remember" value="1"> Remember me
        </label>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
<?php echo form_close();?>

    </div> <!-- /container -->

  </body>
</html>
