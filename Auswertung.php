<html>
<head>
    <?php #$array = [0 => "a", 1 => "b", 2 => "c"];
    #echo $array[0];
    #unset($array[1]);
    #$array = array_values($array);
    #echo $array[1];
    
    function numbers_per_country($country) {
      $length = 0;
      $array = [];
      
      # Zahlen der Ziehung definieren
      if($country == "de"){
        $length = 49;
       } elseif($country == "be"){
        $length = 45;
       } elseif($country == "dk"){
        $length = 36;
       } elseif($country == "us"){
        $length = 69;
       } elseif($country == "it"){
        $length = 90;
       }
      
      # Zahlen der Ziehung in Array füllen
      for($i=0; $i<$length; $i++) {
         $array[$i] = $i+1;
       }
      
      return $array;
    }
    
    function draw($country, $picks, $draws) {
      $numbers = numbers_per_country($country);
      $wins = 0;
      $results = [];
    
      for($i = 1; i <= $draws; $i++){
        $frame = $numbers;
        $draw = [];
        for($j = 1; $j <= count($picks); $j++) {
          $number = rand(0, count($frame));
          $draw[$j] = $frame[$number];
          unset($frame[$number]);
          $draw = array_values($draw);
        }
        $results[] = $draw;
      }
    
      print_r(array_values($results));
    }
    draw("de",["1","2","3","4","5","6"], 2);
    ?>
    <title>Auswertung</title>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColum('string', 'Land');
            data.addColum('number', 'Gewinne');
            data.addRows([
                ['Deutschland', 1],
                ['Deutschland', 1],
                ['Deutschland', 1],
                ['Deutschland', 1],
                ['Deutschland', 1],

            ]);

            var options = {
                'title': 'Graphische Auswertung der Würfe:',
                'width': 500,
                'height': 400
            };
            var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
</head>
<header>
    <h1>Ergebnisse der Lottoziehung:</h1>
    <div>
        <input type="button" value="Zurück zur Lottoziehung" onclick="location.href='lotto.html'">

    </div>
</header>

<br>

<body id="result">

    <main id=main>
        <div class="tab">
            <button class="tablinks" onclick="openTab(event, 'Statistik')" id="defaultOpen">Statistische
                Auswertung</button>
            <button class="tablinks" onclick="openTab(event, 'Daten')">Meine Daten</button>
            <button class="tablinks" onclick="openTab(event, 'Graph')">Graphische Auswertung</button>

        </div>

        <div id="Daten" class="tabcontent">
            <h3>Deine Daten</h3>
            <p>-Lottozahlen</p>
            <p>-Land</p>
            <p>-Wie oft ausgewählt</p>

        </div>

        <div id="Graph" class="tabcontent">
            <h3>Grapische Auswertung</h3>
            <p>Graph einbauen</p>
            <div id="chart_div"></div>


        </div>

        <div id="Statistik" class="tabcontent">
            <h3>Statistische Auswertung</h3>
            <p>Zahlen</p>
        </div>


    </main>
</body>
<footer>
    <div>
        Trotz sorgfältiger inhaltlicher Kontrolle übernimmt die Lotto-DHBW-VS keine Haftung für die Inhalte
        externer Links. Für den Inhalt der verlinkten Seiten sind ausschließlich deren Betreiber verantwortlich.
        Es werden keine Zusicherungen abgegeben und keinerlei Gewährleistung und Haftung für die Richtigkeit der
        bereitgestellten Informationen übernommen. Spielen mit Verantwortung. Spielteilnahme ab 18 Jahren. Glücksspiel
        kann süchtig machen. Mehr Infos unter: www.spielen-mit-verantwortung.de
    </div>
</footer>
<script src="script.js"></script>
<script> document.getElementById("defaultOpen").click()</script>

</html>
