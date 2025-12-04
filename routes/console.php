<?php

/**
 * ============================================
 * CONSOLE ROUTES - SIPALINGKOPI
 * ============================================
 * 
 * File ini berisi custom artisan commands
 * Jalankan dengan: php artisan {command-name}
 * 
 * @package  SipalingKopi
 * @version  1.0.0
 */

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/**
 * Command: inspire
 * Usage: php artisan inspire
 * Fungsi: Menampilkan quote inspiratif di terminal
 * 
 * Example output:
 * "The only way to do great work is to love what you do." - Steve Jobs
 */
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
