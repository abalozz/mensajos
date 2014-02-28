<?php

class Message extends Model {

    static $table = 'messages';

    private $user_id;
    private $text;
    private $created_at;

    public function __construct($data, $exists)
    {
        $this->user_id = $data['user_id'];
        $this->text = $data['text'];
        $this->created_at = $data['created_at'];

        parent::__construct($data, $exists);
    }

    public function get_user_id()
    {
        return $this->user_id;
    }

    public function get_text()
    {
        return $this->text;
    }

    public function get_created_at()
    {
        return $this->created_at;
    }

    public function set_user_id($user_id)
    {
        $this->user_id = $user_id;
    }

    public function set_text($text)
    {
        $this->text = $text;
    }

    public function set_created_at($created_at)
    {
        $this->created_at = $created_at;
    }

    public function is_valid()
    {
        if ($this->user_id && $this->text && $this->created_at) {
            return true;
        }
        return false;
    }

    public function save()
    {
        if ($this->exists) {
            DB::query('UPDATE ' . self::$table . ' SET user_id = ?, text = ?, created_at = ? WHERE id = ?;', [
                $this->user_id, $this->text, $this->created_at, $this->id
                ]);
        } else {
            DB::query('INSERT INTO ' . self::$table . ' (id, user_id, text, created_at) VALUES (null, ?, ?, ?);', [
                $this->user_id, $this->text, $this->created_at
                ]);
        }

        parent::save();
    }

}
