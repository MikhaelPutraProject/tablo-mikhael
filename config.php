<?php
// File: config.php

$tables = [
    'produk' => ['nama_produk', 'harga', 'stok'], 
    'pelanggan' => ['nama_pelanggan', 'alamat', 'email', 'telepon'],
    'pesanan' => ['tgl_pesanan', 'id_pelanggan', 'total_harga', 'status'],
    'supplier' => ['nama_supplier', 'kontak', 'kota'],
    'pegawai' => ['nama_pegawai', 'jabatan', 'tgl_masuk'],
    'gudang' => ['lokasi', 'kapasitas', 'status_gudang'],
    'kategori' => ['nama_kategori', 'keterangan'],
    'cabang' => ['nama_cabang', 'alamat_cabang'],
    'inventori' => ['nama_barang', 'jumlah', 'tgl_update'],
    'pengiriman' => ['no_resi', 'id_pesanan', 'kurir', 'tujuan'],
];

function getTableFields($tableName) {
    global $tables;
    return $tables[$tableName] ?? null;
}

function array_by_ref(array &$arr) {
    $refs = array();
    foreach($arr as $key => $value)
        $refs[$key] = &$arr[$key];
    return $refs;
}
?>