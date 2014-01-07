<html>
<body>
	<p>RO <?php echo $roID;?> has been <?php echo $status; ?> by <?php echo $name; ?>. Please click on the link below</p>
	<?php echo anchor(base_url().'main/view_request/'.$roID, base_url().'main/view_request/'.$roID); ?>
</body>
</html>