<?php
session_start();
$url = isset($_SESSION['url-dituju']) ? $_SESSION['url-dituju'] : 'index.php?page=Dashboard';
include 'koneksi.php';

// Ambil data dari form login
$username = $_POST['username'];
$password = $_POST['password'];
$rememberMe = isset($_POST['remember']) ? $_POST['remember'] : '';

// Cek username di database
$stmt = $koneksi->prepare('SELECT * FROM login WHERE username=?');
$stmt->bind_param('s', $username);
$stmt->execute();
$login = $stmt->get_result();
$cek = $login->num_rows;

if ($cek > 0) {
   $data = $login->fetch_assoc();
   // Verifikasi password
   if (password_verify($password, $data['password'])) {
       $_SESSION['url-dituju'] = $url;
       
       // Cek jika password masih default
       if ($password === '123456') {
           $_SESSION['username'] = $username;
           $_SESSION['reset_password'] = true;
           $_SESSION['Messages'] = 'Please change your password for security reasons.';
           $_SESSION['Icon'] = 'warning';
           header('location:changepassword2.php');
           exit;
       }

       // Set session idnik
       $_SESSION['idnik'] = $data['idnik'];
       $idnik = $data['idnik'];
       
       // Ambil data role
       $stmt_roles = $koneksi->prepare('SELECT id_role FROM user_roles WHERE idnik=?');
       $stmt_roles->bind_param('s', $idnik);
       $stmt_roles->execute();
       $result_roles = $stmt_roles->get_result();
       $roles = [];
       while ($rowrole = $result_roles->fetch_assoc()) {
           $roles[] = $rowrole['id_role'];
       }

       // Cek apakah memiliki role yang valid (1 atau 2)
       $hasValidRole = false;
       foreach ($roles as $role) {
           if ($role == 1 || $role == 2) {
               $hasValidRole = true;
               break;
           }
       }

       // Jika tidak memiliki role yang valid
       if (!$hasValidRole) {
           session_regenerate_id(true); // Regenerate session ID untuk keamanan
           unset($_SESSION['idnik']); // Hapus session idnik
           $_SESSION['Messages'] = 'You do not have permission to access this system.';
           $_SESSION['Icon'] = 'error';
           
           // Debug: Cetak nilai session sebelum redirect
           error_log('Role Invalid - Messages: ' . $_SESSION['Messages']);
           error_log('Role Invalid - Icon: ' . $_SESSION['Icon']);
           
           session_write_close(); // Pastikan session tersimpan
           header('location:login.php');
           exit;
       }

       // Jika role valid, lanjutkan dengan login API
       $api_url = 'https://maa-api.maagroup.co.id/api/auth/login';
       $curl = curl_init();
       curl_setopt_array($curl, [
           CURLOPT_URL => $api_url,
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_POST => true,
           CURLOPT_HTTPHEADER => [
               'Content-Type: application/json',
           ],
           CURLOPT_POSTFIELDS => json_encode([
               'username' => $username,
               'password' => $password,
           ]),
       ]);
       $response = curl_exec($curl);
       $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
       curl_close($curl);

       if ($http_code === 200) {
           $tokens = json_decode($response, true);
           
           // Set session role setelah validasi berhasil
           $_SESSION['role'] = $roles;
           
           // Ambil data user
           $stmt_user = $koneksi->prepare('SELECT nama FROM user WHERE idnik=?');
           $stmt_user->bind_param('s', $idnik);
           $stmt_user->execute();
           $user_query = $stmt_user->get_result();
           $user_data = $user_query->fetch_assoc();
           $user_name = $user_data['nama'];

           // Set cookie jika remember me dicentang
           if ($rememberMe === 'yes') {
               $cookie_lifetime = 30 * 24 * 60 * 60; // 30 hari
               setcookie('username', $username, time() + $cookie_lifetime);
           }

           $_SESSION['Messages'] = 'Login successful! Welcome, ' . htmlspecialchars($user_name) . '.';
           $_SESSION['Icon'] = 'success';

           // Generate script untuk menyimpan token ke sessionStorage
           echo "
           <script>
               sessionStorage.setItem('jwt_token', '" . $tokens['token'] . "');
               sessionStorage.setItem('refresh_token', '" . $tokens['refreshToken'] . "');
               window.location.href = '$url';
           </script>";
           exit;
       } else {
           error_log("Failed to get JWT token. HTTP Code: $http_code, Response: $response");
           $_SESSION['Messages'] = 'Login successful, but token generation failed. Please try again later.';
           $_SESSION['Icon'] = 'warning';
           header('location:login.php');
           exit;
       }
   } else {
       $_SESSION['Messages'] = 'Username or password is incorrect.';
       $_SESSION['Icon'] = 'error';
       header('location:login.php');
       exit;
   }
} else {
   $_SESSION['Messages'] = 'Username or password is incorrect.';
   $_SESSION['Icon'] = 'error';
   header('location:login.php');
   exit;
}
?>