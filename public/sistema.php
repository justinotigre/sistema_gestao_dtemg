<?php
require_once __DIR__ . '/../App/auth.php';
require_login();
header('Location: ' . app_url('modulos/dashboard.php'));
exit;
