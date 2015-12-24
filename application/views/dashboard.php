<?php
include "includes/head.php";
include "includes/header.php";
?>
<div class="row">
  <div class="col-sm-11"></div>
  <div class="col-sm-1" style="padding:5px;"><a  href="<?php echo base_url(); ?>index.php/Main/logout"><button class="btn btn-danger" >Logout</button></a></div>
</div>
<div class="row" id="send_sms_row">

  <div class="col-sm-4 well well-lg">
    <form method="post" action="<?php echo base_url(); ?>main/sendMessage">
      <p><input type="number" name="recipent_number" id="recipent_number" placeholder="To" class="form-control"></input></p>
      <p id="recipent_number_errors"></p>
      <p><input type="text" name="message_subject" id="message_subject" placeholder="Subject" class="form-control"></input></p>
      <p id="message_subject_errors"></p>
      <p><textarea name="message_content" rows="5" id="message_content" placeholder="Message" class="form-control"></textarea></p>
      <center><p><input type="submit" value="Send SMS" class="btn btn-success"></input></p></center>
    </form>

  </div>
  <div class="col-sm-4"></div>
  <div class="col-sm-4"></div>
</div>
<?php
include "includes/footer.php"; ?>
