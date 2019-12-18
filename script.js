// Davids Variable, damit der iFrame nicht 1000 mal neu geladen wird
var iFrame_noch_nicht_geladen = true;



//
function checkInputMonth() {
  var inputMonthFull = document.getElementById("month");
  var inputYearFull = document.getElementById("year");

  if (inputMonthFull.value < 1) {
    inputMonthFull.value = inputMonthFull.min;
  } else if (inputMonthFull.value > 12) {
    inputMonthFull.value = inputMonthFull.max;
  }

  if (inputMonthFull.value != "" && inputYearFull.value != "") {
    document.getElementById("age").disabled = false;
  } else {
    document.getElementById("age").disabled = true;
  }
}
//
function checkInputYear() {
  var inputMonthFull = document.getElementById("month");
  var inputYearFull = document.getElementById("year");

  if (inputYearFull.value < 1000) {
    inputYearFull.value = inputYearFull.min;
  } else if (inputYearFull.value > 3000) {
    inputYearFull.value = inputYearFull.max;
  }

  if (inputMonthFull.value != "" && inputYearFull.value != "") {
    document.getElementById("age").disabled = false;
  } else {
      document.getElementById("age").disabled = true;
  }
}
//
function checkAge() {

  var d = new Date();
  var month = d.getMonth() + 1;
  var inputMonthFull = document.getElementById("month");
  var inputMonth = inputMonthFull.value;

  var year = d.getFullYear();
  var inputYearFull = document.getElementById("year");
  var inputYear = inputYearFull.value;

  if (year - inputYear > 18) {
    window.location.href = "hauptseite.html"

  } else if (year - inputYear == 17) {
    if (month > inputMonth) {
      window.location.href = "hauptseite.html"

    } else {
      alert("Sie sind unter 18! Sie werden zu google.de weitergeleitet.");
      window.location.href = "https://www.google.de/";

    }

  } else {
    alert("Sie sind unter 18! Sie werden zu google.de weitergeleitet.");
    window.location.href = "https://www.google.de/";

  }
}
//
function startUp() {
  // Get the container element
  var btnContainer = document.getElementById("buttons");
  // Get all buttons with class="btn" inside the container
  var btns = btnContainer.getElementsByClassName("countryBtn");
  // Loop through the buttons and add the active class to the current/clicked button
  for (var i = 0; i < btns.length; i++) {
    btns[i].addEventListener("click", function() {
      var current = document.getElementsByClassName("active");

      // If there's no active class
      if (current.length > 0) {
        current[0].className = current[0].className.replace(" active", "");
      }

      // Add the active class to the current/clicked button
      this.className += " active";
    });
  }
  showStuff("de");
  refreshCounter();
}
// when clicking on a country, hide other countries content, generate buttonNumbers and show them
function showStuff(countryCode) {
  var finalC = document.getElementById("finalCountry")
  finalC.value = countryCode
  var removeCountries = document.getElementsByClassName("buttonNumbers");
  // if buttons are already existing, remove the Buttons and remove the final numbers
  if (removeCountries.length != "none") {
    while (removeCountries.length > 0) {
      removeCountries[0].parentNode.removeChild(removeCountries[0]);
      document.getElementById("finalNumbers").value = "";
    }
  }
  // hide all divs content
  var lottoNumbcontainer = document.getElementsByClassName("numbers");
  for (i = 0; i < lottoNumbcontainer.length; i++) {
    lottoNumbcontainer[i].style.display = "none";

  }
  // check how much buttons are needed
  if (countryCode == "de") {
    var generate = 49;
  } else if (countryCode == "be") {
    var generate = 45;
  } else if (countryCode == "dk") {
    var generate = 36;
  } else if (countryCode == "us") {
    var generate = 69;
  } else if (countryCode == "it") {
    var generate = 90;
  } else {
    var generate = 0;
  }
  // generate the required amount of numbers and add needed attributes
  for (j = 1; j < generate + 1; j++) {
    var lottoNumb = document.createElement("INPUT");
    lottoNumb.type = "button";
    lottoNumb.value = j;
    lottoNumb.setAttribute("class", "buttonNumbers");
    var func = j
    lottoNumb.setAttribute("onclick", "selectedNumber(" + j + ")");
    lottoNumb.id = j
    document.getElementById(countryCode).appendChild(lottoNumb);
  }
  //show only needed country content
  var x = document.getElementById(countryCode);
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}
// Opens side bar and width is set to 250px
function openNav() {
  if (document.getElementById("mySidenav").style.width == "0px") {
    document.getElementById("mySidenav").style.width = "250px";
  } else {
    document.getElementById("mySidenav").style.width = "0px"
  }
}
// Set the width of the side navigation to 0
function closeNav() {
  document.getElementById("mySidenav").style.width = "0px";
}
// Opens Tab by hiding all tabcontent and opening the contend by id
function openTab(evt, tabName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";

  // Bei Klick iFrame aktualisieren
  //if (tabName == 'Graph' && iFrame_noch_nicht_geladen) {
  //  setTimeout(function() {
  //    document.getElementById('iframe').contentWindow.location.reload(true);
  //    iFrame_noch_nicht_geladen = false;
  //  }, 200)
  //}
}
// creates counter with reads the value of the slider
function refreshCounter() {
  var slider = document.getElementById("count");
  var counterOutput = document.getElementById("countwert");
  var draw = document.getElementById("finalDraw")
  counterOutput.value = slider.value;
  draw.value = slider.value;

  slider.oninput = function() {
    counterOutput.value = this.value;
    draw.value = this.value;
  }
  //################################################!!!!!!!
  counterOutput.onchange = function() { ///////ÜBERARBEITEN
    slider.value = this.value;
    draw.value = this.value;
    if (this.value < 1) {
      this.value = 1;
      slider.value = this.value;
      draw.value = this.value;
    }

  }
}
// selected Number is marked as active and gives them to the form
function selectedNumber(i) {
  var numba = document.getElementById(i);
  var finalNumbersList = document.getElementsByClassName("buttonNumbers active");
  var countryCodeList = document.getElementsByClassName("countryBtn active");
  var countryCode = countryCodeList[0].value;
  // checks how much buttons can be marked as active max
  if (countryCode == "Deutschland" || countryCode == "Belgien" || countryCode == "Italien") {
    var maxLottoNumbers = 6;
  } else if (countryCode == "Dänemark") {
    var maxLottoNumbers = 7;
  } else if (countryCode == "USA") {
    var maxLottoNumbers = 5;
  } else {
    alert("Fehler bei CountryCode!")
  }
  //mark unactive button as active if unactive or unactive if active
  if (numba.className != "buttonNumbers active") {
    // check how many active buttons are and compare with max amount
    if (finalNumbersList.length < maxLottoNumbers) {
      numba.className += " active";
    } else {
      alert("Zu viele Zahlen ausgewählt! Maximalmenge ist: " + maxLottoNumbers);
    }
  } else {
    numba.className = numba.className.replace(" active", "");
  }
  // if got max amount of final numbers then give the list to the form
  if (finalNumbersList.length == maxLottoNumbers) {
    var fNumb = []
    for (i = 0; i < finalNumbersList.length; i++) {
      fNumb.push(finalNumbersList[i].value);
    }
    var x = fNumb.toString();
    document.getElementById("finalNumbers").value = x;
  } else {
    document.getElementById("finalNumbers").value = "";
  }
}
/////////////////////////////////////////////////////////////////////////////////
