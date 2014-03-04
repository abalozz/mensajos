<?php

class MessagesController extends Controller {

    static $require_auth = ['store'];
    
    public function store()
    {
        $message = Message::create([
            'user_id' => Auth::user()->get_id(),
            'content' => $_POST['mensajo'],
            ]);

        header('Location:./');
    }

}