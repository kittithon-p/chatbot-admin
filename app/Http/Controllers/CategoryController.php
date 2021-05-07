<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    public function index()
    {
        $Categories = DB::table('categories')
            ->orderBy('categories_data', 'ASC')
            ->get();
        if ($Categories) {
            return response()->json([
                'message' => 'Successfully Get All Categories!', 'data' => $Categories
            ]);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }


    public function store(Request $request)
    {
        $request->validate([]);

        $Category = new Category();
        $Category->categories_data = $request->get('categories');
        // dd($Category);
        $Category->save();
        if ($Category) {
            return response()->json([
                'message' => 'Successfully created Category', 'data' => $Category
            ], 201);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }

    public function show(Category $category, $id)
    {
        $Category = Category::find($id);
        return response()->json(['status' => 'success', 'data' => $Category]);
    }


    public function update(Request $request, Category $category, $id)
    {
        $request->validate([]);

        $Category = Category::find($id);
        $Category->categories_data = $request->get('edit_categories');
        // dd($Category);
        $Category->save();
        if ($Category) {
            return response()->json([
                'message' => 'Successfully created Category', 'data' => $Category
            ], 201);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }

    public function destroy(Category $category, $id)
    {
        $category = Category::find($id);

        $category->delete();

        return response()->json('success');
    }
}
