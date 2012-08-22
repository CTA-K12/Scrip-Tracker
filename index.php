<!DOCTYPE>
<html lang="en">

<?php

$dbconn = pg_connect("host=localhost port=5432 dbname=scrip user=postgres ") or die('Could not connect: ' . pg_last_error());
?>


<head>
<meta charset="UTF-8" />
<title>Scrip Tracker</title>


<link rel="stylesheet" href="./assets/css/bootstrap.css" />
<link rel="stylesheet" href="./assets/css/bootstrap-responsive.css" />
<!--<link rel="stylesheet" href="./TableFilter_EN/filtergrid.css" />-->
<link rel="stylesheet" href="style.css" />


<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="./assets/js/bootstrap.js"></script>
<script type="text/javascript" src="http://twitter.github.com/bootstrap/assets/js/bootstrap-typeahead.js"></script>
<script type="text/javascript" src="./TableFilter_EN/actb.js"></script>
<script type="text/javascript" src="./TableFilter_EN/tablefilter.js"></script>


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

<div class="container span6">
 
<table class="table table-striped" id="memberTable">

<thead>

<tr>
<th>#</th>
<th>Name</th>
<th>Balance</th>
</tr>

</thead>

<tbody>

<?php

$query = "SELECT id, (fname || ' ' || lname) FROM golfer ORDER BY id";

$result = pg_query($query) or die('Query failed: '.pg_last_error());

$i = 0;

while($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
	foreach($line as $col_value) {
		if(is_numeric($col_value)) {
			echo "<tr class=\"clickable_row\"><td>$col_value<form type=\"hidden\" action=\"user.php\" method=\"post\">

		<input name=\"golferid\" id=\"golferid\" type=\"hidden\" value=\"$col_value\"> </input> </form></td>";
 
			$i = $col_value;
		}
		else {
			echo "<td>$col_value</td>
			<td>";
			
			$query = "SELECT sum(amt) FROM transaction 
			WHERE type = 'CR' AND golferid = $i";
			
			$credit = pg_fetch_array(pg_query($query));

			$query = "SELECT sum(amt) FROM transaction
			WHERE type = 'DB' AND golferid = $i";

			$debit = pg_fetch_array(pg_query($query));
			
			$balance[0] = $credit[0] - $debit[0];	
			
			$balance[0] = money_format('%i', $balance[0]);
	
			echo "$balance[0]</td></tr>";
		}
	}
}

?>

</tbody>

</table>

<script type="text/javascript">
		$(document).ready(function() {

    		$('tr').click(function() {
        	var form = $(this).find("form");
            		form.submit();
    		});

		});
 </script>

<script type="text/javascript">
	var memberTable_Props = {
		col_0: "none",
		col_2: "none",
		btn: true,
		btn_text: " > "
	}
	setFilterGrid("memberTable", memberTable_Props );
	
</script>

</section>



</body>


</html>
