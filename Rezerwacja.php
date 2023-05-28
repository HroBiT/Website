<?php
$con = mysqli_connect('localhost','root','','website');

$query = "SELECT FromDate, ToDate FROM reservation WHERE ReservationStatus = 1";
$result = mysqli_query($con, $query);

$reservation = array();

while ($row = mysqli_fetch_assoc($result)) {
    $reservation[] = $row;
}

$reservations_json = json_encode($reservation);
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Rezerwacja</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <script src="script.js" defer></script>
    <script src="pokaz.js" defer></script>
    <script src="kalendarze.js" defer></script>
    <script src="baner.js" defer></script>
  </head>
  <style>
    table {
      border-collapse: collapse;
    }

    th, td {
      border: 1px solid black;
      padding: 10px;
      text-align: center;
    }

    th {
      background-color: lightgray;
    }

    .selected {
      background-color: yellow;
    }

    .saturday {
      background-color: blue;
      color: white;
    }

    .sunday {
      background-color: red;
      color: white;
    }
    .reserved {
  background-color: green;
  color: white;
}
  </style>
  <body>
  <div class="banner">
          <img id="logo" src="logo_sloneczny_stok_2.png" alt="logo">
      </div>
    <div class="menu-wrapper">
  <div class="menu-panel">
    <ul>
    <li><a href="Main.php">Strona główna</a></li>
        <li><a href="Onas.php">O nas</a></li>
        <li><a href="Galeria.php">Galeria</a></li>
        <li><a href="rezerwacja.php">Rezerwacja</a></li>
        <li><a href="pomoc.php">Pomoc</a></li>
        <li><a href="kontakt.php">Kontakt</a></li>
    </ul>
  </div>
</div>
<div class="main">
<h1>Rezerwacje</h1>
<form action="Rezerwacja.php" method="post" class="rezerwacja"><br><br>
  Imie: <input type="text" name="imie" id="imie"><br><br>
  Nazwisko: <input type="text" name="nazwisko" id="nazwisko"><br><br>
  Nr. Telefonu: <input type="number" name="numer" id="numer"><br><br>
  adres email: <input type="email" name="mail" id="mail"><br><br>
  Nick: <input type="text" name="nick" id="nick"><br><br>
  <label for="osoby">Liczba osób:</label>
  <select id="osoby" name="osoby">
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
  </select><br><br>
  Apartament<input type="radio" name="typ" value="a" id="typ1">
  Zwykły pokój<input type="radio" name="typ" value="p" id="typ2">
  <br><br>
  <input type="reset" value="reset"> <button onClick="showCalendars();return false">Pokaż</button>
  <p id="pokaz">
  <label for="numer" id="numer1" style="display: none;">Jaki Numer pokoju:</label>
  <select id="numer2" name="numer" style="display: none;">
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
  </select><br><br>
  <h1 id="napis" style="display: none;">Kalendarze</h1>
  <table id="calendar1" style="display: none;"></table>
  <table id="calendar2" style="display: none;"></table>
  <table id="calendar3" style="display: none;"></table>
  <br>
  <button onClick="calculate();return false;"id="button1" style="display: none;">Oblicz</button><button onClick="resetCalendars();return false" id="button2" style="display: none;">Reset</button><button onClick="sendRequest();return false;" id="button3" style="display: none;">Rezerwuj</button>
  <br>
  <p id="result"></p>

  <script>

  var reservation = <?php echo $reservations_json; ?>;
    

    function markReservedDays() {
  var cells = document.getElementsByTagName('td');

  for (var i = 0; i < cells.length; i++) {
    var cell = cells[i];
    var date = cell.dataset.date;

    for (var j = 0; j < reservation.length; j++) {
      var fromDate = new Date(reservation[j].FromDate);
      var toDate = new Date(reservation[j].ToDate);

      if (date >= fromDate.toISOString() && date <= toDate.toISOString()) {
        cell.classList.add('reserved');
        break;
      }
    }
  }
}

    const NowDate = new Date();
    let selectedDates = [];
    let selectedStartDate, selectedEndDate;

    function getDaysInMonth(month, year) {
      return new Date(year, month + 1, 0).getDate();
    }

    function generateCalendar(tableId, monthOffset) {
      const table = document.getElementById(tableId);
      table.innerHTML = '';

      const month = (NowDate.getMonth() + monthOffset) % 12;
      const year = NowDate.getFullYear() + Math.floor((NowDate.getMonth() + monthOffset) / 12);

      const daysInMonth = getDaysInMonth(month, year);
      const firstDay = new Date(year, month, 1).getDay();

      const daysOfWeek = ['N', 'P', 'W', 'Ś', 'C', 'P', 'S'];

      let dayIndex = 0;
      let html = '<tr>';

      for (let i = 0; i < 7; i++) {
        html += '<th>' + daysOfWeek[i] + '</th>';
      }

      html += '</tr><tr>';

      for (let i = 0; i < firstDay; i++) {
        html += '<td></td>';
      }

      for (let i = 1; i <= daysInMonth; i++) {
        const NowDate = new Date(year, month, i);
        const isWeekend = NowDate.getDay() === 0 || NowDate.getDay() === 6;
        const isSelected = selectedDates.includes(NowDate.toDateString());

        const cssClasses = isWeekend ? (isSelected ? 'selected ' : '') + (NowDate.getDay() === 0 ? 'sunday' : 'saturday') : (isSelected ? 'selected' : '');

        html += '<td class="' + cssClasses + '" onclick="selectDate(this, ' + i + ', ' + month + ', ' + year + ')">' + i + '</td>';

        if ((firstDay + i) % 7 === 0) {
          html += '</tr><tr>';
        }
      }

      html += '</tr>';

      table.innerHTML = html;
      markReservedDays();
    }

    function selectDate(cell, day, month, year) {
      const selectedDate = new Date(year, month, day);

      if (cell.classList.contains('selected')) {
        cell.classList.remove('selected');
        const index = selectedDates.findIndex(date => date === selectedDate.toDateString());
        selectedDates.splice(index, 1);
      } else {
        cell.classList.add('selected');
        selectedDates.push(selectedDate.toDateString());
      }
    }

    function showCalendars() {
  generateCalendar('calendar1', 0);
  generateCalendar('calendar2', 1);
  generateCalendar('calendar3', 2);

      document.getElementById('calendar1').style.display = 'table';
      document.getElementById('calendar2').style.display = 'table';
      document.getElementById('calendar3').style.display = 'table';
      document.getElementById('button1').style.display = 'inline';
      document.getElementById('button2').style.display = 'inline';
      document.getElementById('button3').style.display = 'inline';
      document.getElementById('numer1').style.display = 'flex';
      document.getElementById('numer2').style.display = 'flex';
      document.getElementById('napis').style.display = 'flex';
    }

    function resetCalendars() {
      selectedDates = [];

  generateCalendar('calendar1', 0);
  generateCalendar('calendar2', 1);
  generateCalendar('calendar3', 2);
      document.getElementById('result').textContent = '';
    }

    function calculate() {
  if (selectedDates.length === 0) {
    document.getElementById('result').textContent = 'Nie wybrano żadnych dat.';
    return;
  }

  var radios = document.getElementsByName('typ');

  for (var i = 0; i < radios.length; i++) {
    if (radios[i].checked) {
      console.log('Zaznaczona opcja:', radios[i].value);
      var typ = radios[i].value;
      break;
    }
  }

  var osoby = document.getElementById('osoby').value;
  var numer = document.getElementById('numer2').value;
  var cena;

  selectedDates.sort((a, b) => new Date(a) - new Date(b));

  selectedStartDate = new Date(selectedDates[0]);
  selectedEndDate = new Date(selectedDates[selectedDates.length - 1]);

  const totalDays = Math.ceil((selectedEndDate - selectedStartDate) / (1000 * 60 * 60 * 24)) + 1;
  const weekendsCount = countWeekendDays(selectedStartDate, selectedEndDate);

  if (typ == "Zp") {
    cena = (totalDays * osoby) * 80;
  } else {
    cena = (totalDays * osoby) * 120;
  }

  var RoomN = numer+osoby+typ


  document.getElementById('result').textContent = 'Od: ' + selectedStartDate.toDateString() +
    ', Do: ' + selectedEndDate.toDateString() +
    ', Ilość dni: ' + totalDays +
    ', Ilość weekendów: ' + weekendsCount + ", Cena za pokój: " + cena+", oraz numer bedzie to: "+ RoomN;
}


    function countWeekendDays(startDate, endDate) {
      let count = 0;
      let NowDate = new Date(startDate);

      while (NowDate <= endDate) {
        if (NowDate.getDay() === 0 || NowDate.getDay() === 6) {
          count++;
        }
        NowDate.setDate(NowDate.getDate() + 1);
      }

      return count;
    }
    
function sendRequest() {
 
  if (selectedDates.length === 0) {
  document.getElementById('result').textContent = 'Nie wybrano żadnych dat.';
  return;
}

var radios = document.getElementsByName('typ');

for (var i = 0; i < radios.length; i++) {
  if (radios[i].checked) {
    console.log('Zaznaczona opcja:', radios[i].value);
    var typ = radios[i].value;
    break;
  }
}

var osoby = document.getElementById('osoby').value;
var numer = document.getElementById('numer2').value;
var cena;

selectedDates.sort((a, b) => new Date(a) - new Date(b));

selectedStartDate = new Date(selectedDates[0]);
selectedEndDate = new Date(selectedDates[selectedDates.length - 1]);

const totalDays = Math.ceil((selectedEndDate - selectedStartDate) / (1000 * 60 * 60 * 24)) + 1;
const weekendsCount = countWeekendDays(selectedStartDate, selectedEndDate);

var WhenDate =formatDate(selectedStartDate);
var ToDate = formatDate(selectedEndDate);

console.log(WhenDate);
console.log(ToDate);
  document.cookie="dataWhen="+WhenDate;
  document.cookie="dataTo="+ToDate;

  function formatDate(date) {
  var year = date.getFullYear();
  var month = ('0' + (date.getMonth() + 1)).slice(-2);
  var day = ('0' + date.getDate()).slice(-2);
  return year + '-' + month + '-' + day;


}

}


var reservedDatesCookie = getCookie('reservedDates');
if (reservedDatesCookie) {
  var reservedDates = JSON.parse(reservedDatesCookie);


  generateCalendar('calendar1', 0, reservedDates);
  generateCalendar('calendar2', 1, reservedDates);
  generateCalendar('calendar3', 2, reservedDates);
}


function getCookie(name) {
  var cookieArr = document.cookie.split(';');
  for (var i = 0; i < cookieArr.length; i++) {
    var cookiePair = cookieArr[i].split('=');
    if (name === cookiePair[0].trim()) {
      return decodeURIComponent(cookiePair[1]);
    }
  }
  return null;
}

  </script>
</p>
</form>
<?php
$WhenDate = $_COOKIE['dataWhen'];
$ToDate = $_COOKIE['dataTo'];



if (isset($_COOKIE['dataWhen'])) {
  unset($_COOKIE['dataWhen']);
}

if (isset($_COOKIE['dataTo'])) {
  unset($_COOKIE['dataTo']);
}



?>
</div>
<div class="right-main">
<div class="wrapper">
      <header>
        <p class="current-date"></p>
        <div class="icons">
          <span id="prev" class="material-symbols-rounded">chevron_left</span>
          <span id="next" class="material-symbols-rounded">chevron_right</span>
        </div>
      </header>
      <div class="calendar">
        <ul class="weeks">
          <li>Sun</li>
          <li>Mon</li>
          <li>Tue</li>
          <li>Wed</li>
          <li>Thu</li>
          <li>Fri</li>
          <li>Sat</li>
        </ul>
        <ul class="days"></ul>
      </div>
    </div>
    <script src="https://static.elfsight.com/platform/platform.js" data-use-service-core defer></script>
<div class="elfsight-app-3eb23010-0fda-4df0-8802-9ae15477746b"></div>
</div><br>
    <div class="footer">
      <p>STOPKA</p>
    </div>
  </body>
</html>

