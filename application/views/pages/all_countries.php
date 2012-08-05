<h1>All Countries</h1>
<script type='text/javascript' src='https://www.google.com/jsapi'></script>
<script type='text/javascript'>
 google.load('visualization', '1', {'packages': ['geochart']});
 google.setOnLoadCallback(drawRegionsMap);

  function drawRegionsMap() {
    var data = google.visualization.arrayToDataTable([
      ['Country', 'Breweries'],
<?php
foreach( $countries as $country ):
?>
      ['<?php echo $country['name'] ?>', <?php echo $country['num_brewers'] ?>],
<?php
endforeach;
?>
    ]);

    var options = {colorAxis: {maxValue: 30, colors: ['#B1E3AF', '#179E10']}, magnifyingGlass: {enable: true}, legend: 'none'};

    var chart = new google.visualization.GeoChart(document.getElementById('chart_div'));
    chart.draw(data, options);
};
</script>
<div id="chart_div" class="span10"></div>
<?php 
foreach( $countries as $country ): 
?>
    <div class="span3">
        <a href="<?php echo base_url( 'beer/location/' . $country['3166_1_id'] ) ?>"><?php echo $country['name'] ?></a>
        &nbsp;(<?php echo $country['num_brewers'] ?>)
    </div>
<?php endforeach; ?>