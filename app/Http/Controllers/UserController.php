<?php

// namespace App\Http\Controllers;

// use App\Models\User;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Validator;

// class UserController extends Controller
// {
//     public function index()
//     {
//         $users = User::all();
//         return view('users.index', compact('users'));
//     }

//     public function store(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'name' => 'required|string|max:255',
//             'email' => 'required|string|email|max:255|unique:users',
//             'password' => 'required|string|min:6',
//             'user_status' => 'required|in:ผู้ใช้งานทั่วไป,แอดมินผู้ดูแลระบบ',
//         ]);

//         if ($validator->fails()) {
//             return response()->json(['status' => 'error', 'message' => $validator->errors()]);
//         }

//         $user = new User();
//         $user->name = $request->name;
//         $user->email = $request->email;
//         $user->password = Hash::make($request->password);
//         $user->user_status = $request->user_status;
//         $user->save();

//         return response()->json(['status' => 'success', 'message' => 'User created successfully.']);
//     }

//     public function update(Request $request, $id)
//     {
//         $validator = Validator::make($request->all(), [
//             'name' => 'required|string|max:255',
//             'email' => 'required|string|email|max:255|unique:users,email,' . $id,
//             'user_status' => 'required|in:ผู้ใช้งานทั่วไป,แอดมินผู้ดูแลระบบ',
//         ]);

//         if ($validator->fails()) {
//             return response()->json(['status' => 'error', 'message' => $validator->errors()]);
//         }

//         $user = User::find($id);
//         if ($user) {
//             $user->name = $request->name;
//             $user->email = $request->email;
//             $user->user_status = $request->user_status;
//             if ($request->password) {
//                 $user->password = Hash::make($request->password);
//             }
//             $user->save();

//             return response()->json(['status' => 'success', 'message' => 'User updated successfully.']);
//         }

//         return response()->json(['status' => 'error', 'message' => 'User not found.']);
//     }

//     public function destroy($id)
//     {
//         $user = User::find($id);
//         if ($user) {
//             $user->delete();
//             return response()->json(['status' => 'success', 'message' => 'User deleted successfully.']);
//         }

//         return response()->json(['status' => 'error', 'message' => 'User not found.']);
//     }
// }




// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'user_status' => 'required|string',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->user_status = $request->user_status;
        $user->save();

        return response()->json(['status' => 'success', 'message' => 'User created successfully']);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'nullable|string|min:8',
            'user_status' => 'required|string',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->user_status = $request->user_status;
        $user->save();

        return response()->json(['status' => 'success', 'message' => 'User updated successfully']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['status' => 'success', 'message' => 'User deleted successfully']);
    }
}
