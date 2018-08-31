<?php

namespace App\Http\Controllers;

use Validator;
use DataTables;
use App\Todolist;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function getData()
    {
        $data = Todolist::select(['id', 'name', 'email']);
        return DataTables::of($data)
            ->addColumn('actions', function($data) {
                return '
                    <a data-id="'.$data->id.'" data-name="'.$data->name.'" data-email="'.$data->email.'" class="btn btn-warning btn-edit">Edit</a>
                    <a data-id="'.$data->id.'" class="btn btn-danger btn-destroy">Hapus</a>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function index()
    {
        return view('home.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email',
        ]);

        if($validator->passes()){
           $todo = Todolist::create($request->all()); 
            return response($todo);
        }

        return response()->json(['errors' => $validator->errors()->all()]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email',
        ]);

        if($validator->passes()){
           $todo = Todolist::findOrFail($id); 
           $todo->update($request->all());
           return response($todo);
        }

        return response()->json(['errors' => $validator->errors()->all()]);
    }

    public function destroy(Request $request, $id)
    {
        if($request->ajax())
        {
            $todo = Todolist::findOrFail($id);
            $todo->delete();
        }
    }
}
