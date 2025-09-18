<?php
header('Content-Type: application/json');
$json_path = '../DATA/faqs.json';

if (file_exists($json_path)) {
    echo file_get_contents($json_path);
} else {
    echo json_encode([]);
}
