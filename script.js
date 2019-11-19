// Get the container element
var btnContainer = document.getElementById("buttons");

// Get all buttons with class="btn" inside the container
var btns = btnContainer.getElementsByClassName("countryBtn");

// Loop through the buttons and add the active class to the current/clicked button
for (var i = 0; i < btns.length; i++) {
    btns[i].addEventListener("click", function () {
        var current = document.getElementsByClassName("active");

        // If there's no active class
        if (current.length > 0) {
            current[0].className = current[0].className.replace(" active", "");
        }

        // Add the active class to the current/clicked button
        this.className += " active";
    });
}
// when clicking on button, hide other countries and show selected country
function showStuff(x) {
    var lottoNumbcontainer = document.getElementsByClassName("numbers");
    for (i = 0; i < lottoNumbcontainer.length; i++) {
        lottoNumbcontainer[i].style.display = "none";
    }
  // idea is to generate the buttons when needed. Maybe generate them beforehand and only show if nessesary?


  //  if (x == de)  {
  //    var generate = 49;
  //  } else if (x == be) {
  //    var generate = 45;
  //  } else if (x == dä) {
  //    var generate = 36;
  //  } else if (x == us) {
  //    var generate = 69;
  //  } else if (x == it) {
  //    var generate = 90;
  //  } else {
  //    var generate = 0;
  //  }

  //  for (i = generate; i > 0; i--){
  //    var lottoNumb = document.createElement("BUTTON");
  //    lottoNumb.value = i;
  //    lottonumb.class = buttonNumbers;
  //    lottonumb.onclick = selectNumber(i);
  //    lottonumb.id = i
  //    document.getElementById(x).appendChild(lottoNumb);
  //  }
    var lottoNumb = document.getElementsByClassName("lottonumb");
    for (i = 0; i < lottoNumb.length; i++) {
        lottoNumb[i].value = "'";
    }
    var x = document.getElementById(x);
    if (x.style.display === "none") {
        x.style.display = "block";
    }
    else {
        x.style.display = "none";

    }
}

// Opens side bar and width is set to 250px
function openNav() {
    if (document.getElementById("mySidenav").style.width == "0px") {
    document.getElementById("mySidenav").style.width = "250px";
    }
    else {
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
    evt.currentTarget.className += " active"
}
// creates counter with reads the value of the slider
var slider = document.getElementById("count");
var output = document.getElementById("countwert");
output.innerHTML = slider.value;

slider.oninput = function() {
    output.innerHTML = this.value;
}
