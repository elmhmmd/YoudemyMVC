
<?php
    require_once  APPROOT . "/views/admin/components/sidebar.php";
?>

<!-- Main Content -->
<div class="md:ml-64 p-4">
    <!-- Header -->
    <div class="flex justify-between mt-14 md:mt-1 items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold">Course Dashboard</h1>
            <p class="text-gray-600">Welcome back, Admin!</p>
        </div>
        <?php require_once APPROOT . "/views/admin/components/userMenu.php" ?>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600">Total Courses</p>
                    <h3 class="text-2xl font-bold"><?= $data['coursesCount'] ?></h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-book text-blue-600"></i>
                </div>
            </div>
        </div>

    </div>
    <div class="grid lg:grid-cols-2 max-h-[500px] overflow-y-auto gap-10">
        <div class="bg-white rounded-lg p-5">
            <h1 class="font-bold text-xl text-blue-700 mb-5">Courses distribution by category</h1>
            <?php foreach ($data['courseDistribution'] as $category) : ?>
                <div class="flex my-4 items-center justify-between">
                    <span class="font-bold text-md"><?= $category["category_name"] ?></span>
                    <span class="font-bold text-lg text-neutral-700"><?= $category["course_count"] ?></span>
                </div>

            <?php endforeach; ?>
        </div>
        <div class="bg-white rounded-lg  p-5">
            <h1 class="font-bold text-xl text-blue-700 mb-5">Best 3 courses</h1>
            <?php foreach ($data['best3Courses'] as $course) : ?>
                <div class="flex my-4 items-center justify-between">
                    <span class="font-bold text-md"><?= $course["title"] ?></span>
                    <span class="font-bold text-lg text-neutral-700"><?= $course["enrolled_users"] ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Students Table -->
    <div class="bg-white rounded-lg shadow my-8">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold">Best 3 Students</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Courses enrolled</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="studentsList">
                    <?php foreach($data['best3Students'] as $student) :?>
                    <tr>
                        <td class="px-6 py-4"><?= $student["username"] ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?= $student["email"] ?></td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?= $student["enrolled_courses"] ?></td>
                    </tr>
                    <?php endforeach ;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    // set the target element that will be collapsed or expanded (eg. navbar menu)
    const userMenu = document.getElementById('user-menu-button');
    userMenu.addEventListener("click", () => {
        document.getElementById("user-dropdown").classList.toggle("hidden")
    })
</script>
