<?php

# Post data
function post($name, $default = null) {
    
    if (isset($_POST[$name])) {
        return $_POST[$name];   
    }
    
    return $default;
}

# Database details
$database = post('database');
$username = post('username');
$password = post('password');

# Database connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=$database", $username, $password);
} catch (Exception $e) {
    die('Could not connect to database');   
}
    
# Read file
$data = file_get_contents('database.sql');

if (!$data) {
    die('Could not found database file');   
}

# Generate key
function generate($chars = 'abcdefghijklmnopqrstuvwxyz') {

    $result = '';
    
    for ($i=0; $i<=16; $i++) {
        $result .= $chars[mt_rand(0, strlen($chars)-1)];   
    }
    
    return $result;
}

# Generate key
$key = generate();

# User details
$email = post('email');
$pass = post('pass');

# Hash password
$pass = hash('sha1', $pass . 'hgjfht76utjgih98');

# Replace details
$data = str_replace(['@email', '@pass', '@key'], [$email, $pass, $key], $data);

# Execute queries
$lines = explode(';', $data);

foreach ($lines as $line) {
    
    $line = trim($line);
    
    if ($line) {
        $pdo->exec($line);   
    }
}

# Read config file
$data = file_get_contents('config.php');

if (!$data) {
    die('Could not found config file');   
}

# Replace details
$data = str_replace(['@database', '@username', '@password'], [$database, $username, $password], $data);

# File path
$file = '../../../config/db.php';

# Write file
file_put_contents($file, $data);

# Complete
header('Location: ../complete.php');