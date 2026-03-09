<?php

require_once __DIR__ . '/../app/middleware/auth.php';
requireRole('parent');

require_once __DIR__ . '/../app/views/parent/admit-child.php';

