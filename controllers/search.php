<?php

class SearchController extends Controller {

    public function index()
    {
        View::add_locals('title', 'Mensajos - Buscador');
        $view = View::make('search');
        return $view;
    }

    public function search()
    {
        $users = User::search($_POST['search-input']);

        View::add_locals('title', 'Mensajos - Buscando "' . $_POST['search-input'] . '"');
        $view = View::make('search');
        $view->with('users', $users);
        $view->with('input', $_POST['search-input']);
        return $view;
    }

}
