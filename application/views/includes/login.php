<div class="row" id="login_widget">
	<div class='col-sm-4'></div>
		<div class="well well-lg col-sm-4">
			<form id="login_form" method="post" action="<?php echo base_url(); ?>index.php/Main/login_validation">
			    <i class="errors"><?php echo form_error("login_email"); ?></i>
			    <center><input type="email" value="<?php echo set_value('login_email'); ?>" name="login_email" id="login_email" placeholder="Email" class="form-control" required></input></center>
			    <i class="errors"><?php echo form_error("login_password"); ?></i>
			    <center><input type="password" id="login_password" name="login_password" placeholder="Password" class="form-control" required></input></center>
			    <center><p><input type = "submit" class ="btn btn-info" value="Login"></p></center>
		    </form>
		</div>
	<div class="col-sm-4"></div>
</div>