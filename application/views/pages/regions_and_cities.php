<?php
    if( empty( $regions ) ) {
        // Display the Cities
?>
        <ul class="breadcrumb">
            <li>
                <a href="<?php echo base_url('beer/location') ?>">Countries</a>
                <span class="divider">/</span>
            <?php if (isset($region)): ?>
                <li>
                    <a href="<?php echo base_url('beer/location/' . $country['3166_1_id']) ?>"><?php echo $country['name'] ?></a>
                    <span class="divider">/</span>
                </li>
                <li>
                    <?php echo $region['rgn_name'] ?>
                </li>
            <?php else: ?>
                <li>
                    <?php echo $country['name'] ?>
                </li>
            <?php endif; ?>
        </ul>
        <script type='text/javascript' src='https://www.google.com/jsapi'></script>
        <script type='text/javascript'>
         google.load('visualization', '1', {'packages': ['geochart']});
         google.setOnLoadCallback(drawRegionsMap);

          function drawRegionsMap() {
            var data = google.visualization.arrayToDataTable([
              ['City', 'Breweries'],
            <?php
            foreach( $cities as $city ):
            ?>
                  ['<?php echo addslashes($city['city']) ?>', <?php echo $city['num_brewers'] ?>],
            <?php
            endforeach;
            ?>
            ]);

            var options = {
                region: '<?php if (isset($region)) { echo $region['3166_2_code']; } else { echo $country['alpha_2']; } ?>',
                resolution: '<?php if (isset($region)) { echo 'provinces'; } else { echo 'countries'; } ?>',
                displayMode: 'markers',
                colorAxis: {colors: ['#B1E3AF', '#179E10']},
                sizeAxis: {minSize: 5},
                magnifyingGlass: {enable: true}, 
                legend: 'none'
                };

            var chart = new google.visualization.GeoChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        };
        </script>
        <div id="chart_div" class="span10"></div>
<?php
        foreach( $cities as $city ):
?>
            <div class="span3">
            <?php
                $url = base_url( "beer/location/" . $country[ '3166_1_id' ] . "/" . ( isset( $region ) ? $region[ '3166_2_id' ] : "0" ) . "/" . rawurlencode( $city[ 'city' ] ) );
                echo anchor( $url, $city[ 'city' ] ) . ' (' . $city['num_brewers'] . ')';
            ?>
            </div>
<?php
        endforeach;
    } else {
        // Display the regions
?>
        <script type='text/javascript' src='https://www.google.com/jsapi'></script>
        <script type='text/javascript'>
         google.load('visualization', '1', {'packages': ['geochart']});
         google.setOnLoadCallback(drawRegionsMap);

          function drawRegionsMap() {
            var data = google.visualization.arrayToDataTable([
              ['Region', 'Breweries'],
        <?php
        foreach( $regions as $region ):
        ?>
              ['<?php echo $region['rgn_name'] ?>', <?php echo $region['num_brewers'] ?>],
        <?php
        endforeach;
        ?>
            ]);

            var options = {
                region: '<?php echo $country['alpha_2'] ?>',
                resolution: 'provinces',
                colorAxis: {colors: ['#B1E3AF', '#179E10']},
                magnifyingGlass: {enable: true}, 
                legend: 'none'
                };

            var chart = new google.visualization.GeoChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        };
        </script>
        <ul class="breadcrumb">
            <li>
                <a href="<?php echo base_url('beer/location') ?>">Countries</a>
                <span class="divider">/</span>
            <li>
                <?php echo $country['name'] ?></a>
            </li>
        </ul>
        <div id="chart_div" class="span10"></div>
<?php
        foreach( $regions as $region ):
?>
            <div class="span3">
                <a href="<?php echo base_url( 'beer/location/' . $country['3166_1_id'] . '/' . $region['3166_2_id'] ) ?>"><?php echo $region['rgn_name'] ?></a>
                &nbsp(<?php echo $region['num_brewers'] ?>)
            </div>
<?php
        endforeach;
    }    
?>