<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AuctionController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\AuctionController as PublicAuctionController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    if ($user->role === 'admin') {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_auctions' => \App\Models\Annonce::count(),
            'active_auctions' => \App\Models\Annonce::where('statut', 'ACTIVE')->count(),
            'total_bids' => \App\Models\Mise::count(),
        ];
        return view('dashboard', compact('stats'));
    } elseif ($user->role === 'vendeur') {
        // Check if user has a client and vendeur record
        $client = $user->client;
        if (!$client) {
            // Create client record if it doesn't exist
            $client = \App\Models\Client::create([
                'user_id' => $user->id,
                'nom' => $user->nom,
                'prenom' => $user->prenom ?? '',
                'statut' => 'ACTIF',
                'solde' => 0,
            ]);
        }

        $vendeur = $client->vendeur;
        if (!$vendeur) {
            // Create vendeur record if it doesn't exist
            $vendeur = \App\Models\Vendeur::create([
                'client_id' => $client->id,
                'note_moyenne' => 0,
                'nombre_ventes' => 0,
            ]);
        }

        $stats = [
            'total_listings' => $vendeur->annonces()->count(),
            'active_listings' => $vendeur->annonces()->where('statut', 'ACTIVE')->count(),
            'total_sales' => $vendeur->nombre_ventes ?? 0,
            'rating' => $vendeur->note_moyenne ?? 0,
        ];
        return view('dashboard', compact('stats'));
    } else {
        // Client role
        $client = $user->client;
        if (!$client) {
            // Create client record if it doesn't exist
            $client = \App\Models\Client::create([
                'user_id' => $user->id,
                'nom' => $user->nom,
                'prenom' => $user->prenom ?? '',
                'statut' => 'ACTIF',
                'solde' => 0,
            ]);
        }

        $stats = [
            'total_bids' => $client->mises()->count(),
            'active_bids' => $client->mises()->whereHas('annonce', function ($q) {
                $q->where('statut', 'ACTIVE');
            })->count(),
            'won_auctions' => $client->mises()->whereHas('annonce', function ($q) {
                $q->where('statut', 'CLOTUREE');
            })->count(),
            'balance' => $client->solde ?? 0,
        ];
        return view('dashboard', compact('stats'));
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Annonces routes (for sellers)
Route::middleware(['auth'])->prefix('annonces')->group(function () {
    Route::get('/', [AnnonceController::class, 'index'])->name('annonces.index');
    Route::get('/create', [AnnonceController::class, 'create'])->name('annonces.create');
    Route::post('/', [AnnonceController::class, 'store'])->name('annonces.store');
    Route::get('/{annonce}', [AnnonceController::class, 'show'])->name('annonces.show');
    Route::get('/{annonce}/edit', [AnnonceController::class, 'edit'])->name('annonces.edit');
    Route::put('/{annonce}', [AnnonceController::class, 'update'])->name('annonces.update');
    Route::delete('/{annonce}', [AnnonceController::class, 'destroy'])->name('annonces.destroy');
});


// Public auction routes (for buyers)
Route::middleware(['auth', 'role:client'])->group(function () {
    Route::get('/auctions/active', [PublicAuctionController::class, 'active'])->name('auctions.active');
    Route::get('/my-bids', [BidController::class, 'myBids'])->name('my.bids');
    Route::get('/my-won', [BidController::class, 'wonAuctions'])->name('my.won');
    Route::post('/bids/{annonce}', [BidController::class, 'placeBid'])->name('bids.place');

    // Add this inside the client middleware group
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('auctions', AuctionController::class);
    Route::post('/auctions/{annonce}/publish', [AuctionController::class, 'publish'])->name('auctions.publish');
    Route::post('/auctions/{annonce}/block', [AuctionController::class, 'block'])->name('auctions.block');
});

require __DIR__ . '/auth.php';