<!DOCTYPE html>
<html>
<head>
    <title>Student Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>Student Login</h1>

    <form method="post" action="">

        <p>
            <label>Student ID:</label><br>
            <input type="text" name="studentID" required>
        </p>

        <p>
            <label>Password:</label><br>
            <input type="password" name="password" required>
        </p>

        <input type="submit" value="Login">

    </form>

    <p>
        Don't have an account?
        <a href="registration.php">Register Here</a>
    </p>

    <p>
        <a href="index.php">Return to Home</a>
    </p>

</body>
</html>