<?php

class MessagesController extends Controller {

    static $require_auth = ['store', 'forward'];
    
    public function store()
    {
        $message = Message::create([
            'user_id' => Auth::user()->get_id(),
            'content' => $_POST['mensajo'],
            ]);

        header('Location:./');
    }

    public function forward()
    {
        $message = Message::where(['id' => $_GET['id']], 1);
        $message->forward(Auth::user());

        header('Location:./');
    }

}