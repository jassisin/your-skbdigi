<?php
class Calendar {
    private $active_year, $active_month, $active_day;
    private $events = [];

    // Constructor with an optional date parameter
    public function __construct($date = null) {
        // Handle null or empty $date inputs safely
        if (!empty($date) && strtotime($date)) {
            $this->active_year = date('Y', strtotime($date));
            $this->active_month = date('m', strtotime($date));
            $this->active_day = date('d', strtotime($date));
        } else {
            $this->active_year = date('Y');
            $this->active_month = date('m');
            $this->active_day = date('d');
        }
    }





   public function add_event($txt, $date, $days = 1, $color = '', $url = '') {
        $color = $color ? ' ' . $color : $color;
        $parts = explode('/', $txt);
        $part1 = $parts[1] ?? ''; // Prevent undefined index
        // Store the URL along with the event
        $this->events[] = [$txt, $date, $days, $color, $part1, $url];
    }




    

    // Method to generate the calendar HTML
    public function __toString() {
        $num_days = date('t', strtotime($this->active_year . '-' . $this->active_month . '-01'));
        $num_days_last_month = date('j', strtotime('last day of previous month', strtotime($this->active_year . '-' . $this->active_month . '-01')));
        
        $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $first_day_of_week = array_search(date('D', strtotime($this->active_year . '-' . $this->active_month . '-01')), $days);

        $html = '<div class="calendar">';
        $html .= '<div class="header">';
        $html .= '<div class="month-year">';
        $html .= date('F Y', strtotime($this->active_year . '-' . $this->active_month . '-01'));
        $html .= '</div>';
        $html .= '</div>';

        $html .= '<div class="days">';
        foreach ($days as $day) {
            $html .= '<div class="day_name">' . $day . '</div>';
        }

        // Days from the previous month
        for ($i = $first_day_of_week; $i > 0; $i--) {
            $html .= '<div class="day_num ignore">' . ($num_days_last_month - $i + 1) . '</div>';
        }

        // Current month's days
        for ($i = 1; $i <= $num_days; $i++) {
            $selected = ($i == $this->active_day) ? ' selected' : '';
            $html .= '<div class="day_num' . $selected . '">';
            $html .= '<span>' . $i . '</span>';

            foreach ($this->events as $event) {
                for ($d = 0; $d < $event[2]; $d++) {
                    $event_date = date('Y-m-d', strtotime($this->active_year . '-' . $this->active_month . '-' . $i));
                    $event_start_date = date('Y-m-d', strtotime($event[1] . ' + ' . $d . ' days'));
                    if ($event_date === $event_start_date) {
                        $html .= '<div class="event' . $event[3] . '">';
                        $html .= '<a href="history.php?name=' . htmlspecialchars($event[4]) . '">' . htmlspecialchars($event[0]) . '</a>';
                        $html .= '</div>';
                    }
                }
            }

            $html .= '</div>';
        }

        // Days from the next month
        for ($i = 1; $i <= (42 - $num_days - $first_day_of_week); $i++) {
            $html .= '<div class="day_num ignore">' . $i . '</div>';
        }

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}
?>
