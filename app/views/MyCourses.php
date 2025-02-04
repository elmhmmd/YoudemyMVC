<?php

require_once APPROOT . "/views/components/navbar.php";

?>
<main class="my-20">
    <div class="container">
        <h1 class="font-bold text-2xl">My Courses</h1>
        <div class="container grid grid-cols-1 my-20 md:grid-cols-2 mt-20 lg:grid-cols-3 gap-8">
            <?php foreach ($data as $course) : ?>
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                    <div class="relative">
                        <img
                            src='<?= URLROOT . "/public/imgs/uploads/" . $course["thumbnail"]; ?>'
                            class="w-full h-80 aspect-video object-cover" />
                    </div>

                    <div class="p-6">

                        <div class="flex flex-wrap gap-2 mb-4">
                            <?php foreach (explode(",", $course["tags"]) as $tag) : ?>
                                <span
                                    class="px-3 py-1 bg-blue-50 text-blue-600 text-xs font-medium rounded-full">
                                    <?= $tag ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2"><?= $course["title"]; ?></h3>
                        <div class="flex items-end justify-between w-full mt-5">
                            <div class="flex items-center">
                                <div>
                                    <p class="text-xs text-gray-500"><?= $course["user_name"] ?></p>
                                    <p class="text-xs font-medium text-gray-900"><?= $course["user_email"] ?></p>
                                </div>
                            </div>
                            <a href="<?= URLROOT . '/courses/details/' . $course["id"] ?>">
                                <button class="flex items-center text-blue-500 hover:text-blue-600 text-sm font-medium">
                                    View Course
                                    <i class="fa-solid fa-chevron-right  ml-1"></i>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</main>