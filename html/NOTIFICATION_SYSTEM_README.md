# Desktop Notification System for Department Status Changes

## Overview
This system provides real-time desktop notifications when patients change status from one department to another (e.g., from DENTAL to NURSING). Each department page will receive notifications when new patients are assigned to them.

## Features
- 🔔 **Desktop Notifications**: Browser-based notifications when patients are assigned to your department
- 🎵 **Audio Alerts**: Subtle sound notifications for new assignments
- 📱 **In-Page Toasts**: Visual notifications within the web page
- 🔄 **Real-Time Updates**: Automatic checking every 10 seconds for new patients
- 🎯 **Department-Specific**: Each department only receives relevant notifications

## Files Added/Modified

### New Files:
1. **`js/notifications.js`** - Main notification system JavaScript
2. **`check_notifications.php`** - Backend API to check for new patient assignments
3. **`update_tv_dashboard_for_notifications.sql`** - Database updates for notification tracking
4. **`test_notifications.html`** - Test page for the notification system

### Modified Files:
1. **`nursing.php`** - Added notification system integration
2. **`medical.php`** - Added notification system integration  
3. **`office.php`** - Added notification system integration
4. **`store_tv_dashboard.php`** - Updated to support notification timestamps

## How It Works

### 1. Patient Status Change Flow:
```
Patient moves from DENTAL → NURSING
↓
store_tv_dashboard.php updates database with new timestamp
↓
Nursing page checks for new assignments every 10 seconds
↓
Desktop notification + sound + in-page toast shown
```

### 2. Department Mappings:
- **Reception**: `RECEPTION_ENTRY`, `RECEPTION_BILL`
- **Nursing**: `NURSING_VITAL`, `NURSING_CARE`
- **Medical**: `MEDICAL`
- **Dental**: `DENTAL`
- **Pharmacy**: `PHARMACY`
- **Office**: `OFFICE`

## Setup Instructions

### 1. Database Setup:
Run the SQL script to add necessary columns:
```sql
-- Execute this in your database
source update_tv_dashboard_for_notifications.sql;
```

### 2. Browser Permission:
- First time users will be prompted to allow notifications
- Grant permission for best experience

### 3. Test the System:
- Open `test_notifications.html` to test the notification system
- Grant notification permission
- Test different department scenarios

## Usage

### For Each Department Page:
The notification system is automatically initialized when you visit:
- `nursing.php` → Receives NURSING notifications
- `medical.php` → Receives MEDICAL notifications
- `dental.php` → Receives DENTAL notifications (when you add the script)
- `pharmacy.php` → Receives PHARMACY notifications (when you add the script)
- `reception.php` → Receives RECEPTION notifications (when you add the script)
- `office.php` → Receives OFFICE notifications

### Adding to Additional Pages:
To add notifications to other department pages, add this before `</body>`:

```html
<!-- Desktop Notification System -->
<script src="js/notifications.js"></script>
<script>
    // Additional department-specific notification handling
    function refreshPageData() {
        // Refresh the department patient data
        location.reload();
    }
</script>
```

## Notification Types

### 1. Desktop Notifications:
- Appear outside the browser window
- Show patient name, PID, and department
- Click to focus on the page
- Auto-close after 10 seconds

### 2. Audio Alerts:
- Subtle beep sound
- Non-intrusive frequency
- Plays when new patient assigned

### 3. In-Page Toasts:
- Slide in from right side
- Show detailed patient information
- Manual close button
- Auto-remove after 8 seconds

## Customization

### Notification Frequency:
Change check interval in `js/notifications.js`:
```javascript
this.checkInterval = 10000; // 10 seconds (default)
```

### Sound Settings:
Modify audio alert in the `playNotificationSound()` function.

### Visual Style:
Update CSS classes in the `addToastCSS()` function.

## Troubleshooting

### 1. Notifications Not Showing:
- Check browser notification permission
- Ensure page is not in focus (notifications only show when tab is inactive)
- Check browser console for errors

### 2. No New Patients Detected:
- Verify database has `created_at` column in `tv_dashboard` table
- Check that patient status changes are updating the timestamp

### 3. JavaScript Errors:
- Ensure `js/notifications.js` file exists and is accessible
- Check browser console for specific error messages

## Browser Compatibility
- ✅ Chrome/Chromium (full support)
- ✅ Firefox (full support)
- ✅ Safari (basic support)
- ✅ Edge (full support)

## Security Notes
- Notifications only work on HTTPS or localhost
- No sensitive patient data is stored in browser notifications
- All data transmission uses standard AJAX security practices

## Testing
1. Open `test_notifications.html`
2. Grant notification permission
3. Select a department and initialize notifications
4. Use simulation buttons to test different scenarios
5. Verify notifications appear and function correctly
