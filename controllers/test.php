<?php

// Nota: Solo para pruebas
class TestController extends Controller {

    function create_user()
    {
        $user_data = [
            'username' => 'pepito',
            'email' => 'pepito@email.net',
            'password' => '12345678',
            ];

        $user = Auth::create($user_data);

        var_dump($user);

        return '';
    }

    public function login()
    {
        $username = 'pepito';
        $password = '12345678';

        $is_auth = Auth::login($username, $password);

        if ($is_auth) {
            return 'login con éxito =D';
        } else {
            return 'falló el login :(';
        }
    }

    public function index()
    {
        if (Auth::check()) {
            return 'Has iniciado sesión. Bienvenido ' . Auth::user()->get_username();
        } else {
            return 'No has iniciado sesión';
        }
    }

    public function logout()
    {
        Auth::logout();
        return 'Sesión cerrada.';
    }

    public function users()
    {
        $users = User::where(['id' => 1]);
        var_dump($users);

        $user = User::where(['id' => 1], 1);
        var_dump($user);

        return '';
    }

}