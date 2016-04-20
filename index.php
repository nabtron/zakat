<?php
//version 0.0.1 
// this one shows the total income, in one single graph, simple
$sqlserver = 'localhost';
$sqlusername = 'root';
$sqlpassword = '';
$sqldatabase = 'zakat1';

$conn = new mysqli( $sqlserver, $sqlusername, $sqlpassword, $sqldatabase );
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$userid = '1';
$type = '1'; //default type - zakatable
$datavar = ''; //making it blank just in case
$datavararray = []; //making it blank just in case
$itemamount = '';
$sql = "SELECT * FROM money WHERE userid='$userid' AND type='$type' ORDER BY datetime ASC";
$result = $conn->query($sql);

$interval = '012015';
$lastinterval = '122016';
while($interval < $lastinterval){
  $interval += 0;
  $datavararray[$interval] = '';
//  echo $interval.' . <br>';
  if( '120000' > $interval){
    $interval += 10000;
  }else{
    $interval -= 99999; // removing 100000 and adding 1
  }
}

if( 0 < $result->num_rows ){
  while($row = $result->fetch_assoc()){
    $itemdate = date('mY', strtotime($row['datetime'])); // j means 1, d means 01 for day
    $itemamountcurrent = $row['amount'];
//    echo $itemamountcurrent.'-';
    $itemamount = $itemamountcurrent; //if we don't want to add amount
    //$itemamount += $itemamountcurrent; // if we want to add amounts

    $itemdate += 0;
    $datavararray[$itemdate] = $itemamount;
//    $datavar .= ',["'.$itemdate.'", '.$itemamount.']';
    //echo $itemdate;
    //print_r($row);

  }
//  print_r($datavararray);

  $valuenew = '';
  foreach($datavararray as $date => $value){
    $valuenew += $value; // adds the values so that the graph keeps adding
    $datavar .= ',["'.$date.'", '.$valuenew.']';
  }

//  $datavar .= ',["'.$datavararray.'", '.$itemamount.']';
  //generating blank spots to make it look even
}
?><!DOCTYPE html>
<html>
<head>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 
	<script type="text/javascript">
	  google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Total']
          <?php echo $datavar; ?>
        ]);
/*
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Source1', 'Source2', 'Source3','source4'],
          ['2013',  0,      400,100,100],
          ['2014',  1170,      0,100,100],
          ['2015',  660,       0,200,400],
          ['2016',  1030,      540,50,200]
        ]);
 */
    data.addColumn({type: 'string', role: 'annotation'});

        var options = {
        annotation: {
            1: {
                // set the style of the domain column annotations to "line"
                style: 'line'
            }
        },
          isStacked: true,
          title: 'Total Income',
          hAxis: {title: 'Year',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
	</script>  
</head>
<body>
   <div id="chart_div" style="width: 900px; height: 300px;"></div>
</body>
<html>
