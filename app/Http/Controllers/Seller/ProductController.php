<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\SousCategorie;
use App\Models\Annonce;  // Added for the index method
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // No constructor – middleware is applied via routes

    public function index()
    {
        $vendeur = Auth::user()->client->vendeur;

        // Get all product IDs from the seller's auctions
        $productIds = Annonce::where('vendeur_id', $vendeur->id)
            ->pluck('produit_id')
            ->unique();

        $products = Produit::whereIn('id', $productIds)
            ->latest()
            ->paginate(12);

        return view('seller.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Categorie::with('sousCategories')->get();
        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'marque' => 'nullable|string|max:255',
            'modele' => 'nullable|string|max:255',
            'etat' => 'required|in:NEUF,TRES_BON_ETAT,BON_ETAT,ACCEPTABLE',
            'categorie_id' => 'required|exists:categories,id',
            'sous_categorie_id' => 'nullable|exists:sous_categories,id',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $filename = time() . '_' . Str::random(10) . '.' . $photo->getClientOriginalExtension();
                $path = $photo->storeAs('products', $filename, 'public');
                $photos[] = $path;
            }
        }

        $produit = Produit::create([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'marque' => $validated['marque'],
            'modele' => $validated['modele'],
            'etat' => $validated['etat'],
            'sous_categorie_id' => $validated['sous_categorie_id'],
            'photos' => $photos,
        ]);

        return redirect()->route('seller.products.index')
            ->with('success', 'Produit créé avec succès !');
    }

    public function show(Produit $product)
    {
        $vendeur = Auth::user()->client->vendeur;
        $belongsToSeller = $product->annonces()->where('vendeur_id', $vendeur->id)->exists();
        if (!$belongsToSeller) {
            abort(403);
        }

        return view('seller.products.show', compact('product'));
    }

    public function edit(Produit $product)
    {
        $vendeur = Auth::user()->client->vendeur;
        $belongsToSeller = $product->annonces()->where('vendeur_id', $vendeur->id)->exists();
        if (!$belongsToSeller) {
            abort(403);
        }

        $categories = Categorie::with('sousCategories')->get();
        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Produit $product)
    {
        $vendeur = Auth::user()->client->vendeur;
        $belongsToSeller = $product->annonces()->where('vendeur_id', $vendeur->id)->exists();
        if (!$belongsToSeller) {
            abort(403);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'marque' => 'nullable|string|max:255',
            'modele' => 'nullable|string|max:255',
            'etat' => 'required|in:NEUF,TRES_BON_ETAT,BON_ETAT,ACCEPTABLE',
            'categorie_id' => 'required|exists:categories,id',
            'sous_categorie_id' => 'nullable|exists:sous_categories,id',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_photos' => 'nullable|array',
        ]);

        $photos = $product->photos ?? [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $filename = time() . '_' . Str::random(10) . '.' . $photo->getClientOriginalExtension();
                $path = $photo->storeAs('products', $filename, 'public');
                $photos[] = $path;
            }
        }

        if ($request->filled('delete_photos')) {
            foreach ($request->delete_photos as $photoToDelete) {
                if (Storage::disk('public')->exists($photoToDelete)) {
                    Storage::disk('public')->delete($photoToDelete);
                }
                $photos = array_values(array_filter($photos, fn($p) => $p !== $photoToDelete));
            }
        }

        $product->update([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'marque' => $validated['marque'],
            'modele' => $validated['modele'],
            'etat' => $validated['etat'],
            'sous_categorie_id' => $validated['sous_categorie_id'],
            'photos' => $photos,
        ]);

        return redirect()->route('seller.products.index')
            ->with('success', 'Produit mis à jour !');
    }

    public function destroy(Produit $product)
    {
        $vendeur = Auth::user()->client->vendeur;
        $belongsToSeller = $product->annonces()->where('vendeur_id', $vendeur->id)->exists();
        if (!$belongsToSeller) {
            abort(403);
        }

        if ($product->photos) {
            foreach ($product->photos as $photo) {
                if (Storage::disk('public')->exists($photo)) {
                    Storage::disk('public')->delete($photo);
                }
            }
        }

        $product->delete();
        return redirect()->route('seller.products.index')
            ->with('success', 'Produit supprimé définitivement.');
    }

    public function getSubcategories($categoryId)
    {
        $subcategories = SousCategorie::where('categorie_id', $categoryId)->get();
        return response()->json($subcategories);
    }
}