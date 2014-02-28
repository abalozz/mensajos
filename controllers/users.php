<?php

class UsersController extends Controller {
    
    public function index()
    {
        $users = User::all();

        $view = View::make('users/list');
        $view->with('users', $users);
        return $view;
    }

    public function show()
    {
        $user = User::where(['id' => $_GET['id']], 1);

        $view = View::make('users/detail');
        $view->with('user', $user);
        return $view;
    }

    public function follow()
    {
        $user = User::where(['id' => $_GET['id']], 1);
        Auth::user()->follow($user);

        header('Location:?page=users&action=show&id=' . $_GET['id']);
    }

}