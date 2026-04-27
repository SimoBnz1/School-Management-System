<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Layout</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-gray-100">

  <div class="flex h-screen p-4 gap-4">

    <!-- Sidebar -->
    <aside class="w-64 bg-gradient-to-b from-blue-600 to-blue-700 text-white rounded-2xl p-5 flex flex-col justify-between shadow-lg">

      <div>
        <!-- Logo -->
        <div class="flex items-center gap-3 mb-8">
          <div class="bg-white text-blue-600 p-2 rounded-lg">
            <i class="fa-solid fa-shield"></i>
          </div>
          <h1 class="text-lg font-semibold">EduSync</h1>
        </div>

        <!-- Menu -->
        <nav class="space-y-3">
          <a href="#" class="flex items-center gap-3 bg-white/20 px-4 py-2 rounded-lg">
            <i class="fa-solid fa-house"></i>
            <span>Dashboard</span>
          </a>

          <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10">
            <i class="fa-solid fa-book"></i>
            <span>My Courses</span>
          </a>

          <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10">
            <i class="fa-solid fa-users"></i>
            <span>Students</span>
          </a>

          <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10">
            <i class="fa-solid fa-chalkboard"></i>
            <span>Classes</span>
          </a>

          <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10">
            <i class="fa-solid fa-envelope"></i>
            <span>Messages</span>
          </a>
        </nav>
      </div>

      <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-white/10">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span>Logout</span>
      </a>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col gap-4">

      <!-- Header -->
      <header class="bg-white rounded-2xl px-6 py-4 flex items-center justify-between shadow">

        <!-- Title -->
        <h2 class="text-xl font-semibold text-gray-700">
          My Dashboard
        </h2>

        <!-- Right -->
        <div class="flex items-center gap-6">

          <!-- Notification -->
          <button class="text-gray-500 hover:text-gray-700">
            <i class="fa-regular fa-bell text-lg"></i>
          </button>

          <!-- User -->
          <div class="flex items-center gap-2 cursor-pointer">
            <img src="https://i.pravatar.cc/40" class="w-9 h-9 rounded-full" />
            <span class="text-gray-700 font-medium">Teacher</span>
            <i class="fa-solid fa-chevron-down text-sm text-gray-500"></i>
          </div>

        </div>
      </header>

      <!-- Content (placeholder) -->
      <div class="bg-white rounded-2xl flex-1 shadow p-6">
        <p class="text-gray-600">Content here...</p>
      </div>

    </div>

  </div>

</body>
</html>