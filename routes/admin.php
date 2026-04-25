<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AuctionController as AdminAuctionController;
use App\Http\Controllers\EnchereController;
use App\Http\Controllers\AuctionController as PublicAuctionController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Annonce;
use App\Models\Enchere;
use App\Models\Categorie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::put('/categories/subcategories/{subcategory}', [CategoryController::class, 'updateSubcategory'])->name('categories.subcategories.update');
});