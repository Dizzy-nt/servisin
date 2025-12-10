<?php
session_start();
session_unset();      // hapus semua variable di session
session_destroy();    // hancurkan session
header("Location: login.php"); // kembali ke halaman login
exit;
