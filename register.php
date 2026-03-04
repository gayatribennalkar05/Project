<?php
include 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        $message = "❌ All fields are required.";
    } else {

        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "❌ Email already registered.";
        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Generate simple secure keys (NO OpenSSL, NO Sodium)
            $private_key = bin2hex(random_bytes(32));
            $public_key  = bin2hex(random_bytes(32));

            $stmt = $conn->prepare(
                "INSERT INTO users (username, email, password, public_key, private_key)
                 VALUES (?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "sssss",
                $username,
                $email,
                $hashed_password,
                $public_key,
                $private_key
            );

            if ($stmt->execute()) {
                $message = "✅ Registration Successful! You can now login.";
            } else {
                $message = "❌ Database Error: " . $stmt->error;
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>QuantumShield - Register</title>
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

        .login-link {
            text-align: center;
            margin-top: 15px;
        }

        .login-link a {
            color: #00f2ff;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>🔐 QuantumShield Register</h2>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Create Secure Account</button>
    </form>

    <div class="message">
        <?php echo $message; ?>
    </div>

    <div class="login-link">
        Already have an account?
        <a href="login.php">Login here</a>
    </div>
</div>

</body>
</html>