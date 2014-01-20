<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo base_url(); ?>css/bootstrap.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>css/style.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>css/bootstrap-multiselect.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>css/DT_bootstrap.css" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-multiselect.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/DT_bootstrap.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/main.js"></script>
  </head>
  <body>

      <!-- Wrap all page content here -->
    <div id="wrap">

      <!-- Fixed navbar -->
      <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo base_url(); ?>">Stationery Management System</a>
          </div>
          <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
              <li class="<?php echo ( $this->uri->uri_string() == '' )?'active':'' ?>"><a href="<?php echo base_url(); ?>">Home</a></li>
<?php if($admin){ ?>
			  <li class="dropdown<?php echo ( $this->uri->segment(1) == 'admin' )?' active':'' ?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li class="<?php echo ( $this->uri->uri_string() == 'admin/users' )?'active':'' ?>"><a href="<?php echo base_url(); ?>admin/users">Users</a></li>
                  <li class="<?php echo ( $this->uri->uri_string() == 'admin/items' )?'active':'' ?>"><a href="<?php echo base_url(); ?>admin/items">Items</a></li>
                  <li class="<?php echo ( $this->uri->uri_string() == 'admin/vendors' )?'active':'' ?>"><a href="<?php echo base_url(); ?>admin/vendors">Vendors</a></li>
				  <li class="<?php echo ( $this->uri->uri_string() == 'admin/departments' )?'active':'' ?>"><a href="<?php echo base_url(); ?>admin/departments">Departments</a></li>
				  <li class="<?php echo ( $this->uri->uri_string() == 'admin/collection' )?'active':'' ?>"><a href="<?php echo base_url(); ?>admin/collection">Collection</a></li>
				  <li class="<?php echo ( $this->uri->uri_string() == 'admin/report' )?'active':'' ?>"><a href="<?php echo base_url(); ?>admin/report">Report</a></li>
                </ul>
              </li>
<?php } ?>
            </ul>
			<ul class="nav navbar-nav navbar-right">
			  <li><a href="<?php echo base_url(); ?>main/logout">Logout</a></li>
			</ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
      <!-- Begin page content -->
      <div class="container">	  
<?php if($message != ""){ ?>
		<div class="alertbox1">
			<div class="alert alert-success alert-dismissable alertbox2">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<?php echo $message;?>
			</div>
		</div>		
<?php } ?>
<?php if($error != ""){ ?>
		<div class="alertbox1">
			<div class="alert alert-danger alert-dismissable alertbox2">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<?php echo $error;?>
			</div>
		</div>		
<?php } ?>