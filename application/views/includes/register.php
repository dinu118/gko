<div class="row" id="register_widget">
	<div class='col-sm-4'></div>
		<div class="well well-lg col-sm-4">
			<?php 
				echo form_error("sent_fail");
				echo form_error("check_name");
				echo form_error("unsuccessful");
			?>
		    <form id="register_form" method="post" action="<?php echo base_url(); ?>index.php/Main/register_validation">
			    <i class="errors"><?php echo form_error("first_name"); ?></i>
			    <center><input type="text" value="<?php echo set_value('first_name'); ?>" name="first_name" id="first_name" placeholder="First Name" class="form-control" required></input></center>
			    <i class="errors"><?php echo form_error("last_name");?></i>
			    <center><input type="text" value="<?php echo set_value('last_name');?>" name="last_name" id="last_name"
			    placeholder="Last Name" class="form-control" required></input></center>
			    <i class="errors"><?php echo form_error("register_email"); ?></i>
			    <center><input type="email" value="<?php echo set_value('register_email'); ?>" name="register_email" id="register_email" placeholder="Email" class="form-control" required></input></center>
			    <i class="errors"><?php echo form_error("register_password"); ?></i>
			    <center><input type="password" id="register_password" name="register_password" placeholder="Password" class="form-control" required></input></center>
			    <center><input type="password" id="confirm_register_password" name="confirm_register_password" placeholder="Confirm Password" class="form-control" required></input></center>
			    <i class="errors"><?php echo form_error("contact"); ?></i>
			    <center><input type="text" value="<?php echo set_value('contact'); ?>" name="contact" id="contact" placeholder="Contact Number" class="form-control" required></input></center>
			    <center><p><input type = "submit" class ="btn btn-info" value="Register"></p></center>
			</form>
		</div>
	<div class="col-sm-4"></div>
</div>