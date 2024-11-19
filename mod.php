<?php
// mod.php
//IMPORTANT NOTE: As of now, there is no way a person can sign up as a mod via the webpage. Only way a mod can be created is by harcoding a new mod's existence into MySQL. 
//the mod credential that's present in my local database is: Username: mod1, password: mod1password
session_start();
require 'config.php';

if ($_SESSION['role'] !== 'mod') {
    header('Location: index.php'); // Only mods can access this page
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $doc_id = $_POST['doc_id'];

    if ($action == 'approve') {
        // Approve the question paper
        $stmt = $pdo->prepare("UPDATE status SET status = 'approved', mods_username = :username WHERE doc_id = :doc_id");
        $stmt->execute(['username' => $_SESSION['username'], 'doc_id' => $doc_id]);
    } elseif ($action == 'delete') {
        // Delete the question paper
        $stmt = $pdo->prepare("DELETE FROM document WHERE doc_id = :doc_id");
        $stmt->execute(['doc_id' => $doc_id]);
        $stmt = $pdo->prepare("DELETE FROM status WHERE doc_id = :doc_id");
        $stmt->execute(['doc_id' => $doc_id]);
    }
}

// Fetch all unapproved documents along with their images
$stmt = $pdo->prepare("SELECT document.doc_id, document.course_id, document.test_id, document.upload_date, document.question_paper_image, status.status 
                       FROM document 
                       JOIN status ON document.doc_id = status.doc_id 
                       WHERE status.status = 'unapproved'");
$stmt->execute();
$documents = $stmt->fetchAll();
?>

<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The All Knowing</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
 <main>
    <h1>Mod Dashboard</h1>

    <h2>Unapproved Question Papers</h2>
    <?php foreach ($documents as $document): ?>
        <div class="document">
            <p>Course: <?php echo $document['course_id']; ?> | Test: <?php echo $document['test_id']; ?></p>
            <p>Uploaded: <?php echo $document['upload_date']; ?></p>
            
            <!-- Display the image if it exists -->
            <div class="image-preview">
                <?php if ($document['question_paper_image']): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($document['question_paper_image']); ?>" alt="Question Paper Image" style="max-width: 300px; max-height: 300px;"/>
                <?php else: ?>
                    <p>No image available.</p>
                <?php endif; ?>
            </div>

            <form method="POST">
                <input type="hidden" name="doc_id" value="<?php echo $document['doc_id']; ?>">
                <button type="submit" name="action" value="approve">Approve</button>
                <button type="submit" name="action" value="delete">Delete</button>
            </form>
        </div>
    <?php endforeach; ?>
</main>   
</body>
</html>


<?php include 'footer.php'; ?>
