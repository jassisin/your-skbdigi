<?php
class Calendar {

    private $active_year, $active_month, $active_day;
    private $events = [];

    public function __construct($date = null) {
        $this->active_year = $date != null ? date('Y', strtotime($date)) : date('Y');
        $this->active_month = $date != null ? date('m', strtotime($date)) : date('m');
        $this->active_day = $date != null ? date('d', strtotime($date)) : date('d');
    }

    public function add_event($txt, $date, $days = 1, $color = '') {
        $color = $color ? ' ' . $color : '';
        $this->events[] = [$txt, $date, $days, $color];
    }

    public function __toString() {
        // Calculate number of days in the active Gregorian month
        $num_days = date('t', strtotime($this->active_year . '-' . $this->active_month . '-01'));

        // Calculate the day of the week for the first day of the month (0 for Sunday, 6 for Saturday)
        $first_day_of_week = date('w', strtotime($this->active_year . '-' . $this->active_month . '-01'));

        // Start building the HTML output
        $html = '<div class="calendar">';
        $html .= '<div class="header">';
        $html .= '<div class="month-year">';
        $html .= $this->getMalayalamMonthName($this->active_month) . ' ' . $this->active_year;
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="days">';

        // Display Malayalam day names for each day of the week
        $malayalam_days = ['ഞായർ', 'തിങ്കൾ', 'ചൊവ്വ', 'ബുധൻ', 'വ്യാഴം', 'വെള്ളി', 'ശനി'];
        foreach ($malayalam_days as $day) {
            $html .= '<div class="day_name">' . $day . '</div>';
        }

        // Display days from the previous month if the current month doesn't start on a Sunday
        for ($i = $first_day_of_week; $i > 0; $i--) {
            $prev_day = $num_days - $i + 1;
            $html .= '<div class="day_num ignore">' . $prev_day . '</div>';
        }

        // Display days for the current month
        for ($i = 1; $i <= $num_days; $i++) {
            $selected = '';
            if ($i == $this->active_day) {
                $selected = ' selected';
            }
            $html .= '<div class="day_num' . $selected . '">';
            $html .= '<span>' . $i . '</span>';
            
            // Check for events on the current day
            foreach ($this->events as $event) {
                $event_day = date('d', strtotime($event[1]));
                if ((int)$event_day == $i) {
                    $html .= '<div class="event' . $event[3] . '">';
                    $html .= '<a href="history.php?name=' . urlencode($event[0]) . '">' . $event[0] . '</a>';
                    $html .= '</div>';
                }
            }
            $html .= '</div>';
        }

        // Display days from the next month if necessary to complete the calendar grid
        $days_remaining = 42 - $num_days - $first_day_of_week;
        for ($i = 1; $i <= $days_remaining; $i++) {
            $html .= '<div class="day_num ignore">' . $i . '</div>';
        }

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    private function getMalayalamMonthName($month) {
        // Define Malayalam month names
        $malayalam_months = [
            1 => 'ചിങ്ങം', 2 => 'കന്നി', 3 => 'തുലാം', 4 => 'വൃശ്ചികം',
            5 => 'ധനു', 6 => 'മകരം', 7 => 'കുംഭം', 8 => 'മീനം',
            9 => 'മേടം', 10 => 'ഇടവം', 11 => 'മിഥുനം', 12 => 'കര്‍ക്കടകം'
        ];

        // Return the Malayalam month name based on the provided month number
        return $malayalam_months[(int)$month];
    }
}

// Usage example:
$calendar = new Calendar('2024-07-01'); // Initialize with a specific date
$calendar->add_event('ഓണം', '2024-07-12', 1, 'green'); // Add an event
echo $calendar; // Output the calendar
?>
