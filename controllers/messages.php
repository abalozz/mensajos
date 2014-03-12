<?php

class MessagesController extends Controller {

    static $require_auth = ['store', 'forward'];
    
    public function store()
    {
        $message = Message::create([
            'user' => Auth::user(),
            'content' => filter_var($_POST['content'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            ]);

        return $message->to_json();
    }

    public function forward()
    {
        $message = Message::findOne(['id' => $_GET['id']]);
        $message->forward(Auth::user());

        header('Location:./');
    }

    public function delete()
    {
        $message = Message::findOne(['id' => $_GET['id']]);
        if ($message->is_from(Auth::user())) {
            $message->delete();
        }
    }

}