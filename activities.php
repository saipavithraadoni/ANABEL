<!DOCTYPE html>
<html>
<head>
    <title>Activity Timeline</title>
    <link rel="stylesheet" type="text/css" href="activity.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
    /* Remove margin and padding from the body */
    body {
        margin: 0;
        padding: 0;
    }

    /* Set the height of the timeline and form container */
    #timeline,
    .edit-time-tab {
        height: 20px;
    }

    /* Optional: Add some margin to separate the two containers */
    .edit-time-tab {
        margin-top: 20px;
    }
</style>

    <script type="text/javascript">
        google.charts.load('current', {'packages':['timeline']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var container = document.getElementById('timeline');
            var chart = new google.visualization.Timeline(container);
            var dataTable = new google.visualization.DataTable();

            dataTable.addColumn({ type: 'string', id: 'Category' });
            dataTable.addColumn({ type: 'string', id: 'TaskName' });
            dataTable.addColumn({ type: 'date', id: 'Start' });
            dataTable.addColumn({ type: 'date', id: 'End' });

            dataTable.addRows([
                <?php
                $conn = new mysqli('localhost', 'root', '', 'anabel');
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT CategoryID, TaskName, StartTime, EndTime FROM DailyActivities";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $start = explode(":", $row["StartTime"]);
                        $end = explode(":", $row["EndTime"]);
                        $category = $row["CategoryID"] === '1' ? 'Eating' : ($row["CategoryID"] === '2' ? 'Medication' : 'Sleeping');
                        echo "['" . $category . "', '" . $row["TaskName"] . "', new Date(0, 0, 0, " . $start[0] . ", " . $start[1] . "), new Date(0, 0, 0, " . $end[0] . ", " . $end[1] . ")],";
                    }
                }

                $conn->close();
                ?>
            ]);
            
            var options = {
                timeline: { singleColor: 'lightgray' }
            };

            chart.draw(dataTable, options);
        }

        function updateTask(event) {
            event.preventDefault();

            var taskName = document.getElementById('taskName').value;
            var newStartTime = document.getElementById('newStartTime').value;
            var newEndTime = document.getElementById('newEndTime').value;

            var formData = new FormData();
            formData.append('taskName', taskName);
            formData.append('newStartTime', newStartTime);
            formData.append('newEndTime', newEndTime);

            fetch('update_task.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                drawChart(); // Redraw the chart to reflect changes
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</head>
<body>
    <div id="timeline" style="height: 500px;"></div>
    
    <div class="edit-time-tab">
        <h3>Edit Task Times</h3>
        <form id="editTaskForm" onsubmit="updateTask(event)">
            <label for="taskName">Task Name:</label>
            <select id="taskName" name="taskName">
                <?php
                $conn = new mysqli('localhost', 'root', '', 'anabel');
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "SELECT DISTINCT TaskName FROM DailyActivities";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["TaskName"] . "'>" . $row["TaskName"] . "</option>";
                    }
                }
                $conn->close();
                ?>
            </select>

            <label for="newStartTime">New Start Time:</label>
            <input type="time" id="newStartTime" name="newStartTime" required>

            <label for="newEndTime">New End Time:</label>
            <input type="time" id="newEndTime" name="newEndTime" required>

            <input type="submit" value="Update Task">
        </form>
    </div>
</body>
</html>
