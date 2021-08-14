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
          width:500,
          hAxis: {title: 'Date',  titleTextStyle: {color: '#141414'}},
          vAxis: {title: 'Rs.', minValue: 0},
          chartArea: { backgroundColor: '#f0f0f0' },
          backgroundColor: '#f0f0f0',
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div_1'));
        chart.draw(data, options);
      }
google.charts.load('current', {'packages':['bar']});
google.charts.setOnLoadCallback(drawStuff);
      
    function drawStuff() {
          var data = new google.visualization.arrayToDataTable([
            ['Date', 'No. of bookings'],
            ["15 June", 9],
            ["16 June", 6],
            ["17 June", 12],
            ["18 June", 18],
            ['19 June', 28],
            ['20 June', 24],
            ['21 June', 5]
          ]);
               var options = {
                width: 500,
                chartArea: {
                    backgroundColor: '#f0f0f0'
                },
                bars: 'vertical', 
                backgroundColor: '#f0f0f0',
          };
             var chart = new google.charts.Bar(document.getElementById('chart_div_2'));
          chart.draw(data, options);
        };