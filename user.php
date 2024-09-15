<?php
include 'conn.php'; // Ensure this file sets up the $conn mysqli connection
include 'admin_dashboard.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_escaped = '%' . $search . '%';

// Modify the query to fetch only users that match the search criteria
$sql = 'SELECT * FROM tbl_user WHERE name LIKE ? OR email_address LIKE ? ORDER BY id_user';
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $search_escaped, $search_escaped);
$stmt->execute();
$result = $stmt->get_result();
$tbl_user = $result->fetch_all(MYSQLI_ASSOC);
?>

<form action="user.php" method="GET"></form>
    <div class="content1 read">
        <h1>User Account</h1>
        <div class="link">
            <a href="create.php" class="create">Create New Record</a>
            <input type='search' id='search' name='search' value="<?= htmlspecialchars($search) ?>" placeholder='Search...'>
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Account Verify</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($tbl_user) > 0): ?>
                    <?php foreach ($tbl_user as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id_user']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email_address']) ?></td>
                        <td><?= $user['is_active'] ? 'Verified' : 'Not Verified' ?></td>
                        <td class="actions">
                            <a href="update.php?id=<?= htmlspecialchars($user['id_user']) ?>" class="edit">Edit</a>
                            <a href="delete.php?id=<?= htmlspecialchars($user['id_user']) ?>" class="delete">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No records found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    