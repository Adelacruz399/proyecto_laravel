<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Auth;
class UserController extends Controller
{
    public function index(Request $request)
    {
    }
    public function create()
    {
        return view('createUser');
    }
    public function store(Request $request)
    {
        $datosUser=request()->except('_token');
        $datosUser['password']=Hash::make($request->dni);
        User::insert($datosUser);
        return redirect('/');
        
    }
    public function show(Servicios $servicios)
    {
    }
    public function edit($id)
    {
        $user=User::findOrFail($id);
        return view('editUser', compact('user'));
    }
    public function update(Request $request, $id)
    {
        $datosUser=request()->except('_token','_method');
        $datosUser['password']=Hash::make($request->dni);
        User::where('id','=',$id)->update($datosUser);
        //$user=User::findOrFail($id);
        return redirect('/');
    }
    public function destroy($id)
    {
        $users=User::findOrFail($id);

            User::destroy($id);


        return redirect('/');
    }
}