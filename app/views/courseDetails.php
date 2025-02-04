<?php
require_once APPROOT . "/views/components/navbar.php";

?>


<div class="container mx-auto p-6 space-y-8 mt-20">
    <!-- Course Header Section -->
    <div class="space-y-4">
        <h1 class="text-3xl font-bold text-gray-900"><?= $data->getTitle(); ?></h1>

        <div class="flex items-center space-x-4 text-gray-600">
            <span class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <?= $data->getUserName(); ?>
            </span>
            <span>|</span>
            <span><?= $data->getCategoryName(); ?></span>
        </div>

        <!-- Tags -->
        <div class="flex flex-wrap gap-2">
            <?php foreach ($data->getTags() as $tag): ?>
                <span class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-full"><?= $tag->getName() ?></span>
            <?php endforeach; ?>
        </div>

        <p class="text-gray-700">
            <?= $data->getDescription() ?>
        </p>
    </div>

    <?php if ($data->getVideo() || $data->getDocument()) : ?>
        <?php if ($data->getVideo()): ?>
            <video controls class="max-w-2xl mx-auto aspect-video">
                <source src="../assets/uploads/<?= $data->getVideo() ?>" type="video/mp4">
                <source src="mov_bbb.ogg" type="video/ogg">
                Your browser does not support HTML video.
            </video>
        <?php elseif ($data->getDocument()) : ?>
            <div class="container">
                <?= $data->getDocument() ?>
            </div>
        <?php endif; ?>
    <?php else : ?>
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <div class="mx-auto w-16 h-16 mb-4">
                <svg fill="none" stroke="currentColor" class="text-gray-400" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h2 class="text-xl font-semibold mb-2">Enroll to Access Course Content</h2>
            <p class="text-gray-600 mb-4">Join this course to access all lessons and downloadable resources</p>
            <form id="enrolle_form" action=<?= URLROOT . "/courses/enrolle/" . $data->getId() ?> method="POST">
                <input type="hidden" id="course_id" name="courseId" value="<?= $data->getId() ?>">
                <button
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Enroll Now
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>
<script>
    const enrolle_form = document.getElementById("enrolle_form");
    const courseId = document.getElementById("course_id");
    const userId = document.getElementById("user_id");
    // enrolle_form.addEventListener("submit", async (e) => {
    //     console.log("clicked");
    //     const res = await axios.post("/UknowMvc/courses/enrolle/", {})
    // })
</script>
<?php
