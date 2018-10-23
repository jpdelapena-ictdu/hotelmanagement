<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Auth::user()->role == 2, 404);
        $users = User::where('role', 2)->orderBy('updated_at', 'desc')->get();
        
        return view('users.list')
            ->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Auth::user()->role == 2, 404);
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:191',
            'email' => 'required|email|unique:users,email'
        ]);

        $user = New User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt('123456789');
        $user->role = 2;
        $user->save();

        \Alert::success('User has been registered.')->flash();
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('users.edit')
            ->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if($request->email == $user->email) {
            $this->validate($request, [
                'name' => 'required|max:191'
            ]);
        } else {
            $this->validate($request, [
                'name' => 'required|max:191',
                'email' => 'required|email|unique:users,email'
            ]);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        \Alert::success('User has been changed.')->flash();
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        \Alert::error('User has been deleted.')->flash();
        return redirect()->route('users.index');
    }

    public function userResetPassword($id) {
        $user = User::find($id);
        $user->password = bcrypt('123456789');
        $user->save();

        // show a success message
        \Alert::success('Password has been reset.')->flash();
        return redirect()->back();
    }
}
