<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeywordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Keyword = DB::table('keywords')
            ->join('categories', 'categories.id', '=', 'keywords.c_id')
            ->orderBy('categories_data', 'ASC')
            ->select('keywords.id as keywords_id', 'keywords.keywords_data', 'keywords.updated_at', 'categories.id as categories_id', 'categories.categories_data')
            ->get();
        // dd($Keyword);
        if ($Keyword) {
            return response()->json([
                'message' => 'Successfully Get All Categories!', 'data' => $Keyword
            ]);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([]);

        $Keyword = new Keyword();
        $Keyword->keywords_data = $request->get('keywords_data');
        $Keyword->c_id = $request->get('c_id');

        // dd($Category);
        $Keyword->save();
        if ($Keyword) {
            return response()->json([
                'message' => 'Successfully created Keyword', 'data' => $Keyword
            ], 201);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Keyword  $keyword
     * @return \Illuminate\Http\Response
     */
    public function show(Keyword $keyword, $id)
    {
        $Keyword = Keyword::find($id);
        return response()->json(['status' => 'success', 'data' => $Keyword]);
    }


    public function edit(Keyword $keyword)
    {
        //
    }


    public function update(Request $request, Keyword $keyword, $id)
    {
        $request->validate([]);

        $Keyword = Keyword::find($id);
        $Keyword->keywords_data = $request->get('keywords_data');
        $Keyword->c_id = $request->get('c_id');

        // dd($Category);
        $Keyword->save();
        if ($Keyword) {
            return response()->json([
                'message' => 'Successfully created Category', 'data' => $Keyword
            ], 201);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }


    public function destroy(Keyword $keyword, $id)
    {
        $Keyword = Keyword::find($id);

        $Keyword->delete();

        return response()->json('success');
    }
}
