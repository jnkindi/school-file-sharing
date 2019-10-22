<?php
include("config/config.php");
session_start(); // Starting Session
// Storing Session
if (!isset($_SESSION['logged_user_info'])) {
	header('Location: index.php'); // Redirecting To Home Page
} else {
	if (isset($_SESSION['logged_user_info'])) {
		$user_check = $_SESSION['logged_user_info'];
		// SQL Query To Fetch Complete Information Of User
		$query = "SELECT * FROM users WHERE id = '$user_check'";
		$query = $conn->query($query);
		$arr = $query->fetch_array();
		$user_id = $arr['id'];
		$names = $arr['names'];
		$phone = $arr['phone'];
		$user_photo = ($arr['photo'] == '') ? 'assets/img/user.jpg' : 'uploads/' . $arr['photo'];
		$user_role = $arr['role'];
	} else {
		header('Location: logout.php');
	}
}
if (!isset($user_id)) {
	header('Location: index.php');
}
