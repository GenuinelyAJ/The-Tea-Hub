<?php
require_once 'assets/php/functions.php';

if (isset($_GET['newfp'])) {
    unset($_SESSION['auth_temp']);
    unset($_SESSION['forgot_email']);
    unset($_SESSION['forgot_code']);
}

if (isset($_SESSION['Auth'])) {
    $user = getUser($_SESSION['userdata']['id']);
    $posts = filterPosts();
    $follow_suggestions = filterFollowSuggestion();
}

$pagecount = count($_GET);

// Manage pages
if (isset($_SESSION['Auth']) && $user['ac_status'] == 1 && !$pagecount) {
    showPage('header', ['TTH - Home']);
    showPage('navbar');
    showPage('wall');
} elseif (isset($_SESSION['Auth']) && $user['ac_status'] == 0 && !$pagecount) {
    showPage('header', ['TTH - Verification']);
    showPage('verify_email');
} elseif (isset($_SESSION['Auth']) && isset($_GET['editprofile']) && $user['ac_status'] == 1) {
    showPage('header', ['TTH - Edit Profile']);
    showPage('navbar');
    showPage('edit_profile');
} elseif (isset($_SESSION['Auth']) && isset($_GET['u']) && $user['ac_status'] == 1) {
    $profile = getUserByUsername($_GET['u']);
    if (!$profile) {
        showPage('header', ['TTH - User Not Found']);
        showPage('navbar');
        showPage('user_not_found');
    } else {
        $profile_post = getPostById($profile['id']);
        showPage('header', ['TTH' => $profile['first_name'] . ' ' . $profile['last_name']]);
        showPage('navbar');
        showPage('profile');
    }
} elseif (isset($_GET['signup'])) {
    showPage('header', ['page_title' => 'TTH - Sign Up']);
    showPage('signup');
} elseif (isset($_GET['login'])) {
    showPage('header', ['page_title' => 'TTH - Log In']);
    showPage('login');
} elseif (isset($_GET['forgotpassword'])) {
    showPage('header', ['page_title' => 'TTH - Forgot Password']);
    showPage('forgot_password');
} else {
    if (isset($_SESSION['Auth']) && $user['ac_status'] == 1) {
        showPage('header', ['page_title' => 'Home']);
        showPage('navbar');
        showPage('wall');
    } elseif (isset($_SESSION['Auth']) && $user['ac_status'] == 0) {
        showPage('header', ['TTH - Verification']);
        showPage('verify_email');
    } else {
        showPage('header', ['page_title' => 'TTH - Log In']);
        showPage('login');
    }
}

showPage('footer');
unset($_SESSION['error']);
unset($_SESSION['formdata']);








