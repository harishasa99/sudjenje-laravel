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

    public function destroy($id) {
        try {
            $user = User::find($id);
    
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found.'], 404);
            }
    
            // Proveri da li postoje povezane lekcije, kursevi ili zapisi u lesson_course tabeli
            if ($user->lessons()->count() > 0 || $user->courses()->count() > 0 || $user->lessonCourses()->count() > 0) {
                return response()->json(['success' => false, 'message' => 'Cannot delete user with associated records.'], 422);
            }
    
            $user->delete();
    
            return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
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