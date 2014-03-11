<?php

class UsersController extends Controller {

    static $require_auth = ['follow', 'followers', 'followings'];
    
    public function index()
    {
        $users = User::all();

        View::add_locals('title', 'Mensajos - Lista de usuarios');
        $view = View::make('users/list');
        $view->with('users', $users);
        return $view;
    }

    public function show()
    {
        $user = User::findOne(['id' => $_GET['id']]);
        $messages = $user->timeline();

        View::add_locals('title', 'Mensajos - Perfil de ' . $user->get_name());
        $view = View::make('users/detail');
        $view->with('user', $user);
        $view->with('messages', $messages);
        return $view;
    }

    public function follow()
    {
        if (Auth::user()->get_id() != $_GET['id']) {
            $user = User::findOne(['id' => $_GET['id']]);
            Auth::user()->follow($user);
            Auth::user()->save();
        }

        header('Location:./');
    }

    public function followers()
    {
        $user = User::findOne(['id' => $_GET['id']]);
        $followers = $user->get_followers();

        View::add_locals('title', 'Mensajos - Seguidores de ' . $user->get_name());
        $view = View::make('users/list');
        $view->with('users', $followers);
        return $view;
    }

    public function followings()
    {
        $user = User::findOne(['id' => $_GET['id']]);
        $followings = $user->get_followings();

        View::add_locals('title', 'Mensajos - Siguiendo a ' . $user->get_name());
        $view = View::make('users/list');
        $view->with('users', $followings);
        return $view;
    }

}