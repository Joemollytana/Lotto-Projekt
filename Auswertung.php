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

////////////////////////////////////////////////////////////////////////////////
//////////////////////////   Graphische Auswertung   ///////////////////////////
////////////////////////////////////////////////////////////////////////////////

// Berechnung der Parameter
function createOccurrences_per_number() {
  var chxl = '&chxl=0:|'; // x: numbers --> picks
  var chd = '&chd=t:'; // y: values --> occurrences
  var chds = '&chds=0,'; // scaling y
  var chxr = '&chxr=1,0,'
  // 1. Dimension --> Wins
  for (i=1, len=picks.length; i <= len; i++) {
    if (i < len) {
      chd = chd + occurrences[picks[i-1] - 1] + ',';
      chxl = chxl + picks[i-1] + '|';
    }
    else {
      chd = chd + occurrences[picks[i-1] - 1];
      chxl = chxl + picks[i-1];
    }
  }
  // 2. Dimension --> Loses
  chd = chd + '|'
  for (i=1, len=picks.length; i<=len; i++) {
    if (i < len) {
      chd = chd + (iterations - occurrences[picks[i-1] - 1]) + ',';
    }
    else {
      chd = chd + (iterations - occurrences[picks[i-1] - 1]);
    }
  }
  // Bestimmung sonstiger Parameter
  if (iterations<10) {
    chds = chds + 10;
    chxr = chxr + 10;
  }
  else {
    chds = chds + iterations;//(Math.max.apply(Math, occurrences) * 2);
    chxr = chxr + iterations;//(Math.max.apply(Math, occurrences) * 2);
  }

  return chxr + chds + chxl + chd;
}

// Erstellung des Graphen
function createGraph() {
  //...&chxl=0:|1|2|3|4|5&chs=800x350&chd=t:30,30,50,80,200
  parGraph = createOccurrences_per_number();
  var basicGraph = 'http://chart.apis.google.com/chart?cht=bvg&chxt=x,y&chs=700x350&chco=238555,db0202&chdl=Wins|Loses';//&chxr=1,0,10000&chds=0,10000&   chd=t:30,30,50,80,200|1,2,3,4,5   &chxl=0:|1|2|3;
  var urlGraph = basicGraph + parGraph
  //console.log(chxl);
  document.getElementById('graphicalEvaluation').src = urlGraph;
}


////////////////////////////////////////////////////////////////////////////////
//////////////////////////   Zusammenfassung   /////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
function createSummary(){
  var returnedPicks = picks;
  var returnedThrows = iterations;
  var returnCountry = country;
  var lottoZug = results[0]
  if (returnCountry == "de") {
    returnCountry = "Deutschland";
  } else if (returnCountry == "be") {
    returnCountry = "Belgien";
  } else if (returnCountry == "dk") {
    returnCountry = "Dänemark";
  } else if (returnCountry == "us") {
    returnCountry = "USA";
  } else if (returnCountry == "it") {
    returnCountry = "Italien"
  } else {
    returnCountry = "NONE"
  }
  document.getElementById("yourNumbers").innerHTML= "Deine Lottozahlen sind: " + "<b>" + returnedPicks + "</b>";
  document.getElementById("yourThrows").innerHTML= "Deine Wurfanzahl ist: " + "<b>" + returnedThrows + "</b>";
  document.getElementById("yourCountry").innerHTML= "Dein ausgewähltes Land ist: " + "<b>" + returnCountry + "</b>";

  if (returnedThrows > 1){
    var numberHits = hits.reduce((a, b) => a + b, 0);
    var numberMisses = misses.reduce((a, b) => a + b, 0);


    if (victoryState.includes(1)) {

      var win = victoryState.indexOf(1) + 1;
      var state = "bei deinem <b>" + win + "</b> Wurf <b>gewonnen</b>! Herzlichen Glückwunsch!";
      document.getElementById("yourHits").innerHTML = "Deine Anzahl Treffer ingesammt ist: <b>" + numberHits + "</b>";
      document.getElementById("yourMisses").innerHTML = "Deine Anzahl Treffer ingesammt ist: <b>" + numberMisses + "</b>";

    } else {
      var state = "leider <b>verloren</b>.";
      document.getElementById("yourHits").innerHTML = "Deine Anzahl Treffer ingesammt ist: <b>" + numberHits + "</b>";
      document.getElementById("yourMisses").innerHTML = "Deine Anzahl Misserfolge ingesammt ist: <b>" + numberMisses + "</b>";
    }
  }else if (victoryState[0] == 0) {
      var state = "leider <b>verloren</b>. Versuch es doch nochmal!";
      document.getElementById("yourLotto").innerHTML = "Die gezogenen Zahlen sind: <b>" + lottoZug +"</b>"
    } else {
      var state = "<b>gewonnen!!!</b> Herzlichen Glückwunsch!";
      document.getElementById("yourLotto").innerHTML = "Die gezogenen Zahlen sind: <b>" + lottoZug +"</b>"
    }

    document.getElementById("yourWon").innerHTML = "Du hast " + state;

}
////////////////////////////////////////////////////////////////////////////////
//////////////////////////   Statistische Auswertung  //////////////////////////
////////////////////////////////////////////////////////////////////////////////
function createTable(){
  var tableArray = [];
  var tableResults = results;
  var tableHits = hits;
  var tableLength;
  var i;
  var y;
  var table = document.getElementById("Tabelle");

  for (i=0, tableLength = tableResults.length; i<tableLength ; i++){
    var arrayResults = tableResults[i];
    var arrayHits = tableHits[i];
    var arrayArray = [arrayHits, arrayResults];

    tableArray.push(arrayArray); // [ [arrayhits, [arrayResult]], [array]
  }
  tableArray = tableArray.sort().reverse();

  console.log(tableArray);

  for (y=0; y<50 || y < tableArrayb.length ; y++){
    var row = table.insertRow(y+1)
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    console.log(tableArray[y][1][0]);
    cell1.innerHTML = tableArray[y][1][0];
    cell2.innerHTML = tableArray[y][0];

  }
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

</head>
<header>
    <h1>Ergebnisse der Lottoziehung:</h1>
    <div>
        <input type="button" value="Zurück zur Lottoziehung" class="headerButton" onclick="location.href='hauptseite.html'" class="headerButton">

    </div>
</header>

<br>

<body id="result">

    <main id=main>
        <div class="tab">
            <button class="tablinks" onclick="openTab(event, 'Statistik'); createTable()" >Statistische
                Auswertung</button>
            <button class="tablinks" onclick="openTab(event, 'Daten');createSummary()" id="defaultOpen">Zusammenfassung</button>
            <button class="tablinks" onclick="openTab(event, 'Graph');createGraph()">Graphische Auswertung</button>

        </div>

        <div id="Daten" class="tabcontent">
            <h3>Zusammenfassung</h3>
            <p id="yourNumbers">Keine Lottozahlen</p>
            <p id="yourThrows">Keine Würfe</p>
            <p id="yourCountry">Kein Land</p>
            <p id="yourHits"></p>
            <p id="yourMisses"></p>
            <br>
            <p id="yourLotto"></p>
            <br>
            <p id="yourWon"></p>

        </div>

        <div id="Graph" class="tabcontent">
            <h3>Grapische Auswertung</h3>
            <p>Wie oft wurde die gewählte Zahl tatsächlich gezogen und wie oft war es eine Niete.</p>
            <!--<p>Graph einbauen als Balkendiagram: - Gewonnen und verloren - Gewinne verluste pro zahl -</p>-->
            <!--<div id="columnchart_wins_per_num" style="width: 800px; height: 400px;"></div>-->

            <!--<iframe id="iframe" src="graph.php" width="100%" height="500" scrolling="no"
                    frameborder="0" seamless>
            </iframe>-->
            <iframe id="graphicalEvaluation" src="" ></iframe>


        </div>

        <div id="Statistik" class="tabcontent">


          </table>
            <h3>Statistische Auswertung</h3>
            <table id=Tabelle>
              <tr>Ziehungen
                <th>Ziehungen</th>
                <th>Treffer</th>
              </tr>
            </table>

            <button class="prettyButton" onclick="exportTableToExcel('excelTable', 'lotto_auswertung')">Statistische Auswertung in Excel</button>

        </div>


    </main>
</body>
<footer>
    <div>
        Trotz sorgfältiger inhaltlicher Kontrolle übernimmt die Lotto-DHBW-VS keine Haftung für die Inhalte
        externer Links. Für den Inhalt der verlinkten Seiten sind ausschließlich deren Betreiber verantwortlich.
        Es werden keine Zusicherungen abgegeben und keinerlei Gewährleistung und Haftung für die Richtigkeit der
        bereitgestellten Informationen übernommen. Spielen mit Verantwortung. Spielteilnahme ab 18 Jahren. Glücksspiel
        kann süchtig machen. Mehr Infos unter: <a href="https://www.spielen-mit-verantwortung.de/" target="_blank">www.spielen-mit-verantwortung.de</a>
    </div>

</footer>
<script src="script.js"></script>
<script> document.getElementById("defaultOpen").click()</script>

</html>
