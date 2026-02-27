<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2026-02-24 00:46:06 --> Severity: error --> Exception: Class "mysqli_driver" not found D:\Project\Aplikasi Sekolah\cms-sekolah\legacy\system\database\drivers\mysqli\mysqli_driver.php 123
ERROR - 2026-02-24 00:47:04 --> Severity: error --> Exception: Class "mysqli_driver" not found D:\Project\Aplikasi Sekolah\cms-sekolah\legacy\system\database\drivers\mysqli\mysqli_driver.php 123
ERROR - 2026-02-24 00:52:06 --> Severity: error --> Exception: Class "mysqli_driver" not found D:\Project\Aplikasi Sekolah\cms-sekolah\legacy\system\database\drivers\mysqli\mysqli_driver.php 123
ERROR - 2026-02-24 00:58:24 --> Severity: error --> Exception: Class "mysqli_driver" not found D:\Project\Aplikasi Sekolah\cms-sekolah\legacy\system\database\drivers\mysqli\mysqli_driver.php 123
ERROR - 2026-02-24 01:26:07 --> Query error: no such table: _sessions - Invalid query: SELECT "data"
FROM "_sessions"
WHERE "id" = 'e62611f7750dcad92d97a943e2293aeabc35d90f'
ERROR - 2026-02-24 01:29:19 --> Query error: no such table: _sessions - Invalid query: SELECT "data"
FROM "_sessions"
WHERE "id" = 'ee9aa59dc4458899f8b9af93f6592923cbf75cf1'
ERROR - 2026-02-24 08:30:57 --> Query error: no such function: CURDATE - Invalid query: SELECT "id", "phase_name", "phase_start_date", "phase_end_date"
FROM "admission_phases"
WHERE CURDATE() >= "phase_start_date"
AND CURDATE() <= "phase_end_date"
AND "is_deleted" = 'false'
ORDER BY "phase_start_date" DESC
 LIMIT 1
ERROR - 2026-02-24 08:59:25 --> Query error: no such function: CURDATE - Invalid query: SELECT "id", "phase_name", "phase_start_date", "phase_end_date"
FROM "admission_phases"
WHERE CURDATE() >= "phase_start_date"
AND CURDATE() <= "phase_end_date"
AND "is_deleted" = 'false'
ORDER BY "phase_start_date" DESC
 LIMIT 1
