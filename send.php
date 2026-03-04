<?php
session_start();
include 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $receiver_id = $_POST['receiver_id'];
    $plain_text = $_POST['message'];

    if (!empty($receiver_id) && !empty($plain_text)) {

        $method = "AES-256-CBC";
        $key = hash('sha256', SECRET_KEY);
        $iv = substr(hash('sha256', SECRET_IV), 0, 16);

        $encrypted = openssl_encrypt($plain_text, $method, $key, 0, $iv);

        $stmt = $conn->prepare(
            "INSERT INTO messages (sender_id, receiver_id, encrypted_message)
             VALUES (?, ?, ?)"
        );

        $stmt->bind_param("iis", $_SESSION["user_id"], $receiver_id, $encrypted);

        if ($stmt->execute()) {
            $message = "✅ Message Sent Securely!";
        } else {
            $message = "❌ Error sending message.";
        }

        $stmt->close();
    }
}

// Get users for dropdown
$users = $conn->query("SELECT id, username FROM users WHERE id != ".$_SESSION["user_id"]);
?>

<!DOCTYPE html>
<html>
<head>
<title>Send Message</title>
<style>
body {
    font-family: Arial;
    background: linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    color:white;
    padding:40px;
}
.card {
    background: rgba(0,0,0,0.9);
    padding:30px;
    border-radius:15px;
    box-shadow:0 0 20px #00f2ff;
    width:500px;
    margin:auto;
}
h2 { color:#00f2ff; }
select, textarea {
    width:100%;
    padding:10px;
    margin-bottom:15px;
    border-radius:8px;
    border:none;
}
button {
    background:#00f2ff;
    padding:10px 20px;
    border:none;
    border-radius:8px;
    font-weight:bold;
    cursor:pointer;
}
button:hover { background:#00c6d7; }
.msg { margin-top:10px; font-weight:bold; }
a { color:#00f2ff; text-decoration:none; }
</style>
</head>
<body>

<div class="card">
<h2>🔐 Send Encrypted Message</h2>

<form method="POST">
<select name="receiver_id" required>
<option value="">Select Receiver</option>
<?php while($row = $users->fetch_assoc()) { ?>
<option value="<?php echo $row['id']; ?>">
<?php echo $row['username']; ?>
</option>
<?php } ?>
</select>

<textarea name="message" rows="4" placeholder="Enter your secure message..." required></textarea>

<button type="submit">Send Securely</button>
</form>

<div class="msg"><?php echo $message; ?></div>

<br>
<a href="dashboard.php">⬅ Back to Dashboard</a>
</div>

</body>
</html>