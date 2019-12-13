<html>
<head>
<?php
    # Array of Result-Arrays
    $results        = [];
    # Array of number of hits
    $hits           = [];
    # Array of number of misses
    $misses         = [];
    # Array of statements about victory
    $victoryState   = [];
    # Array of number occurrences
    $occurrences     = [];
    # Array of available and selectable country numbers
    $countryNumbers = [0, 0];

    $picks          = 0;
    $iterations     = 0;
    $drawNumbers    = 0;
    $drawCount      = 0;

    $country        = "";

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

        global $occurrences;

        $drawArray = create_raffle_box($drawNumbers);
        $results = [];

        for($i = 1; $i <= $iterations; $i++){
            $raffle_box = $drawArray;
            $draw = [];

            for($j = 0; $j < $drawCount; $j++) {
                $number = rand(0, count($raffle_box)-1);
                $draw[$j] = $raffle_box[$number];
                $occurrences[$raffle_box[$number]-1]++;

                # adjust raffle box array for drawn numbers
                unset($raffle_box[$number]);
                $raffle_box = array_values($raffle_box);
            }

            sort($draw);
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

    #Form Handling
    function main(){
        #Check if Params have values
        if (isset($_GET["numbers"]) && isset($_GET["country"]) && isset($_GET["draws"])){

            global $results, $hits, $misses, $victoryState, $occurrences, $country, $picks,
                   $iterations, $drawNumbers, $drawCount;

            # Formatting inputs
            $country        = $_GET["country"];
            $countryNumbers = numbers_per_country($country);
            $picks          = explode(",", $_GET["numbers"]);
            $iterations     = $_GET["draws"];
            $drawNumbers    = $countryNumbers[0];
            $drawCount      = $countryNumbers[1];

            # Starting: Occurrences of every number = 0
            $occurrences     = array_fill(0, $drawNumbers, 0);
            # Filling global variables
            $results        = raffle($drawNumbers, $drawCount, $iterations);
            $hits           = count_Hits($picks, $results, $iterations);
            $misses         = count_Misses($picks, $results, $iterations);
            $victoryState   = define_lose_or_victory($hits, $iterations, $drawCount);
        }
    }

   main();
    ?>

<table id="excelTable"></table>
<script type="text/javascript">

    // Array of Arrays
    var results             = <?php echo json_encode($results); ?>;
    var results_as_string   = <?php
    
    foreach($results as &$result){
        $result = implode(", ", $result);
    }
    
    echo json_encode($results);
    
    ?>;

    // Arrays with correlating information at the same index
    var hits            = <?php echo json_encode($hits) ?>;
    var misses          = <?php echo json_encode($misses) ?>;
    var victoryState    = <?php echo json_encode($victoryState) ?>;

    // Arrays with no correlation thus different lengths
    var occurrences     = <?php echo json_encode($occurrences) ?>;
    var picks           = <?php echo json_encode($picks) ?>;

    // Ints
    var iterations      = <?php echo $iterations ?>;
    var drawNumbers     = <?php echo $drawNumbers ?>;
    var drawCount       = <?php echo $drawCount ?>;

    // Strings
    var country         = "<?php echo $country ?>";


    console.log(results.toString())

    // Source: https://www.codexworld.com/export-html-table-data-to-excel-using-javascript/
    function exportTableToExcel(tableID, filename = ''){
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
        
        // Specify file name
        filename = filename?filename+'.xls':'excel_data.xls';
        
        // Create download link element
        downloadLink = document.createElement("a");
        
        document.body.appendChild(downloadLink);
        
        if(navigator.msSaveOrOpenBlob){
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob( blob, filename);
        }else{
            // Create a link to the file
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        
            // Setting the file name
            downloadLink.download = filename;
            
            //triggering the function
            downloadLink.click();
        }
    }

    var table       = document.getElementById('excelTable');
    var tableBody   = document.createElement('TBODY');
    var heading     = new Array("Nr.", "Ziehung", "Treffer", "Daneben", "Resultat");

    table.border = '1';
    table.appendChild(tableBody);

    // Header
    var tr = document.createElement('TR');
    tableBody.appendChild(tr);
    for(i = 0; i < heading.length; i++){
        var th = document.createElement('TH');
        /*th.width = "200";*/
        th.appendChild(document.createTextNode(heading[i]));
        tr.appendChild(th);
    }

    // Rows
    for(i = 0; i < results.length; i++){
        var tr  = document.createElement('TR');

       // Nr. / Result / Hits / Misses / Victory (y / n) 
        var n   = document.createElement('TD');
        var r   = document.createElement('TD');
        var h   = document.createElement('TD');
        var m   = document.createElement('TD');
        var v   = document.createElement('TD');

        n.appendChild(document.createTextNode(i + 1));
        r.appendChild(document.createTextNode(results_as_string[i]));
        h.appendChild(document.createTextNode(hits[i]));
        m.appendChild(document.createTextNode(misses[i]));
        v.appendChild(document.createTextNode(victoryState[i] == 0 ? "Verloren!" : "Gewonnen!"));


        tr.appendChild(n);
        tr.appendChild(r);
        tr.appendChild(h);
        tr.appendChild(m);
        tr.appendChild(v);
        
       tableBody.appendChild(tr);
    }
    

</script>
<style>
    /* Hiding the statistical evaluation table used for excel export*/
    table#excelTable {
        visibility: collapse;
    }
</style>

    <title>Auswertung</title>
    <link rel="stylesheet" href="style.css">
    <!--
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">

      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      var bspArray1 = [['Sales', 'Expenses', 'Profit'],[20, 30, 40],[20, 30, 40],[20, 30, 40],[20, 30, 40]];


      function drawChart() {
        var data = google.visualization.arrayToDataTable(
          bspArray1);

        var options = {
          chart: {
            title: 'Lotto Auswertung',
            subtitle: 'Welche Zahlen haben wie oft gewonnen',
            'height': 800px,
            'width': 400px,
            "chartArea": {
              "width":'100%',
              "height":'100%'
            }
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_wins_per_num'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>-->

    <script type="text/javascript" src="script.js"></script>
    <!--<script type="text/javascript">
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
    </script>-->
</head>
<header>
    <h1>Ergebnisse der Lottoziehung:</h1>
    <div>
        <input type="button" value="Zurück zur Lottoziehung" onclick="location.href='hauptseite.html'">

    </div>
</header>

<br>

<body id="result">

    <main id=main>
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
            <!--<div id="columnchart_wins_per_num" style="width: 800px; height: 400px;"></div>-->
            <iframe src="graph.html" width="100%" height="500" scrolling="no"
                    frameborder="0" seamless>
            </iframe>


        </div>

        <div id="Statistik" class="tabcontent">
          <table style="width:100%">
            <tr>
              <th>nix</th>
            </tr>

          </table>
            <h3>Statistische Auswertung</h3>
            <p>Zahlen</p>

            <button onclick="exportTableToExcel('excelTable', 'lotto_auswertung')">Statistische Auswertung in Excel</button>

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
