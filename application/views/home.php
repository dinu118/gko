<?php
include "includes/head.php";
include "includes/header.php";

?>

<?php
if(isset($_GET['success'])&&empty($_GET['success']))
	include "includes/register_success.php";
if(isset($_GET['active'])&&empty($_GET['active']))
	include "includes/activate.php";
if(isset($_GET['error'])&&empty($_GET['error']))
	include "includes/error.php";

include "includes/login.php";

//include "includes/register.php";


?>
<?php
include "includes/footer.php";
 ?>
