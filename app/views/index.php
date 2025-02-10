<?php require_once APPROOT . "/views/components/navbar.php"; ?>

<main class="main-content">
    <section class="hero">
        <div class="hero-content">
            <h1>
                <span class="gradient-text">Master Your Skills Today</span>
            </h1>
            <p>Discover expert-led courses designed to transform your career.</p>
            <div class="hero-buttons">
                <a href="<?= URLROOT ?>/courses" class="primary-btn">Browse Courses</a>
                <a href="<?= URLROOT ?>/users/register" class="secondary-btn">Get Started</a>
            </div>
        </div>
    </section>

    <section class="courses-section">
        <div class="courses-header">
            <h2>Featured Courses</h2>
            <a href="<?= URLROOT ?>/courses" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="courses-grid">
            <?php foreach ($data as $course): ?>
                <div class="course-card">
                    <div class="course-image">
                        <img src='<?= URLROOT . "/public/imgs/uploads/" . $course->getThumbnail(); ?>'
                             alt="<?= $course->getTitle(); ?>" />
                    </div>
                    <div class="course-content">
                        <div class="course-tags">
                            <?php foreach ($course->getTags() as $tag): ?>
                                <span class="tag"><?= $tag->getName(); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <h3><?= $course->getTitle(); ?></h3>
                        <div class="course-meta">
                            <div class="instructor">
                                <p class="instructor-name"><?= $course->getUserName(); ?></p>
                                <p class="instructor-email"><?= $course->getUserEmail(); ?></p>
                            </div>
                            <a href="<?= URLROOT . '/courses/details/' . $course->getId() ?>" 
                               class="view-course">View Course →</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<style>
.main-content {
    min-height: 100vh;
    padding-top: 4rem;
    background: linear-gradient(to bottom, #f8fafc, #f1f5f9);
}

.hero {
    padding: 6rem 1rem;
    text-align: center;
}

.hero-content {
    max-width: 800px;
    margin: 0 auto;
}

.gradient-text {
    background: linear-gradient(to right, blue, #10b981);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    font-size: 3.5rem;
    font-weight: bold;
    line-height: 1.2;
}

.hero p {
    color: #4b5563;
    font-size: 1.25rem;
    margin: 1.5rem 0;
}

.hero-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.primary-btn, .secondary-btn {
    padding: 0.75rem 2rem;
    border-radius: 0.5rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
}

.primary-btn {
    background: blue;
    color: white;
    box-shadow: 0 4px 6px rgba(5, 150, 105, 0.2);
}

.secondary-btn {
    border: 2px solid blue;
    color: blue;
}

.primary-btn:hover { background: #047857; }
.secondary-btn:hover { background: rgba(5, 150, 105, 0.1); }

.courses-section {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.courses-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
}

.course-card {
    background: white;
    border-radius: 1rem;
    overflow: hidden;
    border: 1px solid rgba(0, 0, 0, 0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.course-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1);
}

.course-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.course-content {
    padding: 1.5rem;
}

.course-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.tag {
    background: #f0f9ff;
    color: blue;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
}

.course-content h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 1rem;
}

.course-meta {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
}

.instructor {
    font-size: 0.875rem;
}

.instructor-name {
    color: #374151;
    font-weight: 500;
}

.instructor-email {
    color: #6b7280;
    font-size: 0.875rem;
}

.view-course {
    color: blue;
    text-decoration: none;
    font-weight: 500;
}

.view-all {
    color: blue;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

@media (max-width: 768px) {
    .gradient-text {
        font-size: 2.5rem;
    }
    
    .hero-buttons {
        flex-direction: column;
    }
    
    .courses-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
}
</style>