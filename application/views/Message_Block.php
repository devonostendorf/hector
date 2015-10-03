<?php
	if ($error = Session::instance()->get_once('error'))
	{
?>
				<div data-alert class="alert-box alert">
					<?php echo $error; ?>
					<a href="#" class="close">&times;</a>
				</div>
<?php
	}
	if ($warning = Session::instance()->get_once('warning'))
	{
?>
				<div data-alert class="alert-box warning">
					<?php echo $warning; ?>
					<a href="#" class="close">&times;</a>
				</div>
<?php
	}
	if ($message = Session::instance()->get_once('message'))
	{
?>
				<div data-alert class="alert-box success">
					<?php echo $message; ?>
					<a href="#" class="close">&times;</a>
				</div>
<?php
	}
?>
