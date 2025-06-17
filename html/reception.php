<?php
require "session.php";
require "connection.php";

if (isset($_SESSION["main_admin"])) {
    $username = $_SESSION["main_admin"];
} else {
    $username = "Guest"; // Default value if session not set
}

// Fetch all data for initial page load
$sql = "SELECT * FROM reception";
$result = mysqli_query($conn, $sql);

if (isset($_POST["delete"]) && isset($_POST["delete_id"])) {
    $delete_id = intval($_POST["delete_id"]);
    $del_sql = "DELETE FROM reception WHERE id = $delete_id";
    mysqli_query($conn, $del_sql);
    echo "<script>window.location.href='reception.php';</script>";
    exit();
}

// Don't close connection here - it's needed for header.php
$page_title = "Reception List";
$page_heading_color = "#9055fd";
$footer_color = "#ffffff";
include "header_section.php";
?>
<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed layout-compact"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free">
  <head>
    <style>
       /* Enhanced expense table scroll styles */
.expense-table-scroll {
    max-height: 500px; /* Approximate height for 10 rows */
    overflow-y: auto;
    overflow-x: auto;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    background: white;
    margin-bottom: 20px;
}

.expense-table-scroll::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.expense-table-scroll::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.expense-table-scroll::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
    transition: background 0.3s ease;
}

.expense-table-scroll::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Firefox scrollbar styling */
.expense-table-scroll {
    scrollbar-width: thin;
    scrollbar-color: #c1c1c1 #f1f1f1;
}

/* Table styling */
#expenseTable {
    margin-bottom: 0;
    min-width: 100%;
    border-collapse: collapse;
}

#expenseTable thead {
    position: sticky;
    top: 0;
    background: #f8f9fa;
    z-index: 10;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

#expenseTable thead th {
     background: #9055fd !important;
    color: #fff !important;
    font-weight: 600;
    border-bottom: 2px solid #dee2e6;
    padding: 12px 8px;
    text-align: left;
    white-space: nowrap;
}

#expenseTable tbody tr {
    transition: background-color 0.2s ease;
}

#expenseTable tbody tr:hover {
    background-color: #f8f9fa;
}

#expenseTable tbody tr:nth-child(even) {
    background-color: #fbfbfb;
}

#expenseTable tbody tr:nth-child(even):hover {
    background-color: #f0f0f0;
}

#expenseTable td {
    padding: 10px 8px;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
    white-space: nowrap;
}

@media (max-width: 768px) {
    .expense-table-scroll {
        max-height: 400px;
    }
    
    #expenseTable th,
    #expenseTable td {
        padding: 8px 6px;
        font-size: 14px;
    }
}
 * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
         /*   background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            min-height: 100vh;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header Styles */
        .main-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .logo {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .hospital-info h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .hospital-info p {
            color: #64748b;
            font-size: 1rem;
        }

        .header-stats {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .stat-card {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            padding: 16px 20px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #4f46e5;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Calendar Styles */
        .calendar-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .calendar-nav {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .calendar-nav button {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .calendar-nav button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .calendar-nav button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .calendar-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #4f46e5;
            text-align: center;
            flex: 1;
        }

        .date-picker-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .date-picker {
            padding: 10px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #4f46e5;
            background: white;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .date-picker:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .today-btn {
            background: #10b981 !important;
            background: linear-gradient(135deg, #10b981, #059669) !important;
        }

        /* Navigation Tabs */
        .section-tabs {
            display: flex;
            gap: 4px;
            margin-bottom: 24px;
            background: rgba(255, 255, 255, 0.9);
            padding: 6px;
            border-radius: 16px;
            backdrop-filter: blur(10px);
            overflow-x: auto;
        }

        .tab-btn {
            padding: 14px 24px;
            border: none;
            background: transparent;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 120px;
            text-align: center;
            white-space: nowrap;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            box-shadow: 0 4px 16px rgba(79, 70, 229, 0.3);
            transform: translateY(-2px);
        }

        .tab-btn:not(.active) {
            color: #6b7280;
        }

        .tab-btn:not(.active):hover {
            background: rgba(79, 70, 229, 0.1);
            color: #4f46e5;
            transform: translateY(-1px);
        }

        /* Dashboard Cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-value {
            font-size: 2rem;
            font-weight: 700;
            color: #4f46e5;
        }

        .card-trend {
            font-size: 0.875rem;
            color: #10b981;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Section Content */
        .section-content {
            display: none;
        }

        .section-content.active {
            display: block;
        }

        .section-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .section-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #4f46e5;
            margin-bottom: 8px;
        }

        .section-subtitle {
            color: #6b7280;
            font-size: 1rem;
        }

        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-top: 16px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            box-shadow: 0 4px 16px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.9);
            color: #4f46e5;
            border: 2px solid #e5e7eb;
        }

        .btn-secondary:hover {
            background: white;
            border-color: #4f46e5;
            transform: translateY(-1px);
        }

        /* Patient Registration Form */
        .registration-form {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-input, .form-textarea, .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: white;
        }

        .form-input:focus, .form-textarea:focus, .form-select:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        /* Patient Table */
        .patient-table-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }

        .table-header {
            padding: 20px 24px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .table-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
        }

        .appointment-count {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .patient-table {
            width: 100%;
            border-collapse: collapse;
        }

        .patient-table th {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 16px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .patient-table td {
            padding: 16px 12px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .patient-table tr {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .patient-table tr:hover {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            transform: scale(1.001);
        }

        .patient-table tr.selected {
            background: linear-gradient(135deg, #ede9fe, #ddd6fe);
            box-shadow: inset 3px 0 0 #4f46e5;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-nursing { background: #dcfce7; color: #166534; }
        .status-medical { background: #dbeafe; color: #1e40af; }
        .status-dental { background: #fef3c7; color: #92400e; }
        .status-pharmacy { background: #fce7f3; color: #be185d; }
        .status-reception { background: #f3e8ff; color: #7c3aed; }
        .status-marketing { background: #fef2f2; color: #dc2626; }
        .status-office { background: #f0f9ff; color: #0369a1; }

        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-sm {
            padding: 8px 12px;
            font-size: 12px;
            border-radius: 8px;
        }

        .btn-icon {
            padding: 8px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-call {
            background: #10b981;
            color: white;
        }

        .btn-call:hover {
            background: #059669;
            transform: scale(1.1);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .no-appointments {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            border: 2px dashed #e2e8f0;
        }

        .no-appointments-icon {
            font-size: 3rem;
            margin-bottom: 16px;
            opacity: 0.6;
        }

        .no-appointments h3 {
            color: #64748b;
            font-size: 1.25rem;
            margin-bottom: 8px;
        }

        .no-appointments p {
            color: #94a3b8;
            font-size: 0.95rem;
        }

        @media (max-width: 768px) {
            .container { padding: 12px; }
            .header-content { flex-direction: column; align-items: stretch; }
            .header-stats { justify-content: center; }
            .calendar-header { flex-direction: column; text-align: center; }
            .calendar-nav { justify-content: center; }
            .form-grid { grid-template-columns: 1fr; }
            .patient-table { font-size: 12px; }
            .patient-table th, .patient-table td { padding: 8px 6px; }
            .section-tabs { padding: 4px; }
            .tab-btn { padding: 10px 16px; min-width: 100px; }
        }
</style>
  <!-- Dashboard Section -->
        <div class="section-content active" id="dashboard-section">
            <!-- Calendar Section -->
            <div class="calendar-container">
                <div class="calendar-header">
                    <div class="calendar-nav">
                        <button onclick="changeDate(-1)">
                            ⬅️ Previous Day
                        </button>
                        <button class="today-btn" onclick="goToToday()">
                            📅 Today
                        </button>
                        <button onclick="changeDate(1)">
                            Next Day ➡️
                        </button>
                    </div>
                    <div class="calendar-title" id="selectedDateTitle">
                        Today's Appointments
                    </div>
                    <div class="date-picker-container">
                        <label for="datePicker" style="font-size: 14px; color: #6b7280; font-weight: 600;">Jump to Date:</label>
                        <input type="date" id="datePicker" class="date-picker" onchange="jumpToDate()">
                    </div>
                </div>
    </div>
<div class="section-content active" id="reception-section">
    <div class="calendar-container">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="py-3 mb-4"><u>Reception List</u></h4>
                <a href="add_patient.php" class="btn btn-primary">Add Patient</a>
            </div>
            <div class="d-flex mb-4">
                <select id="searchBy" class="form-select" style="width: 180px;" onchange="handleSearchOptionChange()">
                    <option value="">Search By</option>
                    <option value="name">Name</option>
                    <option value="status">Status</option>
                </select>
                <div id="searchInputWrapper" class="ms-3"></div>
                <button id="searchButton" class="btn btn-primary ms-3" onclick="searchRecords()">Search</button>
                <button id="clearButton" class="btn btn-secondary ms-2" onclick="clearSearch()">Clear</button>
            </div>
            <!-- Add space between search and table -->
            <div style="height: 18px;"></div>
            <div class="expense-table-scroll">
                <table class="table table-bordered" id="expenseTable">
                    <thead>
                        <tr>
                            <th>Edit</th>
                            <th>PID</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Nationality</th>
                            <th>Phone</th>
                            <th>Whatsapp</th>
                            <th>Area</th>
                            <th>Residence</th>
                            <th>Camp Boss</th>
                            <th>Hr Staff</th>
                            <th>Phone (HR)</th>
                            <th>Company</th>
                            <th>Referral</th>
                            <th>Gate Service Site</th>
                            <th>Notes</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php // If search is performed, fetch filtered results
                        if (
                            $_SERVER["REQUEST_METHOD"] === "POST" &&
                            isset($_POST["searchBy"]) &&
                            isset($_POST["searchValue"])
                        ) {
                            $searchBy = mysqli_real_escape_string(
                                $conn,
                                $_POST["searchBy"]
                            );
                            $searchValue = mysqli_real_escape_string(
                                $conn,
                                $_POST["searchValue"]
                            );

                            if ($searchBy === "name") {
                                $sql = "SELECT * FROM reception WHERE name LIKE '%$searchValue%'";
                            } elseif ($searchBy === "status") {
                                $sql = "SELECT * FROM reception WHERE status = '$searchValue'";
                            } else {
                                $sql = "SELECT * FROM reception"; // Default to all records
                            }

                            $result = mysqli_query($conn, $sql);
                        } else {
                            // Use the initial query for all records
                            $result = mysqli_query($conn, $sql);
                        } ?>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td>
                                       <a href="edit_patient.php?id=<?php echo $row[
                                           "id"
                                       ]; ?>" title="Edit">
                                       <i class="mdi mdi-pencil" style="font-size:20px;color:#1976d2;"></i>
                                       </a>
                                    </td>
                                    <td><?php echo htmlspecialchars(
                                        $row["PID"]
                                    ); ?></td>
                                    <td><?php echo htmlspecialchars(
                                        $row["name"]
                                    ); ?></td>
                                    <td><?php echo htmlspecialchars(
                                        $row["status"]
                                    ); ?></td>
                                    <td><?php echo htmlspecialchars(
                                        $row["nationality"]
                                    ); ?></td>
                                    <td><?php echo htmlspecialchars(
                                        $row["phone"]
                                    ); ?></td>
                                    <td><?php echo htmlspecialchars(
                                        $row["whatsapp"]
                                    ); ?></td>
                                    <td><?php echo htmlspecialchars(
                                        $row["area"]
                                    ); ?></td>
                                    <td><?php echo htmlspecialchars(
                                        $row["residence"]
                                    ); ?></td>
                                    <td><?php echo htmlspecialchars(
                                        $row["camp_boss"]
                                    ); ?></td>
                                    <td><?php echo htmlspecialchars(
                                        $row["hr_staff"]
                                    ); ?></td>
                                    <td><?php echo htmlspecialchars(
                                        $row["hr_phone"]
                                    ); ?></td>
                                    <td><?php echo htmlspecialchars(
                                        $row["company"]
                                    ); ?></td>
                                    <td><?php echo htmlspecialchars(
                                        $row["refferal"]
                                    ); ?></td>
                                    <td><?php echo htmlspecialchars(
                                        $row["gate_service_site"]
                                    ); ?></td>
                                    <td><?php echo htmlspecialchars(
                                        $row["notes"]
                                    ); ?></td>
                                    <td>
                                         <form method="post" action="" onsubmit="return confirm('Are you sure you want to delete this patient?');" style="display:inline;">
                                         <input type="hidden" name="delete_id" value="<?php echo $row[
                                             "id"
                                         ]; ?>">
                                         <button type="submit" name="delete" class="btn btn-link p-0" title="Delete">
                                         <i class="mdi mdi-trash-can" style="font-size:20px;color:#d32f2f;"></i>
                                         </button>
                                         </form>
                                   </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="17" class="text-center">No records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include "footer_section.php"; ?>

<script>
function handleSearchOptionChange() {
    const selectedOption = $('#searchBy').val();
    let searchInputHtml = '';

    if (selectedOption === 'name') {
        searchInputHtml = '<input type="text" id="searchInput" class="form-control" placeholder="Enter Name">';
    } else if (selectedOption === 'status') {
        searchInputHtml = '<select id="searchInput" class="form-select">' +
                          '<option value="">Select Status</option>' +
                          '<option value="NURSING">NURSING</option>' +
                          '<option value="MEDICAL">MEDICAL</option>' +
                          '<option value="DENTAL">DENTAL</option>' +
                          '<option value="RECEPTION 2">RECEPTION 2</option>' +
                          '<option value="PHARMACY">PHARMACY</option>' +
                          '</select>';
    }

    $('#searchInputWrapper').html(searchInputHtml);
}

function searchRecords() {
    const searchBy = $('#searchBy').val();
    const searchValue = $('#searchInput').val();

    if (searchBy && searchValue) {
        $.ajax({
            url: 'fetch_records.php', // Use separate file for AJAX
            method: 'POST',
            data: { searchBy: searchBy, searchValue: searchValue },
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

function clearSearch() {
    $('#searchBy').val('');
    $('#searchInputWrapper').html('');
    location.reload(); // Reload page to show all records
}

// Initialize search input when page loads
$(document).ready(function() {
    handleSearchOptionChange();
});
</script>

<?php // Close connection at the very end
mysqli_close($conn); ?>
</body>
</html>