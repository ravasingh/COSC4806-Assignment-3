<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
    <h1>Register</h1>
    <?php
    if (isset($_GET['error'])) {
        echo "<p style='color:red'>" . htmlspecialchars($_GET['error']) . "</p>";
    }
    ?>
    <form action="validate.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>