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
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Annonce;
use App\Models\Mise;
use App\Models\Categorie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
    // User management routes (complete CRUD + block/unblock)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::put('/users/{user}/block', [UserController::class, 'block'])->name('users.block');
    Route::put('/users/{user}/unblock', [UserController::class, 'unblock'])->name('users.unblock');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Category management routes (including subcategories)
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Subcategory management routes
    Route::post('/categories/{category}/subcategories', [CategoryController::class, 'storeSubcategory'])->name('categories.subcategories.store');
    Route::delete('/categories/{category}/subcategories/{subcategory}', [CategoryController::class, 'destroySubcategory'])->name('categories.subcategories.destroy');

    // Auction management routes
    Route::get('/auctions', [AuctionController::class, 'index'])->name('auctions.index');
    Route::get('/auctions/{auction}', [AuctionController::class, 'show'])->name('auctions.show');
    Route::post('/auctions/{annonce}/publish', [AuctionController::class, 'publish'])->name('auctions.publish');
    Route::post('/auctions/{annonce}/block', [AuctionController::class, 'block'])->name('auctions.block');
    Route::delete('/auctions/{annonce}', [AuctionController::class, 'destroy'])->name('auctions.destroy');
});

// ============================================
// API ROUTES FOR WELCOME PAGE (PUBLIC ACCESS)
// ============================================

// Get statistics for hero section
Route::get('/api/stats', function () {
    return response()->json([
        'total_users' => User::count(),
        'active_auctions' => Annonce::where('statut', 'ACTIVE')
            ->where('date_fin', '>', now())
            ->count(),
        'total_bids' => Mise::count(),
    ]);
});

// Get all categories with product counts
Route::get('/api/categories', function () {
    $categories = Categorie::withCount([
        'produits' => function ($query) {
            $query->whereHas('annonces', function ($q) {
                $q->where('statut', 'ACTIVE')
                    ->where('date_fin', '>', now());
            });
        }
    ])->get();

    // Map icon names to Font Awesome classes
    $iconMap = [
        'Electronics' => 'fa-laptop',
        'Fashion' => 'fa-tshirt',
        'Home & Garden' => 'fa-home',
        'Sports' => 'fa-futbol',
        'Automotive' => 'fa-car',
        'Collectibles' => 'fa-gem',
        'Books' => 'fa-book',
        'Toys & Hobbies' => 'fa-gamepad',
    ];

    return response()->json($categories->map(function ($category) use ($iconMap) {
        return [
            'id' => $category->id,
            'nom' => $category->nom,
            'icon' => $iconMap[$category->nom] ?? 'fa-tag',
            'produits_count' => $category->produits_count,
        ];
    }));
});

// Get products with pagination and search
Route::get('/api/products', function (Request $request) {
    $query = Annonce::with(['produit', 'mises', 'vendeur.client'])
        ->where('statut', 'ACTIVE')
        ->where('date_fin', '>', now());

    // Apply search filter
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('titre', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orWhereHas('produit', function ($pq) use ($search) {
                    $pq->where('nom', 'like', '%' . $search . '%')
                        ->orWhere('marque', 'like', '%' . $search . '%')
                        ->orWhere('modele', 'like', '%' . $search . '%');
                });
        });
    }

    $products = $query->orderBy('created_at', 'desc')->paginate(9);

    return response()->json([
        'data' => $products->map(function ($annonce) {
            $photos = $annonce->produit->photos ?? [];
            $firstPhoto = !empty($photos) ? Storage::url($photos[0]) : null;

            return [
                'id' => $annonce->id,
                'titre' => $annonce->titre,
                'description' => Str::limit($annonce->description, 100),
                'current_price' => $annonce->getMontantActuel(),
                'original_price' => $annonce->prix_depart,
                'bid_count' => $annonce->mises()->count(),
                'date_fin' => $annonce->date_fin,
                'image' => $firstPhoto ?: 'https://via.placeholder.com/300x250?text=No+Image',
                'category' => $annonce->produit->categorie->nom ?? 'Non catégorisé',
                'seller_name' => $annonce->vendeur->client->nom ?? 'Vendeur',
                'seller_rating' => $annonce->vendeur->note_moyenne ?? 0,
            ];
        }),
        'current_page' => $products->currentPage(),
        'last_page' => $products->lastPage(),
        'total' => $products->total(),
    ]);
});

// Get products by category
Route::get('/api/products/by-category', function (Request $request) {
    $request->validate([
        'category_id' => 'required|exists:categories,id'
    ]);

    $products = Annonce::with(['produit', 'mises'])
        ->where('statut', 'ACTIVE')
        ->where('date_fin', '>', now())
        ->whereHas('produit.categorie', function ($q) use ($request) {
            $q->where('id', $request->category_id);
        })
        ->orderBy('created_at', 'desc')
        ->limit(12)
        ->get();

    return response()->json([
        'data' => $products->map(function ($annonce) {
            $photos = $annonce->produit->photos ?? [];
            $firstPhoto = !empty($photos) ? Storage::url($photos[0]) : null;

            return [
                'id' => $annonce->id,
                'titre' => $annonce->titre,
                'current_price' => $annonce->getMontantActuel(),
                'original_price' => $annonce->prix_depart,
                'bid_count' => $annonce->mises()->count(),
                'date_fin' => $annonce->date_fin,
                'image' => $firstPhoto ?: 'https://via.placeholder.com/300x250?text=No+Image',
            ];
        })
    ]);
});

// Get featured products (for homepage carousel)
Route::get('/api/featured-products', function () {
    $products = Annonce::with(['produit', 'mises'])
        ->where('statut', 'ACTIVE')
        ->where('date_fin', '>', now())
        ->orderBy('mises_count', 'desc')
        ->limit(6)
        ->get();

    return response()->json([
        'data' => $products->map(function ($annonce) {
            $photos = $annonce->produit->photos ?? [];
            $firstPhoto = !empty($photos) ? Storage::url($photos[0]) : null;

            return [
                'id' => $annonce->id,
                'titre' => $annonce->titre,
                'current_price' => $annonce->getMontantActuel(),
                'bid_count' => $annonce->mises()->count(),
                'image' => $firstPhoto ?: 'https://via.placeholder.com/300x250?text=No+Image',
            ];
        })
    ]);
});

require __DIR__ . '/auth.php';