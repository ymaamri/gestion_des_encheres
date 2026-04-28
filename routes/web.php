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
use App\Helpers\ImageHelper; // <-- ADD THIS LINE

// ============================================
// HOME ROUTE
// ============================================
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ============================================
// DASHBOARD
// ============================================
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
            'total_bids' => \App\Models\Enchere::count(),
        ];
        return view('dashboard', compact('stats'));
    } elseif ($user->role === 'vendeur') {
        $client = $user->client;
        if (!$client) {
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
        $client = $user->client;
        if (!$client) {
            $client = \App\Models\Client::create([
                'user_id' => $user->id,
                'nom' => $user->nom,
                'prenom' => $user->prenom ?? '',
                'statut' => 'ACTIF',
                'solde' => 0,
            ]);
        }

        $stats = [
            'total_bids' => $client->encheres()->count(),
            'active_bids' => $client->encheres()->whereHas('annonce', function ($q) {
                $q->where('statut', 'ACTIVE');
            })->count(),
            'won_auctions' => $client->encheres()->whereHas('annonce', function ($q) {
                $q->where('statut', 'CLOTUREE');
            })->get()->filter(function ($enchere) {
                return $enchere->annonce->getHighestBid() &&
                    $enchere->annonce->getHighestBid()->id === $enchere->id;
            })->count(),
            'balance' => $client->solde ?? 0,
        ];
        return view('dashboard', compact('stats'));
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// ============================================
// PROFILE ROUTES
// ============================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================
// ANNONCES ROUTES (SELLER)
// ============================================
Route::middleware(['auth'])->prefix('annonces')->group(function () {
    Route::get('/', [AnnonceController::class, 'index'])->name('annonces.index');
    Route::get('/create', [AnnonceController::class, 'create'])->name('annonces.create');
    Route::post('/', [AnnonceController::class, 'store'])->name('annonces.store');
    Route::get('/{annonce}', [AnnonceController::class, 'show'])->name('annonces.show');
    Route::get('/{annonce}/edit', [AnnonceController::class, 'edit'])->name('annonces.edit');
    Route::put('/{annonce}', [AnnonceController::class, 'update'])->name('annonces.update');
    Route::delete('/{annonce}', [AnnonceController::class, 'destroy'])->name('annonces.destroy');
});

// ============================================
// PUBLIC AUCTION ROUTES (BUYER)
// ============================================
Route::middleware(['auth', 'role:client'])->group(function () {
    Route::get('/auctions/active', [PublicAuctionController::class, 'active'])->name('auctions.active');
    Route::get('/my-bids', [EnchereController::class, 'myBids'])->name('my.bids');
    Route::get('/my-won', [EnchereController::class, 'wonAuctions'])->name('my.won');
    Route::post('/bids/{annonce}', [EnchereController::class, 'placeBid'])->name('bids.place');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark');
});

// ============================================
// ADMIN ROUTES
// ============================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::put('/users/{user}/block', [UserController::class, 'block'])->name('users.block');
    Route::put('/users/{user}/unblock', [UserController::class, 'unblock'])->name('users.unblock');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::post('/categories/{category}/subcategories', [CategoryController::class, 'storeSubcategory'])->name('categories.subcategories.store');
    Route::delete('/categories/{category}/subcategories/{subcategory}', [CategoryController::class, 'destroySubcategory'])->name('categories.subcategories.destroy');

    Route::get('/auctions', [AdminAuctionController::class, 'index'])->name('auctions.index');
    Route::get('/auctions/{auction}', [AdminAuctionController::class, 'show'])->name('auctions.show');
    Route::post('/auctions/{annonce}/publish', [AdminAuctionController::class, 'publish'])->name('auctions.publish');
    Route::post('/auctions/{annonce}/block', [AdminAuctionController::class, 'block'])->name('auctions.block');
    Route::delete('/auctions/{annonce}', [AdminAuctionController::class, 'destroy'])->name('auctions.destroy');
});

// ============================================
// API ROUTES FOR WELCOME PAGE (PUBLIC)
// ============================================

Route::get('/api/stats', function () {
    return response()->json([
        'total_users' => User::count(),
        'active_auctions' => Annonce::where('statut', 'ACTIVE')
            ->where('date_fin', '>', now())
            ->count(),
        'total_bids' => Enchere::count(),
    ]);
});

Route::get('/api/categories', function () {
    $categories = Categorie::all();
    $result = [];
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

    foreach ($categories as $category) {
        $count = Annonce::where('statut', 'ACTIVE')
            ->where('date_fin', '>', now())
            ->whereHas('produit', function ($query) use ($category) {
                $query->whereHas('sousCategorie', function ($sub) use ($category) {
                    $sub->where('categorie_id', $category->id);
                });
            })->count();

        $result[] = [
            'id' => $category->id,
            'nom' => $category->nom,
            'icon' => $iconMap[$category->nom] ?? 'fa-tag',
            'produits_count' => $count,
        ];
    }
    return response()->json($result);
});

Route::get('/api/products', function (Request $request) {
    $query = Annonce::with(['produit', 'encheres', 'vendeur.client'])
        ->where('statut', 'ACTIVE')
        ->where('date_fin', '>', now());

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

    if ($request->filled('category_id')) {
        $categoryId = $request->category_id;
        $query->whereHas('produit.sousCategorie', function ($q) use ($categoryId) {
            $q->where('categorie_id', $categoryId);
        });
    }

    $products = $query->orderBy('created_at', 'desc')->paginate(9);

    return response()->json([
        'data' => $products->map(function ($annonce) {
            return [
                'id' => $annonce->id,
                'titre' => $annonce->titre,
                'description' => Str::limit($annonce->description, 100),
                'current_price' => $annonce->getMontantActuel(),
                'original_price' => $annonce->prix_depart,
                'bid_count' => $annonce->encheres()->count(),
                'date_fin' => $annonce->date_fin,
                'image' => ImageHelper::getProductImage($annonce->produit),   // ✅ FIXED
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

Route::get('/api/products/by-category', function (Request $request) {
    $request->validate(['category_id' => 'required|exists:categories,id']);
    $products = Annonce::with(['produit', 'encheres'])
        ->where('statut', 'ACTIVE')
        ->where('date_fin', '>', now())
        ->whereHas('produit.sousCategorie', function ($q) use ($request) {
            $q->where('categorie_id', $request->category_id);
        })
        ->orderBy('created_at', 'desc')
        ->limit(12)
        ->get();

    return response()->json([
        'data' => $products->map(function ($annonce) {
            return [
                'id' => $annonce->id,
                'titre' => $annonce->titre,
                'current_price' => $annonce->getMontantActuel(),
                'original_price' => $annonce->prix_depart,
                'bid_count' => $annonce->encheres()->count(),
                'date_fin' => $annonce->date_fin,
                'image' => ImageHelper::getProductImage($annonce->produit),   // ✅ FIXED
            ];
        })
    ]);
});

Route::get('/api/subcategories/{categoryId}', function ($categoryId) {
    $subcategories = \App\Models\SousCategorie::where('categorie_id', $categoryId)->get();
    return response()->json($subcategories);
});

Route::get('/api/featured-products', function () {
    $products = Annonce::with(['produit', 'encheres'])
        ->where('statut', 'ACTIVE')
        ->where('date_fin', '>', now())
        ->orderBy('created_at', 'desc')
        ->limit(6)
        ->get();

    return response()->json([
        'data' => $products->map(function ($annonce) {
            return [
                'id' => $annonce->id,
                'titre' => $annonce->titre,
                'current_price' => $annonce->getMontantActuel(),
                'bid_count' => $annonce->encheres()->count(),
                'image' => ImageHelper::getProductImage($annonce->produit),   // ✅ FIXED
            ];
        })
    ]);
});

// ============================================
// SELLER ROUTES
// ============================================
Route::middleware(['auth', 'role:vendeur'])
    ->prefix('vendeur')
    ->name('seller.')
    ->group(function () {
        Route::resource('products', \App\Http\Controllers\Seller\ProductController::class)->except(['show']);
        Route::get('products/{product}', [\App\Http\Controllers\Seller\ProductController::class, 'show'])->name('products.show');
        Route::get('subcategories/{category}', [\App\Http\Controllers\Seller\ProductController::class, 'getSubcategories'])->name('products.subcategories');
        Route::get('/bids', [\App\Http\Controllers\Seller\BidController::class, 'index'])->name('bids.index');
        Route::get('/sales', [\App\Http\Controllers\Seller\SalesController::class, 'index'])->name('sales.index');
    });

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';