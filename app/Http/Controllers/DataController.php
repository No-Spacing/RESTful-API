<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;
use Illuminate\Support\Facades\DB;
use \Validator;

class DataController extends Controller
{
    public function users(Request $request)
    {
        $posts = Data::when($request->search, function($query, $search){
            $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%');
        });
        return response()->json($posts->orderBy('created_at','asc')->paginate(5)->withQueryString());
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required|regex:/^[\pL\s]+$/u',
            'email' => 'required|email|unique:data',
            'bdate' => 'required',  
        ]);
      
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }else{
            $data = new Data;
            $data->name = $request->name;
            $data->email = $request->email;
            $data->bdate = $request->bdate;
            $create = $data->save();

            return response()->json($create, 200);
        }           
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required|regex:/^[\pL\s]+$/u',
            'email' => 'required|email|unique:data',
            'bdate' => 'required',  
        ]);
      
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }else{
            $data = Data::find($request->id);
            $data->name = $request->name;
            $data->email = $request->email;
            $data->bdate = $request->bdate;
            $update = $data->save();

            return response()->json($update, 200);
        }
    }

    public function delete($id){
        $data = Data::destroy($id);
        return response()->json($data, 200);
    }
}
