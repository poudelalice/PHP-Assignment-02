<?php
require 'db.php';
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashedPassword);

        if ($stmt->execute()) {
            $message = "Registration successful! <a href='login.php' class='text-blue-600 hover:underline'>Click here to login.</a>";
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-100 font-sans">
<?php include 'header.php'; ?>

<div class="max-w-md mx-auto mt-8 px-4">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Register</h2>
    <?php if (!empty($message)): ?>
        <div class="bg-blue-100 text-blue-700 p-4 rounded mb-4"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <div class="mb-4">
            <label for="username" class="block text-gray-700 font-medium mb-2">Username</label>
            <input type="text" name="username" id="username" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
            <input type="password" name="password" id="password" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Register</button>
    </form>
</div>

<?php include 'footer.php'; ?>
</body>
</html>