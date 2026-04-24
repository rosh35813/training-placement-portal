<?php
require_once __DIR__ . '/../config/config.php';
$db = db_connect();
$errors = array();
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $token = isset($_GET['token']) ? $_GET['token'] : '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password1 = $_POST['password1'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if (empty($token) || empty($password1) || empty($password2)) {
        $errors[] = 'All fields are required.';
    } elseif ($password1 !== $password2) {
        $errors[] = 'Passwords do not match.';
    } elseif (strlen($password1) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if (count($errors) === 0) {
        $stmt = $db->prepare('SELECT user_id, expires_at, used FROM password_resets WHERE token = ? LIMIT 1');
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $row = $res->fetch_assoc()) {
            if ($row['used']) {
                $errors[] = 'Token already used.';
            } elseif (strtotime($row['expires_at']) < time()) {
                $errors[] = 'Token expired.';
            } else {
                $newHash = password_hash($password1, PASSWORD_DEFAULT);
                $upd = $db->prepare('UPDATE student SET S_PASSWORD = ? WHERE STUDENT_ID = ?');
                $upd->bind_param('ss', $newHash, $row['user_id']);
                if ($upd->execute()) {
                    $mark = $db->prepare('UPDATE password_resets SET used = 1 WHERE token = ?');
                    $mark->bind_param('s', $token);
                    $mark->execute();
                    $success = 'Password updated successfully. You can now log in.';
                } else {
                    $errors[] = 'Unable to update password.';
                }
            }
        } else {
            $errors[] = 'Invalid token.';
        }
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
    <style>body{font-family:Arial,Helvetica,sans-serif;padding:2rem}</style>
</head>
<body>
    <h2>Reset Password</h2>
    <?php if ($success): ?>
        <p style="color:green"><?php echo htmlspecialchars($success); ?></p>
        <p><a href="student_login_int.php">Go to login</a></p>
    <?php else: ?>
        <?php if (count($errors)): ?>
            <div style="color:red"><ul>
            <?php foreach ($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?>
            </ul></div>
        <?php endif; ?>
        <form method="post">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token ?? ''); ?>">
            <div>
                <label>New password</label><br>
                <input type="password" name="password1" required>
            </div>
            <div>
                <label>Confirm password</label><br>
                <input type="password" name="password2" required>
            </div>
            <div style="margin-top:1rem"><button type="submit">Set password</button></div>
        </form>
    <?php endif; ?>
</body>
</html>
