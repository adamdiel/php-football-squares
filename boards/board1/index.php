<?php
include 'classes/football_squares.class.php';
$squares = new football_squares;
$squares->password = 'owenvonbrock!';
$squares->team_one = 'Home Teams';
$squares->team_two = 'Away Teams';
$squares->price = '3.00';
$squares->currency_symbol = '$';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Football Squares</title>
<script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="js/scripts.js"></script>
<link rel='stylesheet' href='https://code.jquery.com/ui/1.10.4/themes/redmond/jquery-ui.css' type='text/css' media='all' />
<link rel='stylesheet' href='css/style.css' type='text/css' media='all' />
</head>

<body>
<div id="wrapper">
<?php echo $squares->build(); ?>
<div class="noPrint">
<h2>Rules</h2>
<ul>
  <li>Fee: 1 square for <?php echo $squares->currency_symbol; ?>3</li>
  <li>The square will be the same for all three thanksgiving games, the scores for each game will change</li>
  <li>Payout is $25 per quarter. 1st Quarter, Half, 3rd Quarter, End of Game (OT counts)</li>
  <li>Numbers picked at random once the board fills up</li>
  <li>Must pay me immediately or I will remove the squares</li>
  <li>Venmo - @Adam-Diel</li>
  <li>Google Pay - adamdiel@gmail.com</li>
  <li>PayPal - adamdiel@gmail.com</li>
</ul>
</div>
</div>

</body>
</html>
