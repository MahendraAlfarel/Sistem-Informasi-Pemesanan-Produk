<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$endpoint = $_GET['endpoint'] ?? '';
$id = $_GET['id'] ?? '';

// Fungsi untuk ambil dan cari berdasarkan ID
function fetchAndFilter($url, $id) {
    $response = file_get_contents($url);
    if ($response === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Gagal mengambil data dari API']);
        exit;
    }

    $data = json_decode($response, true);
    $filtered = array_filter($data, fn($item) => $item['id'] == $id);
    $filtered = array_values($filtered);
    echo json_encode(['data' => $filtered[0] ?? ['name' => 'Nama tidak ditemukan']]);
    exit;
}

switch ($endpoint) {
    case 'provinces':
        echo file_get_contents("https://wilayah.id/api/provinces.json");
        break;

    case 'regencies':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing id']);
            exit;
        }
        echo file_get_contents("https://wilayah.id/api/regencies/$id.json");
        break;

    case 'districts':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing id']);
            exit;
        }
        echo file_get_contents("https://wilayah.id/api/districts/$id.json");
        break;

    case 'villages':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing id']);
            exit;
        }
        echo file_get_contents("https://wilayah.id/api/villages/$id.json");
        break;

    // ENDPOINT UNTUK GET NAMA BERDASARKAN ID
    case 'province':
        fetchAndFilter("https://wilayah.id/api/provinces.json", $id);
        break;

    case 'regency':
        $prov_id = substr($id, 0, 2); // ambil 2 digit awal ID kota
        fetchAndFilter("https://wilayah.id/api/regencies/$prov_id.json", $id);
        break;

    case 'district':
        $regency_id = substr($id, 0, 4); // ambil 4 digit awal ID kecamatan
        fetchAndFilter("https://wilayah.id/api/districts/$regency_id.json", $id);
        break;
    
    case 'village':
        $district_id = substr($id, 0, 6); // ambil 6 digit awal ID kelurahan
        fetchAndFilter("https://wilayah.id/api/villages/$district_id.json", $id);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid endpoint']);
        break;
}
