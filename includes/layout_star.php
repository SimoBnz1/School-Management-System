<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: ../public/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">

<div class="flex">

<!-- Sidebar -->
<div class="w-64 h-screen bg-gray-800 p-5">
    <h2 class="text-xl font-bold mb-6">Student</h2>

    <a href="dashboard.php" class="block mb-3">Dashboard</a>
    <a href="profile.php" class="block mb-3">Profile</a>
    <a href="courses.php" class="block mb-3">Courses</a>

    <a href="../scripts/logout.php" class="block mt-6 text-red-400">Logout</a>
</div>

<div class="flex-1 p-6">