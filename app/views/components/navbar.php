<?php
    $role = $_SESSION["user"]["role_id"] ?? null;
?>

<header class="main-header">
    <nav class="nav-container">
        <div class="nav-content">
            <!-- Logo -->
            <div class="logo-container">
                <a href=<?= URLROOT ?> class="logo-link">
                    <i data-lucide="graduation-cap" class="logo-icon"></i>
                    <span class="logo-text">
                        <span class="logo-u">You</span>demy
                    </span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="desktop-nav">
                <a href=<?= URLROOT ?> class="nav-link">Home</a>
                
                <?php if ($role && $_SESSION['user']["role_id"] == 3): ?>
                    <a href="<?= URLROOT . '/admin/dashboard' ?>" class="nav-link">Dashboard</a>
                <?php endif; ?>
                
                <?php if ($role && $_SESSION['user']["role_id"] == 2): ?>
                    <a href="<?= URLROOT . '/teacher/dashboard' ?>" class="nav-link">Dashboard</a>
                <?php endif; ?>
                
                <?php if ($role && $_SESSION['user']["role_id"] == 1) : ?>
                    <a href="<?= URLROOT . '/enrollments' ?>" class="nav-link">My courses</a>
                <?php endif; ?>
                
                <a href="<?= URLROOT . '/courses' ?>" class="nav-link">Courses</a>
            </div>

            <!-- Auth Buttons -->
            <?php if (!$role) : ?>
                <div class="auth-buttons">
                    <a href="<?= URLROOT . '/users/login' ?>" class="login-link">Login</a>
                    <a href="<?= URLROOT . '/users/register' ?>" class="signup-btn">Sign Up</a>
                </div>
            <?php else : ?>
                <div class="user-menu-container">
                    <button type="button" id="user-menu-button" class="user-menu-btn" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                        <span class="sr-only">Open user menu</span>
                        <img class="user-avatar" src="" alt="user photo">
                    </button>
                    <!-- Dropdown menu -->
                    <div class="user-dropdown hidden" id="user-dropdown">
                        <div class="user-info">
                            <span class="user-name"><?= $_SESSION['user']["username"] ?></span>
                            <span class="user-email"><?= $_SESSION['user']["email"] ?></span>
                        </div>
                        <ul class="dropdown-menu">
                            <form action=<?= URLROOT . "/users/signout" ?> method="POST">
                                <input type="hidden" name="signout">
                                <button type="submit" name="signout" class="signout-btn">Sign out</button>
                            </form>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Mobile Menu Button -->
            <div class="mobile-menu-container">
                <button class="mobile-menu-button">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu hidden">
            <div class="mobile-menu-content">
                <a href="#" class="mobile-nav-link">Home</a>
                <a href="#" class="mobile-nav-link">Courses</a>
                <a href="#" class="mobile-nav-link">About</a>
                <a href="#" class="mobile-nav-link">Contact</a>
                <div class="mobile-auth">
                    <a href="#" class="mobile-login">Login</a>
                    <a href="#" class="mobile-signup">Sign Up</a>
                </div>
            </div>
        </div>
    </nav>
</header>

<div id="toast-container" class="toast-container"></div>

<style>
.main-header {
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 50;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(8px);
    border-bottom: 1px solid rgba(229, 231, 235, 0.5);
}

.nav-container {
    max-width: 80rem;
    margin: 0 auto;
    padding: 0 1rem;
}

.nav-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 4rem;
}

.logo-container {
    flex-shrink: 0;
}

.logo-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
}

.logo-icon {
    height: 2rem;
    width: 2rem;
    color: blue;
}

.logo-text {
    font-size: 1.5rem;
    font-weight: bold;
    background: linear-gradient(to right, blue, #10b981);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.logo-u {
    color: black;
}

.desktop-nav {
    display: none;
    align-items: center;
    gap: 2rem;
}

.nav-link {
    color: #374151;
    text-decoration: none;
    transition: color 0.2s;
    position: relative;
}

.nav-link:after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    height: 2px;
    width: 0;
    background: blue;
    transition: width 0.2s;
}

.nav-link:hover {
    color: blue;
}

.nav-link:hover:after {
    width: 100%;
}

.auth-buttons {
    display: none;
    align-items: center;
    gap: 1rem;
}

.login-link {
    color: #374151;
    text-decoration: none;
    transition: color 0.2s;
}

.login-link:hover {
    color: blue;
}

.signup-btn {
    background: blue;
    color: white;
    padding: 0.5rem 1.5rem;
    border-radius: 9999px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
    box-shadow: 0 4px 6px rgba(5, 150, 105, 0.2);
}

.signup-btn:hover {
    background: #047857;
    transform: scale(1.05);
    box-shadow: 0 4px 6px rgba(5, 150, 105, 0.4);
}

.user-menu-container {
    position: relative;
}

.user-menu-btn {
    display: flex;
    background: #1f2937;
    border-radius: 9999px;
    cursor: pointer;
}

.user-avatar {
    width: 2rem;
    height: 2rem;
    border-radius: 9999px;
}

.user-dropdown {
    position: absolute;
    top: 2.5rem;
    right: 0;
    margin-top: 1rem;
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    z-index: 50;
}

.user-info {
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.user-name {
    display: block;
    font-size: 0.875rem;
    color: #111827;
}

.user-email {
    display: block;
    font-size: 0.875rem;
    color: #6b7280;
    overflow: hidden;
    text-overflow: ellipsis;
}

.dropdown-menu {
    padding: 0.5rem 0;
}

.signout-btn {
    display: block;
    width: 100%;
    padding: 0.5rem 1rem;
    text-align: left;
    font-size: 0.875rem;
    color: #374151;
    background: none;
    border: none;
    cursor: pointer;
}

.signout-btn:hover {
    background: #f3f4f6;
}

.mobile-menu-container {
    display: block;
}

.mobile-menu-button {
    padding: 0.5rem;
    color: #374151;
    border: none;
    background: none;
    cursor: pointer;
}

.mobile-menu-button:hover {
    color: blue;
}

.mobile-menu {
    padding: 0.5rem;
}

.mobile-menu-content {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.mobile-nav-link {
    display: block;
    padding: 0.75rem;
    color: #374151;
    text-decoration: none;
    border-radius: 0.375rem;
    transition: all 0.2s;
}

.mobile-nav-link:hover {
    color: blue;
    background: #f3f4f6;
}

.mobile-auth {
    padding-top: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.mobile-login {
    color: #374151;
    text-decoration: none;
    padding: 0.75rem;
}

.mobile-signup {
    background: blue;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 9999px;
    font-weight: 500;
    text-align: center;
    text-decoration: none;
    box-shadow: 0 4px 6px rgba(5, 150, 105, 0.2);
}

.toast-container {
    position: fixed;
    top: 1.25rem;
    right: 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    z-index: 50;
}

.hidden {
    display: none;
}

@media (min-width: 768px) {
    .desktop-nav {
        display: flex;
    }
    
    .auth-buttons {
        display: flex;
    }
    
    .mobile-menu-container {
        display: none;
    }
}
</style>

<script>
    const userMenu = document.getElementById('user-menu-button');
    userMenu.addEventListener("click", () => {
        document.getElementById("user-dropdown").classList.toggle("hidden")
    })
</script>