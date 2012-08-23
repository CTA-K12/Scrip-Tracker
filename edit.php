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

$query = "UPDATE transaction SET golferid = '$golferid', date = '$date', descr = '$descr', type = '$type', amt = '$amt' WHERE id = '$transid'";

pg_query($query);



if($amt == null) {
	$query = "DELETE FROM transaction WHERE id = '$transid'";

	pg_query($query);
	
}

			$query = "SELECT sum(amt) FROM transaction 
			WHERE type = 'CR' AND golferid = $id";
			
			$credit = pg_fetch_array(pg_query($query));

			$query = "SELECT sum(amt) FROM transaction
			WHERE type = 'DB' AND golferid = $id";

			$debit = pg_fetch_array(pg_query($query));
			
			$balance[0] = $credit[0] - $debit[0];	
			
			$balance[0] = money_format('%i', $balance[0]);




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

<table class="table table-bordered table-striped">

<thead>

<tr>
	<th colspan="5"><h2>Balance:&nbsp<?php echo $balance[0] ?></h2></th>
</tr>

<tr>
	<th colspan="5"><a href="#editTransaction" class="btn btn-large" data-toggle="modal" >Edit Transaction</a>
 </th>

	

</tr>

<tr>

<td>#</td>
<td>Date</td>
<td>Description</td>
<td>Type</td>
<td>Amount</td>

</tr>

</thead>

<tbody>
<?php


$query = "SELECT id, date, descr, type, amt FROM transaction WHERE id = '$transid'";

$result = pg_query($query);



$query = "UPDATE transaction SET golferid='$id', date='$date', descr='$descr', type='$type', amt='$amt' WHERE id='$transid'";

pg_query($query);


$i = 0;

while($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$i = 0;
	foreach($line as $col_value) {
		if($i == 0) {
		echo "<tr class=\"clickable_row\" data-toggle=\"modal\" data-target=\"#editTransaction\">
		<td>$col_value</td>";
	
	
		$i++;
		}
		else {
		echo "<td>$col_value</td>";
	
		$i++;
		}
		echo "</form>";	
	}
	echo "</tr>";
}



?>
</tbody>

</table>

<div>
<form method="post" action ="user.php">
<input type="hidden"  name="golferid" id="golferid" value="<?php echo $id  ?>">
<button class="btn" type="submit">Return to user page</button>

</form>
</div>

<div id="editTransaction" class="modal hide fade" style="display:none;">

<div class="modal-header">

<form class="form-horizontal" method="post" action="complete.php">

<button type="button" class="close" data-dismiss="modal">x</button>
<h3>Edit Transaction</h3>

</div>


<div class="modal-body">

<input class="hidden" id="transid" name="transid" value="<?php echo $transid ?>">

<input class="hidden" id="golferid" name="golferid" value="<?php echo $id ?>">


<div class="control-group">
	<label class="control-label"><h5>Golfer ID: &nbsp</h5></label>
	<input class="input search" id="golferid" name="golferid" value="<?php echo $golferid ?>" type="search">
</div>

<div class="control-group">
	<label class="control-label"><h5>Date: &nbsp</h5></label>
	<input class="input search" id="date" name="date" value="<?php echo $date ?>" type="search">
</div>

<div class="control-group">
	<label class="control-label"><h5>Description: &nbsp</h5></label>
	<input class="input search" id="descr" name="descr" value="<?php echo $descr ?>" type="search">
</div>

<div class="control-group">
	<label class="control-label"><h5>CR: &nbsp</h5></label>
	<input id="type" name="type" value="CR" type="radio" <?php if($type == 'CR') {  echo "checked";} ?>>
</div>

<div class="control-group">
	<label class="control-label"><h5>DB: &nbsp</h5></label>
	<input id="type" name="type" value="DB" type="radio"  <?php if($type == 'DB') {  echo "checked";} ?>>
</div>

<div class="control-group">
	<label class="control-label"><h5>Amount: &nbsp</h5></label>
	<input class="input search" id="amt" name="amt" value="<?php echo $amt ?>" type="search">
</div>



</div>


<div class="modal-footer">
	<span style="float:left">
	<button  class="btn btn-danger" id="deleteTransaction" >DELETE</button>
	</span>
		
	<button class="btn btn-primary" type="submit" value="Save Changes">Save Changes</button>	
	<a href="#" class="btn" data-dismiss="modal">Cancel</a>


</div>
</form>

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
