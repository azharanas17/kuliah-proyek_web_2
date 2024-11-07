<?php
session_start();
if (isset($_SESSION["user_id"])) {
    echo "User ID: " . $_SESSION["user_id"];
} else {
    echo "Sesi tidak ditemukan.";
}
