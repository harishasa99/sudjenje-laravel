<?php

namespace App\Http\Controllers;

use App\Models\Notifications;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    //
    public function index()
    {
        $users = User::all();
        return view('admin.adminApproving', compact('users'));

    }

    public function update(Request $request, $id){

        $user = User::find($id);
        $user->approved = 1;
        $user->save();
        Session::flash('approved', 'User approved');
        return redirect()->route('admin.index');
    }

    public function destroy($id){
        $user = User::find($id);
        $user->delete();
        Session::flash('deleted', 'User deleted');
        return redirect()->route('admin.index');
    }

    public function search(Request $request){
        $search = $request->get('search');
        if($search == null){
            $users = User::all();
            return view('admin.adminApproving', compact('users'));
        }
        $users = User::where('name', 'like', '%'.$search.'%')->get();
        return view('admin.adminApproving', compact('users'));
    }

    public function notification(){
        $user = auth()->user();
        Notifications::create([
            'user_id' => $user->jmbg,
            'title' => request('title'),
            'message' => request('message'),
        ]);
        Session::flash('notification', 'Notification sent');
        return redirect()->route('admin.index');
    }
}