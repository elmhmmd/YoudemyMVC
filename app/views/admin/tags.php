<?php
    require_once  APPROOT . "/views/admin/components/sidebar.php";
?>

<!-- Main Content -->
<div class="md:ml-64 p-4">
    <!-- Header -->
    <div class="flex justify-between mt-14 md:mt-1 items-center mb-8">
        <div class="flex items-center space-x-2">
            <i class="fas fa-tags text-2xl"></i>
            <h1 class="text-2xl font-bold">Tags</h1>
        </div>
        <?php require_once APPROOT . "/views/admin/components/userMenu.php" ?>
    </div>

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Tags</h1>
        <button onclick="openCreateModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>Add Tag
        </button>
    </div>

    <!-- Tags Table -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold">Tags</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 w-full text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tag name</th>
                        <th class="px-6 py-3 pr-10 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="tagsList">
                    <tr>
                        <td colspan="2" class="px-6 py-4">
                            <p class="text-gray-900 text-center text-xl font-semibold">Loading...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="max-w-4xl mx-auto">
    </div>

    <!-- Tag modal -->
    <div id="tagModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto z-50 h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h2 class="text-lg font-semibold" id="modalTitle">Create Tags</h2>
                <form id="tagForm" class="mt-4">
                <input type="hidden" id="csrf_token" name="csrf_token" value="<?= generateCsrfToken(); ?>">
                    <input type="hidden" id="tagId" value="">
                    <div id="tagInputs" class="space-y-4">
                        <div class="flex items-center space-x-2">
                            <input type="text"
                                class="flex-1 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                                name="tags[]"
                                placeholder="Tag name">
                            <button type="button" class="text-red-500 hover:text-red-700" onclick="removeInput(this)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button id="add_input_btn" type="button"
                        onclick="addTagInput()"
                        class="mt-4 w-full border border-gray-300 rounded-lg px-4 py-2 hover:bg-gray-50">
                        <i class="fas fa-plus mr-2"></i>Add Another Tag
                    </button>
                    <div class="flex justify-end space-x-4 mt-4">
                        <button type="button"
                            onclick="closeModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Utility function to create tag row
        function createTagRow(tag) {
            return `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">${tag.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap space-x-2">
                        <button onclick="editTag(${tag.id}, '${tag.name}')" 
                                class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteTag(${tag.id})" 
                                class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        }

        // Load tags from server
        async function loadTags() {
            try {
                const res = await axios.get('/UknowMvc/tags')
                const tags = res.data.tags;
                const tagsList = document.getElementById('tagsList');
                if (tags.length === 0) {
                    tagsList.innerHTML = `
                        <tr>
                            <td colspan="2" class="px-6 py-4">
                                <p class="text-gray-900 text-center text-xl font-semibold">No tags found.</p>
                            </td>
                        </tr>
                    `;
                    return;
                }
                tagsList.innerHTML = tags.map(tag => createTagRow(tag)).join('');
            } catch (error) {
                console.error('Error loading tags:', error);
            }
        }

        // Modal management
        function openCreateModal() {
            document.getElementById('modalTitle').textContent = 'Create Tags';
            document.getElementById('tagId').value = '';
            document.getElementById("add_input_btn").disabled = false;
            document.getElementById('tagForm').reset();
            document.getElementById('tagInputs').innerHTML = `
                <div class="flex items-center space-x-2">
                    <input type="text" 
                           class="flex-1 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500" 
                           name="tags[]" 
                           placeholder="Tag name">
                    <button type="button" class="text-red-500 hover:text-red-700" onclick="removeInput(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            document.getElementById('tagModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('tagModal').classList.add('hidden');
        }

        // Input field management
        function addTagInput() {
            const container = document.getElementById('tagInputs');
            const newInput = document.createElement('div');
            newInput.className = 'flex items-center space-x-2';
            newInput.innerHTML = `
                <input type="text" 
                       class="flex-1 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500" 
                       name="tags[]" 
                       placeholder="Tag name">
                <button type="button" class="text-red-500 hover:text-red-700" onclick="removeInput(this)">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(newInput);
        }

        function removeInput(button) {
            const inputsContainer = document.getElementById('tagInputs');
            if (inputsContainer.children.length > 1) {
                button.parentElement.remove();
            }
        }

        // Edit tag
        function editTag(id, name) {
            document.getElementById('modalTitle').textContent = 'Update Tag';
            document.getElementById("add_input_btn").disabled = true;
            document.getElementById('tagId').value = id;
            document.getElementById('tagInputs').innerHTML = `
                <div class="flex items-center space-x-2">
                    <input type="text" 
                           class="flex-1 border rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500" 
                           name="tags[]" 
                           value="${name}"
                           placeholder="Tag name">
                </div>
            `;
            document.getElementById('tagModal').classList.remove('hidden');
        }

        const csrf_token = document.getElementById("csrf_token").value ;
        // Delete tag
        async function deleteTag(id) {
            if (confirm('Are you sure you want to delete this tag?')) {
                try {
                    const res = await axios.post('/UknowMvc/tags/delete', {
                        id,
                        csrf_token
                    })
                    console.log(res);
                    if (res.data.message) {
                        showToast(res.data.message);
                        loadTags();
                    } else if (res.data.error) {
                        showToast(res.data.error, 'error');
                    }
                } catch (error) {
                    console.error('Error deleting tag:', error);
                }
            }
        }

        // Form submission
        document.getElementById('tagForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            // Validate inputs
            const inputs = document.querySelectorAll('input[name="tags[]"]');
            let hasEmpty = false;
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('border-red-500');
                    hasEmpty = true;
                } else {
                    input.classList.remove('border-red-500');
                }
            });

            if (hasEmpty) {
                showToast('Please fill all the fields ', 'error');
                return;
            }

            const tagId = document.getElementById('tagId').value;
            const tags = Array.from(inputs).map(input => input.value.trim());

            try {
                const path = tagId ? 'update' : 'create';
                const res = await axios.post(`/UknowMvc/tags/${path}`, {
                    id: tagId,
                    tags,
                    csrf_token
                });
                console.log(res);
                if (res.data.message) {
                    showToast(res.data.message);
                    if (res.data.failed >= 0) {
                        setTimeout(() => {
                            showToast(`${res.data.success} tags created successfully`);
                        }, 2000);
                        setTimeout(() => {
                            showToast(`${res.data.failed} tags failed to create`, res.data.failed > 0 ? "error" : "success");
                        }, 2000);
                    }
                    closeModal();
                    loadTags();
                    document.getElementById('tagForm').reset();
                }
            } catch (error) {
                console.error('Error saving tags:', error);
            }
        });

        // Initial load
        loadTags();
        const userMenu = document.getElementById('user-menu-button');
        userMenu.addEventListener("click", () => {
            document.getElementById("user-dropdown").classList.toggle("hidden")
        })
    </script>
</div>
