<?php
session_start();
require 'db.php';

$loggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Records</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="flex flex-col min-h-screen">
    <?php include 'header.php'; ?>

    <div class="flex-grow container mx-auto px-4 py-8">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-2xl mx-auto">
            <h2 class="text-3xl font-bold text-center text-navy-800 mb-6">All Records</h2>

            <?php if ($loggedIn): ?>
                <p class="text-center text-navy-800 mb-4">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
                <div class="flex justify-center space-x-4 mb-6">
                    <a href="create.php" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-coral-500 font-semibold">Create New Record</a>
                    <a href="logout.php" class="bg-navy-800 text-white px-4 py-2 rounded-lg hover:bg-coral-500 font-semibold">Logout</a>
                </div>
            <?php else: ?>
                <p class="text-center text-navy-800 mb-4">Login to add or manage records. <a href="login.php" class="text-coral-500 hover:underline">Login</a> or <a href="register.php" class="text-coral-500 hover:underline">Register</a></p>
            <?php endif; ?>

            <?php
            $sql = "SELECT * FROM items";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0): ?>
                <table class="w-full border-collapse border border-teal-300">
                    <thead>
                        <tr class="bg-teal-100">
                            <th class="border border-teal-300 p-3 text-navy-800">Title</th>
                            <th class="border border-teal-300 p-3 text-navy-800">Image</th>
                            <th class="border border-teal-300 p-3 text-navy-800">View</th>
                            <?php if ($loggedIn): ?><th class="border border-teal-300 p-3 text-navy-800">Actions</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="border border-teal-300 p-3"><?php echo htmlspecialchars($row['title']); ?></td>
                            <td class="border border-teal-300 p-3">
                                <?php if (!empty($row['image'])): ?>
                                    <img src="Uploads/<?php echo htmlspecialchars($row['image']); ?>" class="w-24">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td class="border border-teal-300 p-3">
                                <a href="read.php?id=<?php echo $row['id']; ?>" class="bg-teal-600 text-white px-2 py-1 rounded-lg hover:bg-coral-500">View</a>
                            </td>
                            <?php if ($loggedIn): ?>
                                <td class="border border-teal-300 p-3">
                                    <a href="update.php?id=<?php echo $row['id']; ?>" class="bg-yellow-600 text-white px-2 py-1 rounded-lg hover:bg-coral-500">Update</a>
                                    <a href="delete.php?id=<?php echo $row['id']; ?>" class="bg-red-600 text-white px-2 py-1 rounded-lg hover:bg-coral-500" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center text-navy-800 mt-4">No records found.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>