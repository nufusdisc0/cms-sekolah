<?php
try {
    $db = new PDO('sqlite:database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare("INSERT INTO settings (setting_group, setting_variable, setting_value, setting_default_value, setting_description, created_at, updated_at, created_by, updated_by, deleted_by, restored_by, is_deleted) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([
        'school_profile',
        'headmaster_photo',
        '',
        'image', // This is now for setting_default_value
        'Foto Kepala Sekolah',
        date('Y-m-d H:i:s'),
        date('Y-m-d H:i:s'),
        0, 0, 0, 0, 'false'
    ]);

    echo "SUCCESS\n";
}
catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
