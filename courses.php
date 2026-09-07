<?php

require_once "Database.php";

$database = new Database();
$connection = $database->connect();

$message = "";
$studentID = "10001";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $courseID = intval($_POST["courseID"]);

    $check = mysqli_query(
        $connection,
        "SELECT * FROM Registration
        WHERE studentID = '$studentID'
        AND courseID = $courseID"
    );

    if (mysqli_num_rows($check) > 0) {

        $message = "You are already registered for this course.";

    } else {

        $sql = "INSERT INTO Registration
                (studentID, courseID, semester)
                VALUES
                ('$studentID', $courseID, 'Fall 2026')";

        if (mysqli_query($connection, $sql)) {

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

            $message = "Course registration successful.";

        } else {

            $message = "Course registration failed.";
        }
    }
}

$courses = mysqli_query(
    $connection,
    "SELECT Courses.courseID,
            Courses.courseCode,
            Courses.courseName,
            Courses.semester,
            Courses.maxEnrollment,
            COUNT(Registration.registrationID) AS enrollmentCount
     FROM Courses
     LEFT JOIN Registration
     ON Courses.courseID = Registration.courseID
     GROUP BY Courses.courseID,
              Courses.courseCode,
              Courses.courseName,
              Courses.semester,
              Courses.maxEnrollment"
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Available Courses</title>
    <link rel="stylesheet" href="style.css">

</head>

<body>

    <h1>Available Courses</h1>

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

        <p>
            Enrollment:
            <?php echo $course["enrollmentCount"]; ?>
            of
            <?php echo $course["maxEnrollment"]; ?>
        </p>

        <form method="post">

            <input
                type="hidden"
                name="courseID"
                value="<?php echo $course["courseID"]; ?>"
            >

            <input
                type="submit"
                value="Register for Course"
            >

        </form>

    <?php } ?>

    <p>
        <a href="myCourses.php">My Courses</a>
    </p>

    <p>
        <a href="index.php">Return to Home</a>
    </p>

</body>

</html>