<button id="sidebarToggle"
    class="fixed  w-16 top-4 left-4 z-50 p-2 rounded-lg bg-gray-900 text-white md:hidden">
    <i class="fas fa-bars"></i>
</button>

<!-- Sidebar -->
<div id="sidebar" class="fixed h-screen inset-y-0 left-0 w-64 bg-gray-900 text-white p-4 sidebar-transition transform -translate-x-full md:translate-x-0 z-40">
    <div class="flex items-center mb-8">
        <a href=<?= URLROOT ?> class="flex items-center space-x-2">
            <span class="text-4xl font-bold bg-gradient-to-r bg-clip-text text-green-600">
                <span class="text-white">You</span>demy
            </span>
        </a>
    </div>

    <nav class="space-y-2">
        <?php
        $currentPage = $_SERVER['REQUEST_URI'];
        ?>
        <a href="/youdemyvc/teacher/dashboard"
            class="flex items-center space-x-2 p-3 rounded-lg <?php echo strpos($currentPage, '/uknow/pages/teacher/dashboard.php') !== false ? 'bg-gray-800' : 'hover:bg-gray-800'; ?>">
            <i class="fas fa-gauge-high"></i>
            <span>Dashboard</span>
        </a>
        <a href="/youdemyvc/teacher/courses"
            class="flex items-center space-x-2 p-3 rounded-lg <?php echo strpos($currentPage, '/uknow/pages/teacher/courses.php') !== false ? 'bg-gray-800' : 'hover:bg-gray-800'; ?>">
            <i class="fas fa-book"></i>
            <span>Courses</span>
        </a>
        <a href="/youdemyvc/teacher/students"
            class="flex items-center space-x-2 p-3 rounded-lg <?php echo strpos($currentPage, '/uknow/pages/teacher/students.php') !== false ? 'bg-gray-800' : 'hover:bg-gray-800'; ?>">
            <i class="fas fa-users"></i>
            <span>Students</span>
        </a>
    </nav>
</div>

<!-- Overlay -->
<div id="overlay"
    class="fixed inset-0 bg-gray-900 bg-opacity-50 z-30 hidden md:hidden"
    onclick="toggleSidebar()">
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggleButton = document.getElementById('sidebarToggle');
    let sidebarOpen = false;

    function toggleSidebar() {
        sidebarOpen = !sidebarOpen;
        if (sidebarOpen) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            toggleButton.innerHTML = '<i class="fas fa-times"></i>';
        } else {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            toggleButton.innerHTML = '<i class="fas fa-bars"></i>';
        }
    }

    toggleButton.addEventListener('click', toggleSidebar);
</script>