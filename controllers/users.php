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
        $user = User::findOne(['id' => $_GET['id']]);
        $messages = $user->timeline();

        $view = View::make('users/detail');
        $view->with('user', $user);
        $view->with('messages', $messages);
        return $view;
    }

    public function follow()
    {
        if (Auth::user()->get_id() != $_GET['id']) {
            $user = User::findOne(['id' => $_GET['id']]);
            Auth::user()->toggle_follow($user);
            Auth::user()->save();
        }

        header('Location:?page=users&action=show&id=' . $_GET['id']);
    }

    public function followers()
    {
        $user = User::findOne(['id' => $_GET['id']]);
        $followers = $user->get_followers();

        $view = View::make('users/list');
        $view->with('users', $followers);
        return $view;
    }

    public function followings()
    {
        $user = User::findOne(['id' => $_GET['id']]);
        $followings = $user->get_followings();

        $view = View::make('users/list');
        $view->with('users', $followings);
        return $view;
    }

}