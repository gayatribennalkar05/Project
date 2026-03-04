<?php
session_start();
include 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["email"] = $email;

            header("Location: dashboard.php");
            exit();

        } else {
            $message = "❌ Incorrect password.";
        }

    } else {
        $message = "❌ Email not found.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>QuantumShield - Login</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
        }

        .card {
            background: rgba(0, 0, 0, 0.9);
            padding: 40px;
            border-radius: 15px;
            width: 420px;
            box-shadow: 0 0 25px #00f2ff;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #00f2ff;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: none;
            border-radius: 8px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #00f2ff;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #00c6d7;
        }

        .message {
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
        }

        .register-link {
            text-align: center;
            margin-top: 15px;
        }

        .register-link a {
            color: #00f2ff;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>🔐 QuantumShield Login</h2>

    <form method="POST">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login Securely</button>
    </form>

    <div class="message">
        <?php echo $message; ?>
    </div>

    <div class="register-link">
        Don't have an account?
        <a href="register.php">Register here</a>
    </div>
</div>

</body>
</html>