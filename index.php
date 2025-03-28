<?php
include "roleseason.php";
include "koneksi.php";

?>
<?php $tgl = date('Y-m-d'); ?>

<?php
$idnik = $_SESSION['idnik'];
$sqllogin = mysqli_query($koneksi, "SELECT * FROM  user WHERE idnik='$idnik'");
$rowlogin = mysqli_fetch_assoc($sqllogin);
		
$niklogin = $idnik ;
$namalogin = $rowlogin['nama'];
$foto_profile = $rowlogin['file_foto'];
$divisilogin = $rowlogin['divisi'];

$query_admin = "SELECT ur.id_role 
                FROM user_roles ur 
                WHERE ur.idnik = '$niklogin'";
$result_admin = mysqli_query($koneksi, $query_admin);
if (!$result_admin) {
    die("Error checking admin status: " . mysqli_error($koneksi));
}
$user_admin = mysqli_fetch_assoc($result_admin);
$is_admin = in_array($user_admin['id_role'], [1, 12]); 


date_default_timezone_set('Asia/Jakarta'); 
?>

<?php
function rupiah($angka){
	$hasil_rupiah = "Rp " . number_format($angka,0,',','.');
	return $hasil_rupiah;
}

function fsize($file){
    $a = array("B", "KB", "MB", "GB", "TB", "PB");
    $pos = 0;
    $size = filesize($file);
    while ($size >= 1024)
    {
        $size /= 1024;
        $pos++;
    }
    return round($size,2)." ".$a[$pos];
}
?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>EIP | Mineral Alam Abadi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link href="assets/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/swiper/swiper-bundle.min.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/layout.js"></script>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    
</head>

<body>
    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php include "layout/page-topbar.php" ?>
        <?php include "layout/menu.php" ?>
        <div class="vertical-overlay"></div>
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0"><?php echo $page = $_GET['page']; ?></h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="index.php?page=Dashboard">Dashboards</a></li>
                                        <li class="breadcrumb-item active"><?php echo $page = $_GET['page']; ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php include "akses_menu.php" ?>
                </div>
            </div>
            <?php include "layout/footer.php" ?>
        </div>
    </div>

    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>

    <!-- Floating Chat Button -->
    

    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/libs/jsvectormap/js/jsvectormap.min.js"></script>
    <script src="assets/libs/jsvectormap/maps/world-merc.js"></script>
    <script src="assets/libs/swiper/swiper-bundle.min.js"></script>
    <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="assets/libs/prismjs/prism.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/app.js"></script>
    
</body>
</html>
