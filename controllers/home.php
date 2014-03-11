<?php

class HomeController extends Controller {

    public function index()
    {
        if (Auth::check()) {
            $messages = Auth::user()->timeline();

            $view = View::make('users/detail');
            $view->with('mensajos', 'Mensajos');
            $view->with('messages', $messages);
            $view->with('user', Auth::user());
        } else {
            $view = View::make('login');
        }

        return $view;
    }

    public function login() {
        if (isset($_POST['identity']) && isset($_POST['password'])) {
            if (Auth::login($_POST['identity'], $_POST['password'])) {
                die(header('Location:index.php'));
            }
        }

        $view = View::make('login');
        $view->with('errors', 'El usuario o la contraseña no coinciden.');
        if (isset($_POST['identity'])) {
            $view->with('identity', $_POST['identity']);
        }
        return $view;
    }

    public function logout()
    {
        Auth::logout();
        header('Location:./');
    }

    public function reg()
    {
        if (Auth::check()) {
            header('Location:./');
        } else {
            $view = View::make('reg');
            return $view;
        }
    }

    public function store() {
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
            $user = Auth::create([
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                ]);

            if ($user instanceof User && Auth::login($_POST['email'], $_POST['password'])) {
                die(header('Location:index.php'));
            }
        }

        $view = View::make('reg');
        $view->with('errors', 'Los datos introducidos son erróneos. La contraseña debe tener al menos 6 caracteres.');
        if (isset($_POST['username'])) {
            $view->with('username', $_POST['username']);
        }
        if (isset($_POST['email'])) {
            $view->with('email', $_POST['email']);
        }
        return $view;
    }

}