google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);
function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['', 'Sales'],
          ['15 Jun',  12000],
          ['16 Jun',  10000],
          ['17 Jun',  16000],
          ['18 Jun',  28000],
          ['19 Jun',  45000],
          ['20 Jun',  32000],
          ['21 Jun',  6000]
        ]);

        var options = {
          title: 'Total sales within the past week', titleTextStyle: {fontName: 'Poppins',fontSize: '25'},
          hAxis: {title: 'Date',  titleTextStyle: {color: '#141414'}},
          vAxis: {title: 'Rs.', minValue: 0},
          chartArea: { backgroundColor: '#f0f0f0' },
          backgroundColor: '#f0f0f0',
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div_1'));
        chart.draw(data, options);
      }