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
              width: 1000,
              chart: { title: 'Total number of bookings made this past week' }, titleTextStyle: { fontName: 'Poppins', fontSize: '25' },
              chartArea: {
                  backgroundColor: '#f0f0f0'
              },
              bars: 'horizontal', 
              backgroundColor: '#f0f0f0',
        };

        var chart = new google.charts.Bar(document.getElementById('chart_div_2'));
        chart.draw(data, options);
      };