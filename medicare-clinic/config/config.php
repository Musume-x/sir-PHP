<?php

define('APP_NAME', 'MediCare Clinic System');
define('BASE_URL', '/sir PHP/medicare-clinic'); // adjust if needed

/** Philippine peso (PHP) display */
function mc_format_money(float $amount, int $decimals = 2): string
{
    return '₱' . number_format($amount, $decimals);
}
