<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $authUser = $this->authenticatedUser();
        if ($authUser->role === 'admin') {
            $data = Todo::with('user');
        }
        else {
            $data = Todo::where('user_id', $authUser->id)->with('user');
        }

        $paginateAppends = [];

        if ($request->input('search')) {
            $search = $request->input('search');
            $data->where('title', 'like', '%'.$search.'%')
                ->orWhere('description', 'like', '%'.$search.'%');
            $paginateAppends['search'] = $search;
        }

        $filter = $request->input('filter');
        if ($filter === '0' || $filter === '1') {
            $data->where('isCompleted', $filter);
            $paginateAppends['filter'] = $filter;
        }


        $data = $data->orderBy('created_at', 'asc')->paginate(10)->appends($paginateAppends);
        return response()->json(['success' => true, 'data' => $data], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'required|string|max:50',
            'description'   => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = $request->only('title', 'description');
        $data['slug'] = Str::slug($data['title']);
        $data['user_id'] = $this->authenticatedUser()->id;

        Todo::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Todo added successfully'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $authUser = $this->authenticatedUser();
        if ($authUser->role === 'admin') {
            $data = Todo::find($id);
        }
        else {
            $data = Todo::where('user_id', $authUser->id)->whereAnd('id', $id)->first();
        }
        return response()->json(['success' => true, 'data' => $data], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Todo $todo
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Todo $todo)
    {
        $authUser = $this->authenticatedUser();
        if ($authUser->role !== 'admin') {
            $todo = Todo::where('user_id', $authUser->id)->whereAnd('id', $todo->id)->first();
        }
        $validator = Validator::make($request->all(), [
            'title'         => 'required|string|max:50',
            'description'   => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = $request->only('title', 'description');
        $data['slug'] = Str::slug($data['title']);

        $todo->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Todo updated successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Todo $todo
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Todo $todo)
    {
        $authUser = $this->authenticatedUser();
        if ($authUser->role !== 'admin') {
            $todo = Todo::where('user_id', $authUser->id)->whereAnd('id', $todo->id)->first();
        }
        $todo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Todo deleted successfully'
        ], 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function complete($id)
    {
        $authUser = $this->authenticatedUser();
        if ($authUser->role === 'admin') {
            $todo = Todo::find($id);
        }
        else {
            $todo = Todo::where('user_id', $authUser->id)->whereAnd('id', $id)->first();
        }

        $data['isCompleted'] = '1';
        $todo->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Todo completed successfully'
        ], 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function unComplete($id)
    {
        $authUser = $this->authenticatedUser();
        if ($authUser->role === 'admin') {
            $todo = Todo::find($id);
        }
        else {
            $todo = Todo::where('user_id', $authUser->id)->whereAnd('id', $id)->first();
        }

        $data['isCompleted'] = '0';
        $todo->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Todo uncompleted successfully'
        ], 200);
    }

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function authenticatedUser()
    {
        return Auth::user();
    }
}
