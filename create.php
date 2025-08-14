<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$title = "";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $imageName = '';

    if (!empty($title)) {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'Uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $imageName = basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $imageName;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $message = "Failed to upload image.";
            }
        }

        if (empty($message)) {
            $stmt = $conn->prepare("INSERT INTO items (title, image) VALUES (?, ?)");
            $stmt->bind_param("ss", $title, $imageName);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                $message = "Database error: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        $message = "Please enter a title.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Record</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-100 font-sans">
<?php include 'header.php'; ?>

<div class="max-w-2xl mx-auto mt-8 px-4">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Create New Record</h2>
    <?php if (!empty($message)): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST" action="create.php" enctype="multipart/form-data">
        <div class="mb-4">
            <label for="title" class="block text-gray-700 font-medium mb-2">Title</label>
            <input type="text" name="title" id="title" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($title); ?>" required>
        </div>
        <div class="mb-4">
            <label for="image" class="block text-gray-700 font-medium mb-2">Upload Image (optional)</label>
            <input type="file" name="image" id="image" class="w-full p-2 border border-gray-300 rounded">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create</button>
    </form>
</div>

<?php include 'footer.php'; ?>
</body>
</html>