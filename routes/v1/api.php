<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['throttle:api-v1'])->group(function () {
    require __DIR__.'/auth.php';
});
