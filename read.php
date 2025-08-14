<?php
require 'db.php';
session_start();

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-100 font-sans">
<?php include 'header.php'; ?>

<div class="max-w-2xl mx-auto mt-8 px-4">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">View Record</h2>

    <?php if (!empty($message)): ?>
        <div class="bg-yellow-100 text-yellow-700 p-4 rounded mb-4"><?php echo htmlspecialchars($message); ?></div>
    <?php else: ?>
        <div class="mb-4">
            <strong class="text-gray-700">Title:</strong>
            <p class="text-gray-600"><?php echo htmlspecialchars($title); ?></p>
        </div>
        <div class="mb-4">
            <strong class="text-gray-700">Image:</strong><br>
            <?php if (!empty($image)): ?>
                <img src="Uploads/<?php echo htmlspecialchars($image); ?>" class="w-48">
            <?php else: ?>
                <p class="text-gray-600">No image uploaded.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <a href="index.php" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Back</a>
</div>

<?php include 'footer.php'; ?>
</body>
</html>