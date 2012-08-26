<?php if( $mode == 'world' ) { ?>
<div class="page-header">
    <h1>All Countries</h1>
</div>
<?php } else if( $mode == 'continent' ) { ?>
    <ul class="breadcrumb">
        <li>
            <?php echo anchor( base_url( 'beer/location/' ), 'Countries' ); ?>
        </li>
        <span class="divider">/</span>
        <li>
            <?php echo $continents[ 0 ][ 'name' ] ?>
        </li>
    </ul>
<?php } else if( $mode == 'subcontinent' ) { ?>
    <ul class="breadcrumb">
        <li>
            <?php echo anchor( base_url( 'beer/location/' ), 'Countries' ); ?>
        </li>
        <span class="divider">/</span>
        <li>
            <?php echo anchor( base_url( 'beer/location/' . $continents[ 0 ][ 'continent_id' ] . '/' ), $continents[ 0 ][ 'name' ] ); ?>
        </li>
        <span class="divider">/</span>
        <li>
            <?php echo $subcontinents[ 0 ][ 'name' ] ?>
        </li>
    </ul>
<?php } ?>

<script type='text/javascript' src='https://www.google.com/jsapi'></script>
<script type='text/javascript'>
 google.load('visualization', '1', {'packages': ['geochart']});
 google.setOnLoadCallback(drawRegionsMap);

  function drawRegionsMap() {
    var data = google.visualization.arrayToDataTable([
      ['Country', 'Breweries'],
<?php
foreach( $countries as $country ):
    if( ( $mode == 'world' )
     || ( $mode == 'continent' and $country[ 'continent_id' ] == $continents[ 0 ][ 'continent_id' ] )
     || ( $mode == 'subcontinent' and $country[ 'subcontinent_id' ] == $subcontinents[ 0 ][ 'subcontinent_id' ] ) ) {
        echo '[\'' . $country[ 'name' ] . '\', ' . $country[ 'num_brewers' ] . '],';
    }
endforeach;
?>
    ]);

    var options = {
        <?php if( $mode == 'world' ) { ?>
            region: 'world',
            resolution: 'countries',
            colorAxis: { maxValue: 30, colors: ['#B1E3AF', '#179E10'] },
            magnifyingGlass: { enable: true },
            legend: 'none'
        <?php } else if( $mode == 'continent' ) { ?>
            region: '<?php echo $continents[ 0 ][ 'un_m49_code' ]; ?>',
            resolution: 'countries',
            colorAxis: { maxValue: 30, colors: ['#B1E3AF', '#179E10'] },
            magnifyingGlass: { enable: true },
            legend: 'none'
        <?php } else if( $mode == 'subcontinent' ) { ?>
            region: '<?php echo $subcontinents[ 0 ][ 'un_m49_code' ]; ?>',
            resolution: 'countries',
            colorAxis: { maxValue: 30, colors: ['#B1E3AF', '#179E10'] },
            magnifyingGlass: { enable: true },
            legend: 'none'
        <?php } ?>
    };

    var chart = new google.visualization.GeoChart(document.getElementById('chart_div'));
    chart.draw(data, options);

    var handleClick = function() {
        // Mapping of data entries to urls
        var url_map = [
            <?php
            foreach( $countries as $country ) {
                if( ( $mode == 'world' )
                 || ( $mode == 'continent' and $country[ 'continent_id' ] == $continents[ 0 ][ 'continent_id' ] )
                 || ( $mode == 'subcontinent' and $country[ 'subcontinent_id' ] == $subcontinents[ 0 ][ 'subcontinent_id' ] ) ) {
                    $country_url = base_url( 'beer/location/' . $country[ 'continent_id' ] . '/' . $country[ 'subcontinent_id' ] . '/' . $country[ '3166_1_id' ] . '/' );
                    echo '\'' . $country_url . '\',' . "\n";
                }
            }
            ?>
        ];

        item = chart.getSelection();
        window.location.href = url_map[ item[0].row ];
    }

    google.visualization.events.addListener( chart, 'select', handleClick );
};
</script>
<div class="row">
    <div id="chart_div" class="span12"></div>
</div>
<div class="row">
<?php

switch( $mode ) {
    case 'world':
        // sort each of the countries by their continents
        foreach( $countries as $country ) {
            $continentMap[ $country[ 'continent_id' ] ][] = $country;
        }

        // output each country under their continent groupings
        foreach( $continents as $continent ) {
            echo '<a name="' . $continent[ 'name' ] . '"></a>';
            echo '<div class="span12"><h2>' . anchor( base_url( 'beer/location/' . $continent[ 'continent_id' ] . '/' ), $continent[ 'name' ] ) . '</h2></div>';
            foreach( $continentMap[ $continent[ 'continent_id' ] ] as $country ) {
                echo '<div class="span3">';
                echo anchor( base_url( 'beer/location/' . $continent[ 'continent_id' ] . '/' . $country[ 'subcontinent_id' ] . '/' . $country[ '3166_1_id' ] . '/' ), $country['name'] );
                echo '  &nbsp;(' . $country['num_brewers'] . ')';
                echo '</div>';
            }
        }
        break;
    case 'continent':
        // sort each of the countries by their subcontinents
        foreach( $countries as $country ) {
            $subcontinentMap[ $country[ 'subcontinent_id' ] ][] = $country;
        }

        // output each country under their subcontinent groupings
        foreach( $subcontinents as $subcontinent ) {
            echo '<a name="' . $subcontinent[ 'name' ] . '"></a>';
            echo '<div class="span12"><h2>' . anchor( base_url( 'beer/location/' . $continents[ 0 ][ 'continent_id' ] . '/' . $subcontinent[ 'subcontinent_id' ] . '/' ), $subcontinent[ 'name' ] ) . '</h2></div>';
            foreach( $subcontinentMap[ $subcontinent[ 'subcontinent_id' ] ] as $country ) {
                echo '<div class="span3">';
                echo anchor( base_url( 'beer/location/' . $continents[ 0 ][ 'continent_id' ] . '/' . $subcontinent[ 'subcontinent_id' ] . '/' . $country[ '3166_1_id' ] . '/' ), $country['name'] );
                echo '  &nbsp;(' . $country['num_brewers'] . ')';
                echo '</div>';
            }
        }
        break;
    case 'subcontinent':
        foreach( $countries as $country ) {
            if( $country[ 'subcontinent_id' ] == $subcontinents[ 0 ][ 'subcontinent_id' ] ) {
                echo '<div class="span3">';
                echo anchor( base_url( 'beer/location/' . $continents[ 0 ][ 'continent_id' ] . '/' . $subcontinents[ 0 ][ 'subcontinent_id' ] . '/' . $country[ '3166_1_id' ] . '/' ), $country['name'] );
                echo '  &nbsp;(' . $country['num_brewers'] . ')';
                echo '</div>';
            }
        }
        break;
}

?>
</div>