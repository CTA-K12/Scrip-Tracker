<!DOCTYPE>
<html lang="en">

<?php

$dbconn = pg_connect("host=localhost port=5432 dbname=scrip user=postgres ") or die('Could not connect: ' . pg_last_error());

$id = $_POST["golferid"];

$query = "SELECT * FROM golfer WHERE id = $id";

$result = pg_query($query);

if(!isset($_POST["fname"])) {
	$fname = pg_fetch_result($result, 'fname');
}
else { $fname = $_POST["fname"]; }


if(!isset($_POST["lname"])) {
	$lname = pg_fetch_result($result, 'lname');
}
else { $lname = $_POST["lname"]; }


if(!isset($_POST["bdate"])) {
	$bdate = pg_fetch_result($result, 'bdate');
}
else { $bdate = $_POST["bdate"]; }


if(!isset($_POST["address1"])) {
	$address1 = pg_fetch_result($result, 'address1');
}
else { $address1 = $_POST["address1"]; }


if(!isset($_POST["address2"])) {
	$address2 = pg_fetch_result($result, 'address2');
}
else { $address2 = $_POST["address2"]; }


if(!isset($_POST["phone"])) {
	$phone = pg_fetch_result($result, 'phone');
}
else { $phone = $_POST["phone"]; }


if(!isset($_POST["email"])) {
	$email = pg_fetch_result($result, 'email');
}
else { $email = $_POST["email"]; }


$query = "UPDATE golfer SET fname = '$fname', lname = '$lname', bdate = '$bdate', address1 = '$address1', address2 = '$address2', phone = '$phone', email = '$email' WHERE id = '$id'";

pg_query($query); 

if(isset($_POST["description"])) {
	$golferid = $id;
	$descr = $_POST["description"];
$query = "UPDATE golfer SET fname = '$fname', lname = '$lname', bdate = '$bdate', address1 = '$address1', address2 = '$address2', phone = '$phone', email = '$email' WHERE id = '$id'";

	$type = $_POST["type"];
	$amount = $_POST["amount"];

	$query = "INSERT INTO transaction (golferid, descr, type, amt) VALUES ($id, '$descr', '$type', $amount)";

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
		<h1>Gresham Golf Course</h1>
		</p>
	</div>
</div>

<div class="row">
</div>

<div class="container span4 well">

<h2>Member Info</h2>

<?php

echo "ID:&nbsp $id<br>";

echo "Name:&nbsp $fname $lname<br>";

echo "Birth Date: &nbsp$bdate<br>";

echo "Address Line 1: &nbsp$address1<br>";

echo "Address Line 2: &nbsp$address2<br>";

echo "Phone: &nbsp$phone<br>";

echo "Email: &nbsp$email<br>";

?>
<!--
<dl id="memberInfo">

	<dt>ID: </dt>
		<dd><?php echo "$id"; ?></dd>
	<dt>Name: </dt>
		<dd><?php echo "$fname $lname"; ?></dd>
	<dt>Birth Date: </dt>
		<dd><?php echo "$bdate"; ?></dd>
	<dt>Address Line 1: </dt>
		<dd><?php echo "$address1"; ?></dd>
	<dt>Address Line 2: </dt>
		<dd><?php echo "$address2"; ?></dd>
	<dt>Phone: </dt>
		<dd><?php echo "$phone"; ?></dd>
	<dt>Email: </dt>
		<dd><?php echo "$email"; ?></dd>

</dl>
-->
<div id="memberEdit" class="modal hide fade" style="display:none;">

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">x</button>
	<h3>Edit Member Information</h3>
</div>

<div class="modal-body">

<form class="form-horizontal" method="post" action="user.php">

<div class="control-group">
	<label class="control-label"><h5>ID:&nbsp</h5> </label>
	<span class="input uneditable-input" value="<?php echo $id ?>"><?php echo $id ?></span>
	<input class="hidden" value="<?php echo $id ?>" name="golferid" id="golferid">
</div>

<div class="control-group">
	<label class="control-label"><h5>First Name: &nbsp</h5></label>
	<input class="input search" id="fname" name="fname"  type="search" value="<?php echo $fname ?>">
</div>

<div class="control-group">
	<label class="control-label"><h5>Last Name: &nbsp</h5></label>
	<input class="input search" id="lname" name="lname" type="search" value="<?php echo $lname ?>">
</div>

<div class="control-group">
	<label class="control-label"><h5>Birth Date: &nbsp</h5></label>
	<input class="input search" id="bdate" name="bdate" type="search" value="<?php echo $bdate ?>">
</div>

<div class="control-group">
	<label class="control-label"><h5>Address Line 1: &nbsp</h5></label>
	<input class="input search" id="address1" name="address1" type="search" value="<?php echo $address1 ?>">
</div>

<div class="control-group">
	<label class="control-label"><h5>Address Line 2: &nbsp</h5></label>
	<input class="input search" id="address2" name="address2" type="search" value="<?php echo $address2 ?>">
</div>

<div class="control-group">
	<label class="control-label"><h5>Phone: &nbsp</h5></label>
	<input class="input search" id="phone" name="phone" type="search" value="<?php echo $phone ?>">
</div>

<div class="control-group">
	<label class="control-label"><h5>Email: &nbsp</h5></label>
	<input class="input search" id="email" name="email" type="search" value="<?php echo $email ?>">
</div>

</div>

<div class="modal-footer">
	<form id="golferid">
	<button class="btn btn-primary" type="submit" value="Save Changes">Save Changes</button>	
	<a href="#" class="btn" data-dismiss="modal">Cancel</a>
</div>
</form>
</form>
</div>

<span style="float:right;">
<a href="#memberEdit" class="btn btn-primary" data-toggle="modal">Edit</a>
<span>

</div>

<div class="container span7 well">

<table class="table table-bordered table-striped">

<thead>

<tr>
	<th colspan="5"><h2>Balance:&nbsp<?php echo $balance[0] ?></h2></th>
</tr>

<tr>
	<th colspan="5"><a href="#newTransaction" class="btn btn-large" data-toggle="modal" >New Transaction</a>
<h3 style="float:right;">Click on a row to edit</h3>
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

$query = "SELECT id, date, descr, type, amt FROM transaction WHERE golferid = $id ORDER BY id";

$result = pg_query($query);

$i = 0;

while($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	$i = 0;
	foreach($line as $col_value) {
		if($i == 0) {
		echo "<tr class=\"clickable_row\">
		<td>$col_value</td>";
		
		echo "<form method=\"post\"  action=\"edit.php\">
		<input class=\"hidden\" id=\"transid\" name=\"transid\" value=\"$col_value\">
		<input class=\"hidden\" id=\"golferid\" name=\"golferid\" value=\"$id\">
		"; 
	
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

<div id="newTransaction" class="modal hide fade" style="display:none;">

<form class="form-horizontal" method="post" action="user.php">

<div class="modal-header">

<button type="button" class="close" data-dismiss="modal">x</button>
<h3>New Transaction</h3>

</div>


<div class="modal-body">

<input class="hidden" id="golferid" name="golferid" value="<?php echo $id ?>">


<div class="control-group">
	<label class="control-label"><h5>Description: &nbsp</h5></label>
	<input class="input search" id="description" name="description" type="search" value="">
</div>

<div class="control-group">
	<label class="control-label"><h5>CR: &nbsp</h5></label>
	<input type="radio" name="type" id="type" value="CR">
</div>

<div class="control-group">
	<label class="control-label"><h5>DB: &nbsp</h5></label>
	<input type="radio" name="type" id="type" value="DB">
</div>

<div class="control-group">
	<label class="control-label"><h5>Amount: &nbsp</h5></label>
	<input class="input search" id="amount" name="amount" type="search">
</div>


</div>


<div class="modal-footer">

<div class="modal-footer">
	
	<button class="btn btn-primary" type="submit" value="Save Changes">Save Changes</button>	
	<a href="#" class="btn" data-dismiss="modal">Cancel</a>
</div>
</form>


</div>


</div>



<div id="editTransaction" class="modal hide fade" style="display:none;">

<div class="modal-header">

<button type="button" class="close" data-dismiss="modal">x</button>
<h3>Edit Transaction</h3>

</div>


<div class="modal-body">



</div>


<div class="modal-footer">



</div>


</div>

</div>


</section>

<script type="text/javascript">

$('#memberEdit').modal({show:false});

$(document).ready(function() {

$('tr').click(function() {
	var form = $(this).find("form");
	form.submit();
});

});

</script>

</body>


</html>
