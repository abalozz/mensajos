<?php

class HomeController extends Controller {

    public function index()
    {
        if (Auth::check()) {
            $messages = Message::timeline(Auth::user()->get_id());

            $view = View::make('home');
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

}