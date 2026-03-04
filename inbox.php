<?php
session_start();
include 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$method = "AES-256-CBC";
$key = hash('sha256', SECRET_KEY);
$iv = substr(hash('sha256', SECRET_IV), 0, 16);

$stmt = $conn->prepare(
    "SELECT messages.*, users.username AS sender_name
     FROM messages
     JOIN users ON messages.sender_id = users.id
     WHERE receiver_id = ?"
);

$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Inbox</title>
<style>
body {
    font-family: Arial;
    background: linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    color:white;
    padding:40px;
}
.card {
    background: rgba(0,0,0,0.9);
    padding:20px;
    border-radius:10px;
    margin-bottom:20px;
    box-shadow:0 0 15px #00f2ff;
}
h2 { color:#00f2ff; }
a { color:#00f2ff; text-decoration:none; }
</style>
</head>
<body>

<h2>📥 Encrypted Inbox</h2>

<?php while($row = $result->fetch_assoc()) {

    $decrypted = openssl_decrypt(
        $row['encrypted_message'],
        $method,
        $key,
        0,
        $iv
    );
?>

<div class="card">
<strong>From:</strong> <?php echo $row['sender_name']; ?><br><br>
<strong>Encrypted:</strong><br>
<?php echo $row['encrypted_message']; ?><br><br>
<strong>Decrypted:</strong><br>
<?php echo $decrypted; ?>
</div>

<?php } ?>

<br>
<a href="dashboard.php">⬅ Back to Dashboard</a>

</body>
</html>