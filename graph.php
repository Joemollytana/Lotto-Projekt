
<html lang="de">
  <head>
    <meta charset="utf-8">

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <?php
      $results = array(array(10,14,15,18,20,42),array(40,41,42,44,45,46));
    ?>
    <script type="text/javascript">
      var results = <?php echo json_encode($results); ?>;              // [[1, 2, 3, 4, 5, 6]]
      console.log(results)
      var hits = <?php echo $hits; ?>;                    // [1]
      var misses = <?php echo $misses; ?>;                // [5]
      var victoryState = <?php echo $victoryState; ?>;    // [0]*/
      var occurrences = "<?php echo json_encode($occurrences); ?>";      // i = Zahl - 1 [i] = Häufigkeit Zahl

    </script>


    <script type="text/javascript">


      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      var bspArray1 = [['Zahlen', 'Gewinne', 'Nieten'],[20, 30, 40],[20, 30, 40],[20, 30, 40],[20, 30, 40]];
      var chartArray = [['Häufigkeiten']]
      chartArray.push(occurrences)

      function drawChart() {
        var data = google.visualization.arrayToDataTable(
          chartArray);

        var options = {
          chart: {
            title: 'Lotto Auswertung',
            subtitle: 'Welche Zahlen haben wie oft gezogen',
            'height': 800,
            'width': 400,
            "chartArea": {
              "width":'100%',
              "height":'100%'
            }
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_wins_per_num'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>

    <title>Graph für IFrame</title>
  </head>
  <body>
    <div id="columnchart_wins_per_num" style="width: 800px; height: 400px;"></div>
  </body>
</html>
