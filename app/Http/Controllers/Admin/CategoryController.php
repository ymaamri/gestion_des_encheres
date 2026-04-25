<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\SousCategorie;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = Categorie::with('sousCategories')->orderBy('nom')->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'icone' => 'nullable|string|max:100',
        ]);

        Categorie::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Categorie $category)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom,' . $category->id,
            'description' => 'nullable|string',
            'icone' => 'nullable|string|max:100',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Categorie $category)
    {
        // Check if category has products
        $productCount = $category->produits()->count();
        if ($productCount > 0) {
            return back()->with('error', 'Impossible de supprimer cette catégorie car elle contient ' . $productCount . ' produit(s).');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }

    /**
     * Store a subcategory.
     */
    public function storeSubcategory(Request $request, Categorie $category)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        SousCategorie::create([
            'categorie_id' => $category->id,
            'nom' => $validated['nom'],
            'description' => $validated['description'],
        ]);

        // Si la requête est AJAX, retourner JSON, sinon redirection
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Sous-catégorie ajoutée avec succès.']);
        }
        
        return back()->with('success', 'Sous-catégorie ajoutée avec succès.');
    }

    /**
     * Update a subcategory.
     */
    public function updateSubcategory(Request $request, SousCategorie $subcategory)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $subcategory->update($validated);

        // Rediriger vers la page des catégories avec un message de succès
        return redirect()->route('admin.categories.index')
            ->with('success', 'Sous-catégorie mise à jour avec succès.');
    }

    /**
     * Delete a subcategory.
     */
    public function destroySubcategory(Categorie $category, SousCategorie $subcategory)
    {
        // Check if subcategory has products
        $productCount = $subcategory->produits()->count();
        if ($productCount > 0) {
            return back()->with('error', 'Impossible de supprimer cette sous-catégorie car elle contient ' . $productCount . ' produit(s).');
        }

        $subcategory->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Sous-catégorie supprimée avec succès.');
    }
}