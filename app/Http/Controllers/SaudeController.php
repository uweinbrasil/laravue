<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class SaudeController extends Controller
{

	public function index(){
		$daten = \App\Saude::whereNotNull('id');

		//if(request()->has('title'))
		//	$tasks->where('title','like','%'.request('title').'%');

        //if(request()->has('status'))
        //    $tasks->whereStatus(request('status'));

        $daten->orderBy(request('sortBy'),request('order'));

		return $daten->paginate(request('pageLength'));
	}

    public function store(Request $request){
       
        $validation = Validator::make($request->all(), [
            //'title' => 'required|unique:tasks',
            //'description' => 'required',
            //'start_date' => 'required|date_format:Y-m-d',
            //'due_date' => 'required|date_format:Y-m-d|after:start_date'
        ]);

        if($validation->fails())
        	return response()->json(['message' => $validation->messages()->first()],422);

        $user = \JWTAuth::parseToken()->authenticate();
        $task = new \App\Saude;
        $task->fill(request()->all());
        $task->uuid = generateUuid();
        $task->user_id = $user->id;
        $task->save();

        return response()->json(['message' => 'Daten hinzugefügt!', 'data' => $task]);
    }

     public function destroy(Request $request, $id){
        $task = \App\Saude::find($id);

        if(!$task)
            return response()->json(['message' => 'Couldnot find Data!'],422);

        $task->delete();

        return response()->json(['message' => 'Data deleted!']);
    } 

  /*   public function show($id){
        $task = \App\Task::whereUuid($id)->first();

        if(!$task)
            return response()->json(['message' => 'Couldnot find task!'],422);

        return $task;
    } */

  /*   public function update(Request $request, $id){

        $task = \App\Task::whereUuid($id)->first();

        if(!$task)
            return response()->json(['message' => 'Couldnot find task!']);

        $validation = Validator::make($request->all(), [
            'title' => 'required|unique:tasks,title,'.$task->id.',id',
            'description' => 'required',
            'start_date' => 'required|date_format:Y-m-d',
            'due_date' => 'required|date_format:Y-m-d|after:start_date'
        ]);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first()],422);

        $task->title = request('title');
        $task->description = request('description');
        $task->start_date = request('start_date');
        $task->due_date = request('due_date');
        $task->progress = request('progress');
        $task->save();

        return response()->json(['message' => 'Task updated!', 'data' => $task]);
    } */

  
}
