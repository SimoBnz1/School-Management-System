<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-gray-100">

<div class="flex h-screen p-4 gap-4">

  <!-- Sidebar -->
  <?php include "aside.php"; ?>

  <!-- Main -->
  <div class="flex-1 flex flex-col gap-4">

    <!-- Header -->
    <?php include "header.php"; ?>

    <!-- 🔥 Content هنا -->
    <div class="bg-white rounded-2xl flex-1 shadow p-6">
        <?php include $page; ?>
    </div>

  </div>

</div>

</body>
</html>