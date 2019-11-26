<html>
<head>
<?php
    # Setting available RAM for calculations to 2GB
    ini_set('memory_limit', '1024M');

    $results        = [];
    $hits           = [];
    $misses         = [];
    $victoryState   = [];

    function numbers_per_country($country) {
      $availableNumbers = 0;
      $selectableCount = 0;
      $availableSelectableArray = [];

      # Defining available and selectable count of numbers
      if ($country == "de"){
        $availableNumbers   = 49;
        $selectableCount    = 6;
       } elseif ($country == "be"){
        $availableNumbers   = 45;
        $selectableCount    = 6;
       } elseif ($country == "dk"){
        $availableNumbers   = 36;
        $selectableCount    = 7;
       } elseif ($country == "us"){
        $availableNumbers   = 69;
        $selectableCount    = 5;
       } elseif ($country == "it"){
        $availableNumbers   = 90;
        $selectableCount    = 6;
       }

       $availableSelectableArray[] = $availableNumbers;
       $availableSelectableArray[] = $selectableCount;

      return $availableSelectableArray;
    }


    function create_raffle_box($length){
        $array = [];

        for($i=0; $i<$length; $i++) {
            $array[$i] = $i+1;
          }
        return $array;
    }

    function raffle($drawNumbers, $drawCount, $iterations) {

        $drawArray = create_raffle_box($drawNumbers);
        $results = [];

        for($i = 1; $i <= $iterations; $i++){
            $raffle_box = $drawArray;
            $draw = [];

            for($j = 0; $j < $drawCount; $j++) {
                $number = rand(0, count($raffle_box)-1);
                $draw[$j] = $raffle_box[$number];
                unset($raffle_box[$number]);
                $raffle_box = array_values($raffle_box);
            }

            if ($iterations > 1){
                $draw = sort($draw);
            }
            $results[] = $draw;
        }
        return $results;
    }

    function count_Hits($picks, $results, $iterations) {
        $hits = [];

        for ($i = 0; $i < $iterations; $i++){
            $hits[] = count(array_intersect($picks, $results[$i]));
        }

        return $hits;
    }

    function count_Misses($picks, $results, $iterations) {
        $misses = [];

        for ($i = 0; $i < $iterations; $i++){
            $misses[] = count(array_diff($picks, $results[$i]));
        }

        return $misses;
    }

    function define_lose_or_victory($arrayOfHits, $iterations, $drawCount){
        $victoryState = [];

        for ($i = 0; $i < $iterations; $i++){
            if ($arrayOfHits[$i] == $drawCount) {
                $victoryState[] = 1;#"Gewinn!";
            } else {
                $victoryState[] = 0;#"Verlust!";
            }
        }

        return $victoryState;
    }

    #function excel_export($results, $hits, $victoryState, $iterations){
    #    for
    #}

    #Form Handling
    function main(){
        #Check if Params have values
        if (isset($_POST["country"]) && isset($_POST["numbers"]) && isset($_POST["draws"])){

            global $results, $hits, $misses, $victoryState;

            $countryNumbers = numbers_per_country($_POST["country"]);
            $picks          = explode(",", $_POST["numbers"]);
            $iterations     = $_POST["draws"];
            $drawNumbers    = $countryNumbers[0];
            $drawCount      = $countryNumbers[1];

            # Correlating Numbers at the same Index
            $results        = raffle($drawNumbers, $drawCount, $iterations);
            $hits           = count_Hits($picks, $results, $iterations);
            $misses         = count_Misses($picks, $results, $iterations);
            $victoryState   = define_lose_or_victory($hits, $iterations, $drawCount);
        }
    }

    main();

    #Test
    #$countryNumbers = numbers_per_country("dk");
    #$picks          = explode(",", "2,3,4,5,6,7,8");
    #$iterations     = "1";
    #$drawNumbers    = $countryNumbers[0];
    #$drawCount      = $countryNumbers[1];
    #
    ## Correlating Numbers at the same Index
    #$results        = raffle($drawNumbers, $drawCount,  $iterations);
    #$hits           = count_Hits($picks, $results, $iterations);
    #$victoryState   = define_lose_or_victory($hits, $iterations, $drawCount);
    #
    #print_r($results);
    #print_r($hits);
    #print_r($victoryState);
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
        <input type="button" value="Zurück zur Lottoziehung" onclick="location.href='index.html'">

    </div>
</header>

<br>

<body id="result">

    <main id=main>
    <?php
    print_r($results);
    print_r($hits);
    ?>
        <div class="tab">
            <button class="tablinks" onclick="openTab(event, 'Statistik')" id="defaultOpen">Statistische
                Auswertung</button>
            <button class="tablinks" onclick="openTab(event, 'Daten')">Zusammenfassung</button>
            <button class="tablinks" onclick="openTab(event, 'Graph')">Graphische Auswertung</button>

        </div>

        <div id="Daten" class="tabcontent">
            <h3>Zusammenfassung</h3>
            <p>-Lottozahlen</p>
            <p>-Anzahl würfe</p>
            <p>-Land</p>
            <p>-Gewonnen:</p>
            <p>-Verloren:</p>
            <p>Wenn anzahl 1, dann anders!</p>

        </div>

        <div id="Graph" class="tabcontent">
            <h3>Grapische Auswertung</h3>
            <p>Graph einbauen als Balkendiagram: - Gewonnen und verloren - Gewinne verluste pro zahl -</p>
            <div id="chart_div"></div>


        </div>

        <div id="Statistik" class="tabcontent">
            <h3>Statistische Auswertung</h3>
            <p>Zahlen</p>
            <input type="button" name="" value="Als Excel exportieren">

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
