<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>QuantumShield Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            color: white;
        }

        .navbar {
            background: black;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            color: #00f2ff;
            font-weight: bold;
            font-size: 20px;
        }

        .nav-links a {
            color: #00f2ff;
            margin-left: 20px;
            text-decoration: none;
            font-weight: bold;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        .container {
            text-align: center;
            padding-top: 100px;
        }

        .card {
            display: inline-block;
            background: rgba(0,0,0,0.9);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 25px #00f2ff;
            width: 500px;
        }

        h1 {
            color: #00f2ff;
            margin-bottom: 10px;
        }

        .btn {
            display: inline-block;
            margin: 15px;
            padding: 12px 25px;
            background: #00f2ff;
            color: black;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn:hover {
            background: #00c6d7;
        }

        .user-info {
            margin-top: 10px;
            font-size: 14px;
            color: #ccc;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">🔐 QuantumShield</div>
    <div class="nav-links">
        <a href="send.php">Send</a>
        <a href="inbox.php">Inbox</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <div class="card">
        <h1>Welcome, <?php echo $_SESSION["username"]; ?> 👋</h1>
        <div class="user-info">
            Logged in as: <?php echo $_SESSION["email"]; ?>
        </div>

        <br>

        <a class="btn" href="send.php">Send Message</a>
        <a class="btn" href="inbox.php">View Inbox</a>
    </div>
</div>

</body>
</html>