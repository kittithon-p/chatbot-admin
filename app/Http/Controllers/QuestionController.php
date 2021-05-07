<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Question = DB::table('questions')
            ->join('answers', 'answers.id', '=', 'questions.a_id')
            ->join('categories', 'categories.id', '=', 'questions.c_id')
            ->orderBy('questions_data', 'ASC')
            ->get(['questions.id', 'questions.questions_data', 'questions.updated_at', 'answers.id as answers_id', 'answers.answers_data', 'categories.id as categories_id', 'categories.categories_data']);
        // dd($Question);
        if ($Question) {
            return response()->json([
                'message' => 'Successfully Get All questions!', 'data' => $Question
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

        $Question = new Question();
        $Question->questions_data = $request->get('questions_data');
        $Question->a_id = $request->get('a_id');
        $Question->c_id = $request->get('c_id');
        // dd($Question);
        $Question->save();
        if ($Question) {
            return response()->json([
                'message' => 'Successfully created Answers', 'data' => $Question
            ], 201);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question, $id)
    {
        $Question = Question::find($id);
        return response()->json(['status' => 'success', 'data' => $Question]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question, $id)
    {
        $request->validate([]);

        $Question = Question::find($id);
        $Question->questions_data = $request->get('questions_data');
        $Question->a_id = $request->get('a_id');
        $Question->c_id = $request->get('c_id');
        $Question->save();
        if ($Question) {
            return response()->json([
                'message' => 'Successfully created Answers', 'data' => $Question
            ], 201);
        } else {
            return response()->json(['error' => 'Provide proper details']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question, $id)
    {
        $Question = Question::find($id);

        $Question->delete();

        return response()->json('success');
    }
}
