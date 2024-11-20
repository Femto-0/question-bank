<?php
// index.php
session_start();
require 'config.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$whereClauses = [];
$params = [];

// Apply filters based on course_id or test_id. More filters can be added over time. 
// Current filters include: Course Id and Test ID
if (isset($_POST['course_id']) && $_POST['course_id'] != '') {
    $whereClauses[] = "document.course_id = :course_id";
    $params['course_id'] = $_POST['course_id'];
}

if (isset($_POST['test_id']) && $_POST['test_id'] != '') {
    $whereClauses[] = "document.test_id = :test_id";
    $params['test_id'] = $_POST['test_id'];
}

$where = '';
if (count($whereClauses) > 0) {
    $where = "WHERE " . implode(" AND ", $whereClauses);
}

$stmt = $pdo->prepare("SELECT * FROM document JOIN status ON document.doc_id = status.doc_id $where AND status.status = 'approved'");
$stmt->execute($params);
$documents = $stmt->fetchAll();
?>

<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <main>
    <h1>Home Page</h1>

    <form method="POST">
        <label for="course_id">Filter by Course:</label>
        <select name="course_id" id="course_id">
            <option value="">Select Course</option>
            <?php
$stmt = $pdo->query("SELECT DISTINCT course_id, course_name FROM course ORDER BY course_name ASC"); // Select distinct courses and sort alphabetically
$courses = $stmt->fetchAll();
foreach ($courses as $course) {
    echo "<option value='" . $course['course_id'] . "'>" . $course['course_name'] . " (" . $course['course_id'] . ")</option>";
}
?>
        </select>

        <label for="test_id">Filter by Test Type:</label>
        <select name="test_id" id="test_id">
            <option value="">Select Test Type</option>
            <?php
            $stmt = $pdo->query("SELECT DISTINCT test_id FROM test_type");
            $test_types = $stmt->fetchAll();
            foreach ($test_types as $test_type) {
                echo "<option value='" . $test_type['test_id'] . "'>" . $test_type['test_id'] . "</option>";
            }
            ?>
        </select>

        <button type="submit">Filter</button>
    </form>

   <!-- Dynamically display images from the database -->
   <div class="documents">
        <?php if (count($documents) > 0): ?>
            <?php foreach ($documents as $document): ?>
                <div class="document">
                    <p>Course: <?php echo htmlspecialchars($document['course_id']); ?> | Test: <?php echo htmlspecialchars($document['test_id']); ?></p>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($document['question_paper_image']); ?>" alt="Question Paper" width="300">
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <h2> Question paper not found <h2>
            <img src="imageAndFont/the_question_bank_how_to.png" alt="how to use the question bank correctly" height="700">
            <img src="imageAndFont/the_question_bank_commandments.png" alt="Rules to upload questio paper" height="1080">
        <?php endif; ?>
    </div>
</main>
</body>
</html>


<?php include 'footer.php'; ?>
