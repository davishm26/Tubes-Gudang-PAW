<?php

return [
    // Batas stok menipis. Jika stok <= nilai ini, kirim notifikasi ke admin perusahaan.
    'low_stock_threshold' => env('LOW_STOCK_THRESHOLD', 5),

    // Jangka hari sebelum langganan berakhir untuk mengirim notifikasi.
    'subscription_expiry_days' => env('SUBSCRIPTION_EXPIRY_DAYS', 7),

    // Hindari duplikasi: jika ada notifikasi dengan template yang sama untuk penerima pada hari yang sama, jangan buat baru.
    'dedupe_same_day' => true,
];
