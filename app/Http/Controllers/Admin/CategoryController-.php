<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\SousCategorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories with their subcategories.
     */
    public function index()
    {
        $categories = Categorie::with('sousCategories')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom',
            'description' => 'nullable|string',
            'icone' => 'nullable|string|max:100',
        ]);

        try {
            Categorie::create($validated);
            return redirect()->route('admin.categories.index')
                ->with('success', 'Catégorie créée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Categorie $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Categorie $category)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:categories,nom,' . $category->id,
            'description' => 'nullable|string',
            'icone' => 'nullable|string|max:100',
        ]);

        try {
            $category->update($validated);
            return redirect()->route('admin.categories.index')
                ->with('success', 'Catégorie mise à jour avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Categorie $category)
    {
        try {
            // Check if category has subcategories
            if ($category->sousCategories()->count() > 0) {
                return back()->with('error', 'Impossible de supprimer cette catégorie car elle contient des sous-catégories.');
            }
            
            $category->delete();
            return redirect()->route('admin.categories.index')
                ->with('success', 'Catégorie supprimée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Display subcategories for a specific category.
     */
    public function subcategories(Categorie $category)
    {
        $subcategories = $category->sousCategories()->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.categories.subcategories', compact('category', 'subcategories'));
    }

    /**
     * Show form to create a subcategory.
     */
    public function createSubcategory(Categorie $category)
    {
        return view('admin.categories.create-subcategory', compact('category'));
    }

    /**
     * Store a new subcategory.
     */
    public function storeSubcategory(Request $request, Categorie $category)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            SousCategorie::create([
                'categorie_id' => $category->id,
                'nom' => $validated['nom'],
                'description' => $validated['description'],
            ]);
            
            return redirect()->route('admin.categories.subcategories', $category)
                ->with('success', 'Sous-catégorie créée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    /**
     * Show form to edit a subcategory.
     */
    public function editSubcategory(SousCategorie $subcategory)
    {
        $category = $subcategory->categorie;
        return view('admin.categories.edit-subcategory', compact('subcategory', 'category'));
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

        try {
            $subcategory->update($validated);
            return redirect()->route('admin.categories.subcategories', $subcategory->categorie_id)
                ->with('success', 'Sous-catégorie mise à jour avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Delete a subcategory.
     */
    public function destroySubcategory(SousCategorie $subcategory)
    {
        try {
            $categoryId = $subcategory->categorie_id;
            $subcategory->delete();
            return redirect()->route('admin.categories.subcategories', $categoryId)
                ->with('success', 'Sous-catégorie supprimée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}