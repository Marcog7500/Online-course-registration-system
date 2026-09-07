<?php

require_once "Database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $studentID = $_POST["studentID"];
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $database = new Database();
    $connection = $database->connect();

    $sql = "INSERT INTO Users (studentID, firstName, lastName, email, phone, password)
            VALUES (?, ?, ?, ?, ?, ?)";

    $statement = $connection->prepare($sql);

    $statement->bind_param(
        "ssssss",
        $studentID,
        $firstName,
        $lastName,
        $email,
        $phone,
        $password
    );

    if ($statement->execute()) {
        $message = "Registration successful.";
    } else {
        $message = "Registration failed. Student ID or email may already exist.";
    }

    $statement->close();
    $connection->close();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>Create Student Account</h1>

    <?php
    if ($message != "") {
        echo "<p>$message</p>";
    }
    ?>

    <form method="post" action="">

        <p>
            <label>Student ID:</label><br>
            <input type="text" name="studentID" required>
        </p>

        <p>
            <label>First Name:</label><br>
            <input type="text" name="firstName" required>
        </p>

        <p>
            <label>Last Name:</label><br>
            <input type="text" name="lastName" required>
        </p>

        <p>
            <label>Email:</label><br>
            <input type="email" name="email" required>
        </p>

        <p>
            <label>Phone Number:</label><br>
            <input type="text" name="phone" required>
        </p>

        <p>
            <label>Password:</label><br>
            <input type="password" name="password" required>
        </p>

        <input type="submit" value="Register">

    </form>

    <p>
        Already have an account?
        <a href="login.php">Login Here</a>
    </p>

    <p>
        <a href="index.php">Return to Home</a>
    </p>

</body>
</html>