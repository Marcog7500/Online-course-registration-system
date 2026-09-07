<?php

require_once "Database.php";

$database = new Database();
$connection = $database->connect();

$studentID = "10001";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $registrationID = intval($_POST["registrationID"]);

    $result = mysqli_query(
        $connection,
        "SELECT courseID
        FROM Registration
        WHERE registrationID = $registrationID
        AND studentID = '$studentID'"
    );

    $registration = mysqli_fetch_assoc($result);

    if ($registration) {

        $courseID = $registration["courseID"];

        mysqli_query(
            $connection,
            "DELETE FROM Registration
            WHERE registrationID = $registrationID"
        );

        $countResult = mysqli_query(
            $connection,
            "SELECT COUNT(*) AS total
            FROM Registration
            WHERE courseID = $courseID"
        );

        $countRow = mysqli_fetch_assoc($countResult);
        $total = $countRow["total"];

        mysqli_query(
            $connection,
            "UPDATE Courses
            SET currentEnrollment = $total
            WHERE courseID = $courseID"
        );

        $message = "Course removed successfully.";
    }
}

$courses = mysqli_query(
    $connection,
    "SELECT Registration.registrationID,
            Courses.courseCode,
            Courses.courseName,
            Courses.semester
     FROM Registration
     JOIN Courses
     ON Registration.courseID = Courses.courseID
     WHERE Registration.studentID = '$studentID'"
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>My Courses</title>
    <link rel="stylesheet" href="style.css">

</head>

<body>

    <h1>My Courses</h1>

    <p>Student ID: 10001</p>

    <?php

    if ($message != "") {
        echo "<p>$message</p>";
    }

    ?>

    <?php while ($course = mysqli_fetch_assoc($courses)) { ?>

        <h3>
            <?php echo $course["courseCode"]; ?>
            <?php echo $course["courseName"]; ?>
        </h3>

        <p>
            Semester:
            <?php echo $course["semester"]; ?>
        </p>

        <form method="post">

            <input
                type="hidden"
                name="registrationID"
                value="<?php echo $course["registrationID"]; ?>"
            >

            <input
                type="submit"
                value="Remove Course"
            >

        </form>

    <?php } ?>

    <p>
        <a href="courses.php">Add More Courses</a>
    </p>

    <p>
        <a href="index.php">Return to Home</a>
    </p>

</body>

</html>