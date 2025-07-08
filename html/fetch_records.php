<?php
require "connection.php";

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["searchBy"]) &&
    isset($_POST["searchValue"])
) {
    $searchBy = mysqli_real_escape_string($conn, $_POST["searchBy"]);
    $searchValue = mysqli_real_escape_string($conn, $_POST["searchValue"]);

    if ($searchBy === "name") {
        $sql = "SELECT * FROM reception WHERE name LIKE '%$searchValue%'";
    } elseif ($searchBy === "status") {
        $sql = "SELECT * FROM reception WHERE status = '$searchValue'";
    } else {
        $sql = "SELECT * FROM reception";
    }

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0):
        while ($row = mysqli_fetch_assoc($result)):
?>
<tr>
    <td>
        <a href="edit_patient.php?id=<?php echo $row["id"]; ?>" title="Edit">
            <i class="mdi mdi-pencil" style="font-size:20px;color:#1976d2;"></i>
        </a>
    </td>
    <td><?php echo htmlspecialchars($row["PID"]); ?></td>
    <td><?php echo htmlspecialchars($row["name"]); ?></td>
    <td><?php echo htmlspecialchars($row["status"]); ?></td>
    <td><?php echo htmlspecialchars($row["nationality"]); ?></td>
    <td><?php echo htmlspecialchars($row["phone"]); ?></td>
    <td><?php echo htmlspecialchars($row["whatsapp"]); ?></td>
    <td><?php echo htmlspecialchars($row["area"]); ?></td>
    <td><?php echo htmlspecialchars($row["residence"]); ?></td>
    <td><?php echo htmlspecialchars($row["camp_boss"]); ?></td>
    <td><?php echo htmlspecialchars($row["hr_staff"]); ?></td>
    <td><?php echo htmlspecialchars($row["hr_phone"]); ?></td>
    <td><?php echo htmlspecialchars($row["company"]); ?></td>
    <td><?php echo htmlspecialchars($row["refferal"]); ?></td>
    <td><?php echo htmlspecialchars($row["gate_service_site"]); ?></td>
    <td><?php echo htmlspecialchars($row["notes"]); ?></td>
    <td>
        <form method="post" action="" onsubmit="return confirm('Are you sure you want to delete this patient?');" style="display:inline;">
            <input type="hidden" name="delete_id" value="<?php echo $row["id"]; ?>">
            <button type="submit" name="delete" class="btn btn-link p-0" title="Delete">
                <i class="mdi mdi-trash-can" style="font-size:20px;color:#d32f2f;"></i>
            </button>
        </form>
    </td>
</tr>
<?php
        endwhile;
    else:
?>
<tr>
    <td colspan="17" class="text-center">No records found.</td>
</tr>
<?php
    endif;
}
?>