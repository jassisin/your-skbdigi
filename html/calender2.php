php
<?php
// Define events
$events = array(
    '2024-05-20' => 'Event 1',
    '2024-05-25' => 'Event 2',
    '2024-05-28' => 'Event 3'
);

// Function to create links for events
function createEventLink($date, $event) {
    return '<a href="event_page.php?date=' . $date . '">' . $event . '</a>';
}

// Function to generate calendar
function generateCalendar($month, $year, $events) {
    $firstDayOfMonth = strtotime("$year-$month-01");
    $daysInMonth = date('t', $firstDayOfMonth);
    $startDayOfWeek = date('N', $firstDayOfMonth);
    $currentDay = 1;

    echo '<table border="1">';
    echo '<tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>';
    echo '<tr>';

    // Print empty cells before the first day of the month
    for ($i = 1; $i < $startDayOfWeek; $i++) {
        echo '<td></td>';
    }

    // Print days of the month
    while ($currentDay <= $daysInMonth) {
        for ($i = $startDayOfWeek; $i <= 7; $i++) {
            if ($currentDay > $daysInMonth) {
                break;
            }
            $date = "$year-$month-$currentDay";
            echo '<td>';
            echo $currentDay;
            if (isset($events[$date])) {
                echo '<br>' . createEventLink($date, $events[$date]);
            }
            echo '</td>';
            $currentDay++;
        }
        echo '</tr>';
        // Start a new row if there are remaining days
        if ($currentDay <= $daysInMonth) {
            echo '<tr>';
        }
        $startDayOfWeek = 1; // Reset start day for the next row
    }
    echo '</table>';
}

// Generate calendar for May 2024
generateCalendar(5, 2024, $events);
?>
