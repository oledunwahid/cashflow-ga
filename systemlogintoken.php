<?php
session_start();
include 'koneksi.php';

// Sanitize input
$token = isset($_POST['token']) ? trim($_POST['token']) : '';
$url = isset($_SESSION['url-dituju']) ? $_SESSION['url-dituju'] : 'index.php?page=Dashboard';

if (empty($token)) {
    $_SESSION['Messages'] = 'Token is required.';
    $_SESSION['Icon'] = 'error';
    header('location:logintoken.php');
    exit;
}

try {
    // Use prepared statement to prevent SQL injection
    $stmt = $koneksi->prepare('SELECT * FROM login WHERE token = ? AND status_login = ? AND token_expiry > NOW()');
    $status = 'Aktif';
    $stmt->bind_param('ss', $token, $status);
    $stmt->execute();
    $login = $stmt->get_result();
    $cek = $login->num_rows;

    if ($cek > 0) {
        $data = $login->fetch_assoc();
        $idnik = $data['idnik'];

        // Verify token hasn't been used before (prevent replay attacks)
        if ($data['token_used'] == 1) {
            throw new Exception('Token has already been used.');
        }

        // Mark token as used
        $update_stmt = $koneksi->prepare('UPDATE login SET token_used = 1 WHERE idnik = ?');
        $update_stmt->bind_param('s', $idnik);
        $update_stmt->execute();

        // Set session data
        $_SESSION['idnik'] = $idnik;
        $_SESSION['url-dituju'] = $url;

        // Fetch user roles using prepared statement
        $stmt_roles = $koneksi->prepare('SELECT id_role FROM user_roles WHERE idnik = ?');
        $stmt_roles->bind_param('s', $idnik);
        $stmt_roles->execute();
        $result_roles = $stmt_roles->get_result();
        
        $roles = [];
        while ($rowrole = $result_roles->fetch_assoc()) {
            $roles[] = $rowrole['id_role'];
        }

        // Validate roles (assuming 1 and 2 are valid roles as in your original system)
        $hasValidRole = false;
        foreach ($roles as $role) {
            if ($role == 1 || $role == 2) {
                $hasValidRole = true;
                break;
            }
        }

        if (!$hasValidRole) {
            throw new Exception('You do not have permission to access this system.');
        }

        // Get user data
        $stmt_user = $koneksi->prepare('SELECT nama FROM user WHERE idnik = ?');
        $stmt_user->bind_param('s', $idnik);
        $stmt_user->execute();
        $user_data = $stmt_user->get_result()->fetch_assoc();

        // Set session data
        $_SESSION['role'] = $roles;
        $_SESSION['Messages'] = 'Login successful! Welcome, ' . htmlspecialchars($user_data['nama']) . '.';
        $_SESSION['Icon'] = 'success';

        // Generate new JWT token for API access
        $api_url = 'https://maa-api.maagroup.co.id/api/auth/token';
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode(['token' => $token])
        ]);
        
        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($http_code === 200) {
            $tokens = json_decode($response, true);
            // Store tokens in sessionStorage using JavaScript
            echo "
            <script>
                sessionStorage.setItem('jwt_token', '" . $tokens['token'] . "');
                sessionStorage.setItem('refresh_token', '" . $tokens['refreshToken'] . "');
                window.location.href = '" . htmlspecialchars($url) . "';
            </script>";
            exit;
        } else {
            throw new Exception('Failed to generate API token.');
        }
    } else {
        throw new Exception('Invalid token or inactive account.');
    }
} catch (Exception $e) {
    error_log('Login error: ' . $e->getMessage());
    $_SESSION['Messages'] = $e->getMessage();
    $_SESSION['Icon'] = 'error';
    header('location:logintoken.php');
    exit;
}
?>