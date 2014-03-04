<?php

class UsersController extends Controller {

    static $require_auth = ['follow', 'followers', 'followings'];
    
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
        if (Auth::user()->get_id() != $_GET['id']) {
            $user = User::where(['id' => $_GET['id']], 1);
            Auth::user()->toggle_follow($user);
            Auth::user()->save();
        }

        header('Location:?page=users&action=show&id=' . $_GET['id']);
    }

    public function followers()
    {
        $user = User::where(['id' => $_GET['id']], 1);
        $followers = $user->get_followers();

        $view = View::make('users/list');
        $view->with('users', $followers);
        return $view;
    }

    public function followings()
    {
        $user = User::where(['id' => $_GET['id']], 1);
        $followings = $user->get_followings();

        $view = View::make('users/list');
        $view->with('users', $followings);
        return $view;
    }

}