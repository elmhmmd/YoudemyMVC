
<?php
   require_once  APPROOT . "/views/admin/components/sidebar.php";
?>

<!-- Main Content -->
<div class="md:ml-64 p-4 mt-5 ">
    <!-- Header -->
    <div class="flex justify-between mt-14 md:mt-1 items-center mb-8">
        <div class="flex items-center space-x-2">
            <i class="fas fa-chalkboard-teacher text-2xl"></i>
            <h1 class="text-2xl font-bold">Teachers</h1>
        </div>
        <?php require_once APPROOT . "/views/admin/components/userMenu.php" ?>
    </div>

    <!-- Teachers Table -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold">All Teachers</h2>
            <input type="hidden" id="csrf_token" name="csrf_token" value="<?= generateCsrfToken(); ?>">
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="teachersList">
                    <tr>
                        <td colspan="4" class="px-6 py-4">
                            <p class="text-gray-900 text-center text-xl font-semibold">Loading...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Utility function to create teacher row
    function createTeacherRow(teacher) {
        const statusClass = teacher.isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
        const statusText = teacher.isActive ? 'Active' : 'Inactive';
        const actionIcon = teacher.isActive ? 'fa-ban' : 'fa-check';
        const actionColor = teacher.isActive ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900';
        const actionTitle = teacher.isActive ? 'Deactivate' : 'Activate';
        return `
            <tr>
                <td class="px-6 py-4">${teacher.username}</td>
                <td class="px-6 py-4 text-sm text-gray-500">${teacher.email}</td>
                <td class="px-6 py-4">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                        ${statusText}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm">
                    <button onclick="toggleTeacherStatus(${teacher.id}, ${teacher.isActive})" 
                            class="${actionColor} text-xl mx-8" 
                            title="${actionTitle}">
                        <i class="fas ${actionIcon}"></i>
                    </button>
                </td>
            </tr>
        `;
    }

    // Load teachers from server
    async function loadTeachers() {
        try {
            const res = await axios.get('/UknowMvc/admin/allTeachers')
            const teachers = res.data.teachers;
            const teachersList = document.getElementById('teachersList');
            teachersList.innerHTML = teachers.map(teacher => createTeacherRow(teacher)).join('');
        } catch (error) {
            console.error('Error loading teachers:', error);
            showToast('Error loading teachers', 'error');
        }
    }

    const csrf_token = document.getElementById("csrf_token").value ;
    // Toggle teacher status
    async function toggleTeacherStatus(teacherId, currentStatus) {
        const action = currentStatus ? 'desactivate' : 'activate';
        const confirmMessage = `Are you sure you want to ${action} this teacher?`;
        if (confirm(confirmMessage)) {
            try {
                const res = await axios.post('/UknowMvc/admin/toggleStatus', {
                    id: teacherId,
                    status: !currentStatus,
                    csrf_token
                });
                if (res.data.message) {
                    showToast(res.data.message);
                    loadTeachers(); 
                } else if (res.data.error) {
                    showToast(res.data.error, 'error');
                }
            } catch (error) {
                console.error(`Error ${action}ing teacher:`, error);
                showToast(`Error ${action}ing teacher`, 'error');
            }
        }
    }

    // User menu toggle
    const userMenu = document.getElementById('user-menu-button');
    userMenu.addEventListener("click", () => {
        document.getElementById("user-dropdown").classList.toggle("hidden")
    });

    // Initial load
    loadTeachers();
</script>
