<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (){
    Route::view('/tutorial', 'website.convert_image')->name('tutorial');
});
?>