// Reception page JavaScript functionality

/**
 * Handles the change event for search option dropdown
 * Creates appropriate input field based on selected search criteria
 */
function handleSearchOptionChange() {
    const selectedOption = $('#searchBy').val();
    let searchInputHtml = '';

    if (selectedOption === 'name') {
        searchInputHtml = '<input type="text" id="searchInput" class="form-control" placeholder="Enter Name">';
    } else if (selectedOption === 'status') {
        searchInputHtml = '<select id="searchInput" class="form-select">' +
            '<option value="">Select Status</option>' +
            '<option value="RECEPTION_ENTRY">RECEPTION - ENTRY</option>' +
            '<option value="NURSING_VITAL">NURSING - VITAL</option>' +
            '<option value="MEDICAL">MEDICAL</option>' +
            '<option value="DENTAL">DENTAL</option>' +
            '<option value="NURSING_CARE">NURSING - CARE</option>' +
            '<option value="PHARMACY">PHARMACY</option>' + 
            '<option value="RECEPTION_BILL">RECEPTION - BILL</option>' +
            '</select>';
    }

    $('#searchInputWrapper').html(searchInputHtml);
}

/**
 * Performs AJAX search based on selected criteria and search value
 */
function searchRecords() {
    const searchBy = $('#searchBy').val();
    const searchValue = $('#searchInput').val();

    if (searchBy && searchValue) {
        $.ajax({
            url: 'fetch_records.php', // Use separate file for AJAX
            method: 'POST',
            data: { 
                searchBy: searchBy, 
                searchValue: searchValue 
            },
            success: function(response) {
                $('#tableBody').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Search failed:', error);
                alert('Search failed. Please try again.');
            }
        });
    } else {
        alert("Please select a search criteria and enter search term.");
    }
}

/**
 * Clears search form and reloads page to show all records
 */
function clearSearch() {
    $('#searchBy').val('');
    $('#searchInputWrapper').html('');
    location.reload(); // Reload page to show all records
}

/**
 * Calendar functionality - changes date by specified number of days
 * @param {number} days - Number of days to add/subtract (positive for future, negative for past)
 */
function changeDate(days) {
    // Get current date from date picker or use today
    const currentDate = document.getElementById('datePicker').value ? 
        new Date(document.getElementById('datePicker').value) : 
        new Date();
    
    // Add/subtract days
    currentDate.setDate(currentDate.getDate() + days);
    
    // Update date picker
    document.getElementById('datePicker').value = currentDate.toISOString().split('T')[0];
    
    // Update title
    updateDateTitle(currentDate);
}

/**
 * Jumps to today's date
 */
function goToToday() {
    const today = new Date();
    document.getElementById('datePicker').value = today.toISOString().split('T')[0];
    updateDateTitle(today);
}

/**
 * Handles date picker change event
 */
function jumpToDate() {
    const selectedDate = new Date(document.getElementById('datePicker').value);
    updateDateTitle(selectedDate);
}

/**
 * Updates the calendar title with the selected date
 * @param {Date} date - The date to display
 */
function updateDateTitle(date) {
    const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    };
    const formattedDate = date.toLocaleDateString('en-US', options);
    
    const today = new Date();
    const isToday = date.toDateString() === today.toDateString();
    
    document.getElementById('selectedDateTitle').textContent = 
        isToday ? "Today's Appointments" : `Appointments for ${formattedDate}`;
}

/**
 * Initialize page when DOM is ready
 */
$(document).ready(function() {
    // Initialize search input when page loads
    handleSearchOptionChange();
    
    // Set today's date in date picker
    const today = new Date();
    document.getElementById('datePicker').value = today.toISOString().split('T')[0];
    
    // Initialize calendar title
    updateDateTitle(today);
});