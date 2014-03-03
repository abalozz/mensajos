<?php

class MessagesController extends Controller {
    
    public function store()
    {
        $message = Message::create([
            'user_id' => Auth::user()->get_id(),
            'content' => $_POST['mensajo'],
            ]);

        header('Location:./');
    }

}