<?php
require_once __DIR__ . '/../../backend/config.php';

function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
