<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$title = "";
$image = "";
$message = "";

$stmt = $conn->prepare("SELECT * FROM items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $title = $row['title'];
    $image = $row['image'];
} else {
    $message = "Record not found.";
}
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newTitle = trim($_POST['title']);

    if (!empty($newTitle)) {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $newImage = basename($_FILES['image']['name']);
            $tmpPath = $_FILES['image']['tmp_name'];
            $uploadPath = "Uploads/" . $newImage;
            move_uploaded_file($tmpPath, $uploadPath);
        } else {
            $newImage = $image;
        }

        $update = $conn->prepare("UPDATE items SET title = ?, image = ? WHERE id = ?");
        $update->bind_param("ssi", $newTitle, $newImage, $id);

        if ($update->execute()) {
            $message = "Record updated successfully.";
            $title = $newTitle;
            $image = $newImage;
        } else {
            $message = "Error: " . $conn->error;
        }
        $update->close();
    } else {
        $message = "Title cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-100 font-sans">
<?php include 'header.php'; ?>

<div class="max-w-2xl mx-auto mt-8 px-4">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Update Record</h2>
    <?php if (!empty($message)): ?>
        <div class="bg-blue-100 text-blue-700 p-4 rounded mb-4"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div class="mb-4">
            <label for="title" class="block text-gray-700 font-medium mb-2">Title</label>
            <input type="text" name="title" id="title" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo htmlspecialchars($title); ?>" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Current Image:</label>
            <?php if (!empty($image)): ?>
                <img src="Uploads/<?php echo htmlspecialchars($image); ?>" class="w-32">
            <?php else: ?>
                <p class="text-gray-600">No image uploaded.</p>
            <?php endif; ?>
        </div>
        <div class="mb-4">
            <label for="image" class="block text-gray-700 font-medium mb-2">Upload New Image (optional)</label>
            <input type="file" name="image" id="image" class="w-full p-2 border border-gray-300 rounded">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        <a href="index.php" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Done</a>
    </form>
</div>

<?php include 'footer.php'; ?>
</body>
</html>