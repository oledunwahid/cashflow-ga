<?php session_start();
if (isset($_SESSION['idnik']) && $_SESSION['idnik']) {
    // Jika sudah login, arahkan ke halaman akses (atau halaman home)
    header("location:index.php?page=Dashboard");
    exit();
}

if (isset($_SESSION['reset_password']) && $_SESSION['reset_password']) {
    // Jika sudah login, arahkan ke halaman akses (atau halaman home)
    header("location:changepassword2.php");
    exit();
}
?>
<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>Sign Out | EIP MAA Group</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Employee Information Portal" name="description" />
    <meta content="Mineral Alam Abadi" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/logo.svg">

    <!-- Layout config Js -->
    <script src="assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />

    <!-- Add flags icon CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.6.6/css/flag-icons.min.css">

    <!-- Custom Styles for Modern UI -->
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Inter', sans-serif;
        }

        .logout-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Using existing auth-one-bg classes from your current theme */
        .auth-one-bg-position {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
        }

        .logout-card {
            width: 100%;
            max-width: 420px;
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            z-index: 1;
            text-align: center;
        }

        .logout-logo {
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .logout-logo img {
            height: 30px;
            width: auto;
            max-width: 100%;
        }

        .logout-title {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #fff;
        }

        .logout-title h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .logout-title p {
            font-size: 0.95rem;
            opacity: 0.9;
            color: rgba(255, 255, 255, 0.8);
        }

        .logout-icon {
            margin: 1rem auto;
            width: 100px;
            height: 100px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #e52222;
        }

        /* Login button */
        .login-btn {
            background: linear-gradient(to right, #890707, #c61111, #e52222) !important;
            border: none;
            color: white;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            margin-top: 1rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(137, 7, 7, 0.3);
            display: inline-block;
            text-decoration: none;
            text-align: center;
            font-size: 1rem;
        }

        /* Efek hover untuk button */
        .login-btn:hover {
            background: linear-gradient(to right, #750606, #b30f0f, #d71f1f) !important;
            box-shadow: 0 5px 20px rgba(137, 7, 7, 0.5);
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }

        /* Efek aktif saat diklik */
        .login-btn:active {
            transform: translateY(1px);
            box-shadow: 0 2px 10px rgba(137, 7, 7, 0.4);
        }

        /* Tambahkan efek glossy dengan pseudo-element */
        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, 0.3) 50%,
                    rgba(255, 255, 255, 0) 100%);
            transform: skewX(-25deg);
            transition: all 0.75s;
        }

        /* Animasi glossy saat hover */
        .login-btn:hover::before {
            left: 100%;
        }

        .logout-footer {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
            margin-top: 1.5rem;
        }

        .heart-icon {
            color: #e74c3c;
            display: inline-block;
        }

        /* Force override any bg-overlay styles */
        .bg-overlay {
            position: absolute !important;
            height: 100% !important;
            width: 100% !important;
            right: 0 !important;
            bottom: 0 !important;
            left: 0 !important;
            top: 0 !important;
            opacity: 0.7 !important;
            background-color: rgba(0, 0, 0, 0.7) !important;
            /* Dark blue overlay */
            background-image: none !important;
            /* Remove any background image */
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            z-index: -1 !important;
        }

        /* Additional style to ensure nothing else overrides */
        html,
        body,
        .logout-container,
        .auth-one-bg-position {
            background-color: transparent !important;
        }

        /* Add an inline style directly to the element */
        #auth-particles::after {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5) !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            z-index: -1;
            pointer-events: none;
        }

        /* Remove the shape from the original theme */
        .shape {
            display: none;
        }

        /* Language switcher styling */
        .language-switcher {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
        }

        .language-btn {
            background-color: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.3s;
        }

        .language-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .language-dropdown {
            display: none;
            position: absolute;
            background-color: rgba(35, 35, 40, 0.95);
            min-width: 120px;
            border-radius: 6px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            right: 0;
            z-index: 1;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .language-dropdown a {
            color: rgba(255, 255, 255, 0.8);
            padding: 10px 12px;
            text-decoration: none;
            display: block;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .language-dropdown a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .show {
            display: block;
        }

        .animation-ctn {
            text-align: center;
            margin-top: 1rem;
        }

        @-webkit-keyframes checkmark {
            0% {
                stroke-dashoffset: 100px
            }

            100% {
                stroke-dashoffset: 0px
            }
        }

        @-ms-keyframes checkmark {
            0% {
                stroke-dashoffset: 100px
            }

            100% {
                stroke-dashoffset: 0px
            }
        }

        @keyframes checkmark {
            0% {
                stroke-dashoffset: 100px
            }

            100% {
                stroke-dashoffset: 0px
            }
        }

        @-webkit-keyframes checkmark-circle {
            0% {
                stroke-dashoffset: 480px
            }

            100% {
                stroke-dashoffset: 0px
            }
        }

        @-ms-keyframes checkmark-circle {
            0% {
                stroke-dashoffset: 480px
            }

            100% {
                stroke-dashoffset: 0px
            }
        }

        @keyframes checkmark-circle {
            0% {
                stroke-dashoffset: 480px
            }

            100% {
                stroke-dashoffset: 0px
            }
        }

        .inlinesvg .svg svg {
            display: inline
        }

        .icon--order-success svg path {
            -webkit-animation: checkmark 0.5s ease-in-out 0.7s backwards;
            animation: checkmark 0.5s ease-in-out 0.7s backwards
        }

        .icon--order-success svg circle {
            -webkit-animation: checkmark-circle 0.6s ease-in-out backwards;
            animation: checkmark-circle 0.6s ease-in-out backwards
        }
    </style>
</head>

<body>
    <div class="logout-container">
        <!-- Language Switcher -->
        <?php
        // Get current URL without language parameter
        $queryString = $_SERVER['QUERY_STRING'] ?? '';
        parse_str($queryString, $params);
        unset($params['lang']); // Remove existing lang parameter

        // Create new URLs with language parameter
        $baseUrl = basename($_SERVER['PHP_SELF']); // Get current filename (e.g., "index.php")
        $indonesiaUrl = $baseUrl . (empty($params) ? '?lang=indonesia' : '?' . http_build_query($params) . '&lang=indonesia');
        $englishUrl = $baseUrl . (empty($params) ? '?lang=english' : '?' . http_build_query($params) . '&lang=english');

        // Determine current language
        $language = isset($_GET['lang']) ? $_GET['lang'] : 'english'; // Default to English
        ?>

        <div class="language-switcher">
            <button onclick="toggleLanguageDropdown()" class="language-btn">
                <?php echo $language === 'indonesia' ? '<i class="fi fi-id me-1"></i> ID' : '<i class="fi fi-us me-1"></i> EN'; ?>
                <i class="ri-arrow-down-s-line"></i>
            </button>
            <div id="languageDropdown" class="language-dropdown">
                <a href="<?= $indonesiaUrl ?>" <?= $language === 'indonesia' ? 'class="active"' : '' ?>>
                    <i class="fi fi-id me-2"></i> Indonesia
                </a>
                <a href="<?= $englishUrl ?>" <?= $language === 'english' ? 'class="active"' : '' ?>>
                    <i class="fi fi-us me-2"></i> English
                </a>
            </div>
        </div>

        <!-- Auth background with particles -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>
        </div>

        <!-- Logout Card -->
        <div class="logout-card">
            <!-- Logo -->
            <div class="logout-logo">
                <img src="assets/images/logo_MAAA.png" alt="Mineral Alam Abadi Logo">
            </div>

            <!-- Animated Checkmark Icon -->
            <div class="animation-ctn">
                <div class="icon icon--order-success svg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80">
                        <circle class="circle" cx="40" cy="40" r="38" stroke="#D60303" stroke-width="2" fill="none" />
                        <polyline class="path" stroke="#D60303" stroke-width="4" points="24,40 36,52 56,32" fill="none" />
                    </svg>
                </div>
            </div>

            <!-- Logout Message -->
            <div class="logout-title">
                <h3 class="text-white">You are Logged Out</h3>
                <p>Thank you for using <span class="fw-semibold">Petty Cash</span> Facilities</p>
            </div>

            <!-- Login button -->
            <a href="login.php" class="login-btn">Sign In</a>
        </div>

        <!-- Footer -->
        <div class="logout-footer">
            &copy; <script>
                document.write(new Date().getFullYear())
            </script>
            Petty Cash <span class="heart-icon">❤</span> by Mineral Alam Abadi
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- particles js -->
    <script src="assets/libs/particles.js/particles.js"></script>
    <script src="assets/js/pages/particles.app.js"></script>

    <!-- Language dropdown script -->
    <script>
        // Language dropdown toggle
        function toggleLanguageDropdown() {
            document.getElementById("languageDropdown").classList.toggle("show");
        }

        // Close the dropdown if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.language-btn') && !event.target.matches('.language-btn i')) {
                var dropdowns = document.getElementsByClassName("language-dropdown");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>

</html>