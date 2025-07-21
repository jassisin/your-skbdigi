<?php
require 'connection.php';

$date = $_GET['date'] ?? '';
$date = mysqli_real_escape_string($conn, $date);

$sql = "SELECT PID, name, notes, status, created_date, next_visit_date FROM nursing_table WHERE next_visit_date = '$date'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0):
    while ($row = mysqli_fetch_assoc($result)):
        $timeArrived = date('h:i A', strtotime($row['created_date']));
        $pid = htmlspecialchars($row['PID']);
?>
<tr>
    <td><strong><?php echo $pid ?></strong></td>
    <td><?php echo htmlspecialchars($row['name']) ?></td>
    <td><?php echo $timeArrived ?></td>
    <td>
        <input type="text" class="form-control" id="notes-<?php echo $pid ?>" value="<?php echo htmlspecialchars($row['notes'] ??  '')?>">
    </td>
    <td>
        <input type="date" class="date-input" id="nextdate-<?php echo $pid ?>" value="<?php echo htmlspecialchars($row['next_visit_date']) ?>">
    </td>
    <td>
        <select class="status-dropdown" id="status-<?php echo $pid ?>">
            <option value="RECEPTION_ENTRY" <?php echo $row['status'] == 'RECEPTION_ENTRY' ? 'selected' : '' ?>>RECEPTION - ENTRY</option>
            <option value="NURSING_VITAL" <?php echo $row['status'] == 'NURSING_VITAL' ? 'selected' : '' ?>>NURSING - VITAL</option>
            <option value="MEDICAL" <?php echo $row['status'] == 'MEDICAL' ? 'selected' : '' ?>>MEDICAL</option>
            <option value="DENTAL" <?php echo $row['status'] == 'DENTAL' ? 'selected' : '' ?>>DENTAL</option>
            <option value="NURSING_CARE" <?php echo $row['status'] == 'NURSING_CARE' ? 'selected' : '' ?>>NURSING - CARE</option>
            <option value="PHARMACY" <?php echo $row['status'] == 'PHARMACY' ? 'selected' : '' ?>>PHARMACY</option>
            <option value="RECEPTION_BILL" <?php echo $row['status'] == 'RECEPTION_BILL' ? 'selected' : '' ?>>RECEPTION - BILL</option>
        </select>
    </td>
    <td>
        <div class="action-buttons">
            <button class="btn-icon btn-update" onclick="updatePatientStatus('<?php echo $pid ?>')" title="Update Status">
                ✅
            </button>
            <button class="btn-icon btn-call" onclick="callPatient('<?php echo $pid ?>')" title="Call Patient">
                📞
            </button>
        </div>
    </td>
</tr>
<?php
    endwhile;
else:
?>
<tr>
    <td colspan="7" class="text-center">No nursing patients found for this date.</td>
</tr>
<?php
endif;
?>