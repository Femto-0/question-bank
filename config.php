<?php
/* configuration to connect to the database
//  database host  (on my mac, I had to provide my TCP/IP i.e"127.0.0.1:3306" instead of just providing my local socket i.e "localhost")
 This behavior is unusual because upon finding my host and username for MySQL server (SELECT current_user;). 
 I got an answer of "root@localhost" which suggests that my host infact is "localhost" but I would get an error of "Connection failed: SQLSTATE[HY000] [2002] No such file or directory" whenever I tried connecting to the database using "localhost" as my host. 
 */                       
$host = "127.0.0.1:3306"; 
$dbname = "question_bank"; // Database name
$username = "root"; // Database username
$password = "root@1234"; // Database password

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

