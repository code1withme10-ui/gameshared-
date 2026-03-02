<?php

require_once __DIR__ . '/../app/middleware/auth.php';
requireRole('headmaster');

require_once __DIR__ . '/../app/views/admin/dashboard.php';

