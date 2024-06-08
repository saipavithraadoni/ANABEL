<!DOCTYPE html>
<html>
<head>
    <title>Dynamic Table Display</title>
    <link rel="icon" type="image/png" href="logo.png">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" type="text/css" href="eating.css">
    <script type="text/javascript">
        google.charts.load('current', {'packages':['table']});
        google.charts.setOnLoadCallback(initialize);

        function initialize() {
            setInitialWeek();
            drawTable();
        }

        function setInitialWeek() {
            const currentDate = new Date();
            const currentYear = currentDate.getFullYear();
            document.getElementById('week_input').value = currentYear + '-W01';
        }

        function drawTable() {
            var weekValue = document.getElementById('week_input').value;
            var year = weekValue.substring(0, 4);
            var weekNumber = parseInt(weekValue.substring(6));
            var startDate = new Date(year, 0, 3 + (weekNumber - 1) * 7);
            var endDate = new Date(year, 0, 3 + weekNumber * 7 - 1);

            var formattedStartDate = startDate.toISOString().split('T')[0];
            var formattedEndDate = endDate.toISOString().split('T')[0];

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Date');
            data.addColumn('string', 'Day');
            data.addColumn('string', 'Breakfast Status');
            data.addColumn('string', 'Lunch Status');
            data.addColumn('string', 'Dinner Status');
            data.addColumn('string', 'Snack Status');

            fetch('eating_fetch.php?start=' + formattedStartDate + '&end=' + formattedEndDate)
            .then(response => response.json())
            .then(serverData => {
                console.log('Server Data:', serverData); // Log server data for debugging

                serverData.forEach(row => {
                    // Replace missing or undefined values with 'Unavailable'
                    ['Breakfast_Status', 'Lunch_Status', 'Dinner_Status', 'Snack_Status'].forEach(key => {
                        if (!row[key] || row[key].trim() === '') {
                            row[key] = 'Unavailable';
                        }
                    });

            data.addRow([
                row.Date, 
                row.Day, 
                getStyledCell(row.Breakfast_Status),
                getStyledCell(row.Lunch_Status),
                getStyledCell(row.Dinner_Status),
                getStyledCell(row.Snack_Status)
            ]);
        });

        var table = new google.visualization.Table(document.getElementById('table_div'));
        table.draw(data, {showRowNumber: true, width: '100%', height: '100%', allowHtml: true});
    }).catch(error => {
        console.error('Error fetching data:', error); // Log any fetch errors
    });
}

function getStyledCell(value) {
    var statusMapping = {
        '-1': 'Missed',
        '1': 'Completed',
        '2': 'In Progress',
        'Unavailable': 'Unavailable'
    };

    var displayValue = statusMapping[value] || value;

    var colors = {
        '-1': 'rgba(255, 0, 0, 0.5)', // Red
        '1': 'rgba(0, 128, 0, 0.5)', // Green
        '2': 'rgba(255, 255, 0, 0.5)', // Yellow
        'Unavailable': 'rgba(128, 128, 128, 0.2)' // Grey for Unavailable
    };
    var color = colors[value] || '';
    return '<div style="background-color: ' + color + ';">' + displayValue + '</div>';
}
    </script>
</head>
<body>
    <div class="container">
    <div class="header">
            <h2>Eating schedule</h2>
        </div>
        <div class="navigation">
            <button onclick="window.location.href = 'pwd_dashboard.php';">Home</button>
        </div>
        
        <div class="calendar-input">
            <input type="week" id="week_input" onchange="drawTable()">
        </div>
        <div id="table_div"></div>
        <div class="footer">
            <p>&copy; 2024 Anabel 2.0. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
