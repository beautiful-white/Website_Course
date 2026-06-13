<?php
session_start();

$conn = mysqli_connect("127.0.0.1", "root", "BxPMZbhvJmRZT6t57UWOh6R6o9aO6p4C");
if (!$conn) die("Ошибка подключения к базе данных: " . mysqli_connect_error());

$result = mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS blog");
if (!$result) die("Ошибка создания базы данных: " . mysqli_error($conn));

$result = mysqli_select_db($conn, "blog");
if (!$result) die("Ошибка выбора базы данных: " . mysqli_error($conn));

$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255),
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$result = mysqli_query($conn, $sql);
if (!$result) die("Ошибка создания таблицы users: " . mysqli_error($conn));

$result = mysqli_query($conn, "ALTER TABLE users ADD COLUMN IF NOT EXISTS avatar VARCHAR(255) DEFAULT NULL");
if (!$result) die("Ошибка добавления колонки avatar: " . mysqli_error($conn));

$sql = "CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$result = mysqli_query($conn, $sql);
if (!$result) die("Ошибка создания таблицы posts: " . mysqli_error($conn));