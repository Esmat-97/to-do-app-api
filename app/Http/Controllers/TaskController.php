<?php

namespace App\Http\Controllers;


use \App\Models\Task;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    //
    function show(){
        
        $tasks = Task::all();
        return $tasks;
      
    }


    public function detail($user_id)
    {
        // Retrieve tasks associated with the specified user_id
        $tasks = Task::where('user_id', $user_id)->get();
        
        // Return a response, typically in JSON format
        return response()->json(['tasks' => $tasks]);
    }


    


    public function destroy($id)
    {
        $user = Task::find($id);
        if (!$user) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }





    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id', // Make sure user_id exists in the users table
            'completed' => 'boolean', // Optionally, validate completed as boolean
        ]);

        // Create the task using the Task model
        $task = Task::create([
            'description' => $validatedData['description'],
            'user_id' => $validatedData['user_id'],
            'completed' => $request->has('completed') ? $validatedData['completed'] : false, // Default to false if not provided
        ]);

        // Return a JSON response with the newly created task and status code 201 (Created)
        return response()->json($task, 201);
    }

    





    public function update(Request $request, $id)
{
    // Find the user by ID
    $user = Task::find($id);

    // If the user doesn't exist, return a 404 response
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }


    $validatedData = $request->validate([
        'description' => 'required|string',
        'user_id' => 'required|exists:users,id', // Make sure user_id exists in the users table
        'completed' => 'boolean', // Optionally, validate completed as boolean
    ]);

    // Update the user record with the validated data
    $user->description = $validatedData['description'];
    $user->user_id = $validatedData['user_id'];
    $user->completed = $validatedData['completed'];
    $user->save();

    // Return a JSON response with the updated user data
    return response()->json($user);
}

}

