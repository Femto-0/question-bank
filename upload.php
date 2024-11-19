<?php
// upload.php
session_start();
require 'config.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = $_POST['course_id'];
    $test_id = $_POST['test_id'];
    $file = $_FILES['question_paper'];

    // Validate the file upload
    //The datatype used to store the question papers are of type Medium BLOB whose max capacity is: 5MB and that is why it is necessary to check if the question paper is less than 5MB or not
    if ($file['error'] == 0) {
        // Check file size (example: max size 5MB)
        if ($file['size'] > 5000000) {  // 5MB
            echo "File is too large.";
            exit();
        }

        // Read the file contents
        $fileData = file_get_contents($file['tmp_name']);

        // Check the size of the binary data
        echo 'File size: ' . strlen($fileData);  // Display the size in bytes for debugging

        // Insert the question paper details into the document table
        $stmt = $pdo->prepare("INSERT INTO document (course_id, test_id, question_paper_image) 
                               VALUES (:course_id, :test_id, :question_paper_image)");
        $stmt->execute([
            'course_id' => $course_id,
            'test_id' => $test_id,
            'question_paper_image' => $fileData
        ]);

        // Insert into status table (unapproved status)
        $doc_id = $pdo->lastInsertId();
        $stmt = $pdo->prepare("INSERT INTO status (doc_id, status, mods_username) VALUES (:doc_id, 'unapproved', NULL)");
        $stmt->execute(['doc_id' => $doc_id]);

        // Redirect back to the homepage after uploading
        header("Location: index.php");
        exit();
    } else {
        echo "Error uploading file.";
    }
}
?>

<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
    <h1>Upload Question Paper</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="course_id">Select Course:</label>
        <select name="course_id" id="course_id" required>
            <option value="">Select Course</option>
            <?php
            $stmt = $pdo->query("SELECT DISTINCT course_name, course_id FROM course  ORDER BY course_name ASC");
            $courses = $stmt->fetchAll();
            foreach ($courses as $course) {
                echo "<option value='" . $course['course_id'] . "'>" . $course['course_name'] . "</option>";
            }
            ?>
        </select>

        <label for="test_id">Select Test Type:</label>
        <select name="test_id" id="test_id" required>
            <option value="">Select Test Type</option>
            <?php
            $stmt = $pdo->query("SELECT DISTINCT test_id FROM test_type");
            $test_types = $stmt->fetchAll();
            foreach ($test_types as $test_type) {
                echo "<option value='" . $test_type['test_id'] . "'>" . $test_type['test_id'] . "</option>";
            }
            ?>
        </select>

        <label for="question_paper">Upload Question Paper (Image):</label>
        <input type="file" name="question_paper" id="question_paper" accept="image/*" required>

        <button type="submit">Upload</button>
    </form>
</main>
</body>
</html>


<?php include 'footer.php'; ?>
