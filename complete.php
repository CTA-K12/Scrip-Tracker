<!DOCTYPE>
<html lang="en">

<?php

$dbconn = pg_connect("host=localhost port=5432 dbname=scrip user=postgres ") or die('Could not connect: ' . pg_last_error());


$id = $_POST["golferid"];
$transid = $_POST["transid"];


$query = "SELECT * FROM transaction WHERE id = $transid";

$result = pg_query($query);

if(!isset($_POST["golferid"])) {
	$golferid = pg_fetch_result($result, 'golferid');
}
else { $golferid = $_POST["golferid"];}

if(!isset($_POST["transid"])) {
	$transid = pg_fetch_result($result, 'id');
}
else { $transid = $_POST["transid"];}

if(!isset($_POST["date"])) {
	$date = pg_fetch_result($result, 'date');
}
else { $date = $_POST["date"]; }

if(!isset($_POST["descr"])) {
	$descr = pg_fetch_result($result, 'descr');
}
else { $descr = $_POST["descr"]; }

if(!isset($_POST["type"])) {
	$type = pg_fetch_result($result, 'type');
}
else { $type = $_POST["type"]; }

if(!isset($_POST["amt"])) {
	$amt = pg_fetch_result($result, 'amt');
}
else { $amt = $_POST["amt"]; }
/*
$query = "UPDATE transaction SET golferid = '$golferid', date = '$date', descr = '$descr', type = '$type', amt = '$amt' WHERE id = '$transid'";

pg_query($query);
*/


if($amt == null) {
	$query = "DELETE FROM transaction WHERE id = '$transid'";

	pg_query($query);
	
}
else {
	$query = "UPDATE transaction SET golferid = '$golferid', date = '$date', descr = '$descr', type = '$type', amt = '$amt' WHERE id = '$transid'";

	pg_query($query);
}


?>


<head>
<meta charset="UTF-8" />
<title>Scrip Tracker</title>


<link rel="stylesheet" href="./assets/css/bootstrap.css" />
<link rel="stylesheet" href="./assets/css/bootstrap-responsive.css" />
<link rel="stylesheet" href="style.css" />


<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="./assets/js/bootstrap.js"></script>
<script type="text/javascript" src="http://twitter.github.com/bootstrap/assets/js/bootstrap-typeahead.js"></script>

<script type="text/javascript">

function DoNav(theUrl)
	{
		document.location.href = theUrl;
	}


</script>

</head>

<body style="padding:40px">

<section id="navbar">
<?php include('nav.php') ?>
</section>

<section id="content">

<div class="row">
	<div class="span12">
		<p>
		<h1>Golf Course</h1>
		</p>
	</div>
</div>

<div class="row">
</div>


<div class="container span7 well">

<h2>Edit Complete</h2>


<div>

<form method="post" action ="user.php">

<input type="hidden"  name="golferid" id="golferid" value="<?php echo $id  ?>">
<button class="btn" type="submit">Return to user page</button>

</form>
</div>


</div>

</div>


</section>

<script type="text/javascript">

$(document).ready(function() {

$("#deleteTransaction").click(function() {

var value = null;
$("#amt").val(value);
form.submit();

});

});

</script>

<script type="text/javascript">

$('#memberEdit').modal({show:false});

$(document).ready(function() {

$('tr').click(function() {
	var form = $(this).find("form");
	form.submit();
});

});

</script>

<script type="text/javascript">

$(window).bind("load", function() {

<?php




?>

});

</script>

</body>


</html>
