<?php

namespace App\Http\Controllers;

use App\Models\Answers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnswersController extends Controller
{

    public function index()
    {
        $answers = DB::table('answers')
            ->orderBy('answers_data', 'ASC')
            ->get();
        if ($answers) {
            return response()->json([
                'message' => 'Successfully Get All answers!', 'data' => $answers
            ]);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }


    public function store(Request $request)
    {
        $request->validate([]);

        $Answers = new Answers();
        $Answers->answers_data = $request->get('answers');
        // dd($Answers);
        $Answers->save();
        if ($Answers) {
            return response()->json([
                'message' => 'Successfully created Answers', 'data' => $Answers
            ], 201);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }

    public function show(Answers $Answers, $id)
    {
        $Answers = Answers::find($id);
        return response()->json(['status' => 'success', 'data' => $Answers]);
    }


    public function update(Request $request, Answers $Answers, $id)
    {
        $request->validate([]);

        $Answers = Answers::find($id);
        $Answers->answers_data = $request->get('edit_answers');
        // dd($Answers);
        $Answers->save();
        if ($Answers) {
            return response()->json([
                'message' => 'Successfully created Answers', 'data' => $Answers
            ], 201);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }

    public function destroy(Answers $Answers, $id)
    {
        $Answers = Answers::find($id);

        $Answers->delete();

        return response()->json('success');
    }
}
