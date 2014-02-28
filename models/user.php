<?php

class User extends Model {

    static $table = 'users';

    private $username;
    private $email;
    private $password;

    public function __construct($data, $exists = false)
    {
        $this->username = $data['username'];
        $this->email = $data['email'];
        $this->password = $data['password'];

        parent::__construct($data, $exists);
    }

    public function get_username()
    {
        return $this->username;
    }

    public function get_email()
    {
        return $this->email;
    }

    public function set_username($value)
    {
        $this->username = $value;
    }

    public function set_email($value)
    {
        $this->email = $value;
    }

    public function follow($user)
    {
        if ($user->is_followed_by(Auth::user())) {
            DB::query('DELETE FROM follows WHERE user_id = ? AND followed_id = ?', [$this->id, $user->id]);
        } else {
            DB::query('INSERT INTO follows (id, user_id, followed_id) VALUES (null, ?, ?)', [$this->id, $user->id]);
        }
    }

    public function is_followed_by($user)
    {
        $follow = DB::query('SELECT id FROM follows WHERE user_id = ? AND followed_id = ?', [$user->id, $this->id], 1);
        
        return count($follow) ? true : false;
    }

    public function is_valid()
    {
        if ($this->username && $this->password && $this->email) {
            return true;
        }
        return false;
    }

    public function save()
    {
        if ($this->exists) {
            DB::query('UPDATE ' . self::$table . ' SET username = ?, email = ?, password = ? WHERE id = ?;', [
                $this->username, $this->email, $this->password, $this->id
                ]);
        } else {
            DB::query('INSERT INTO ' . self::$table . ' (id, username, email, password) VALUES (null, ?, ?, ?);', [
                $this->username, $this->email, $this->password
                ]);
        }

        parent::save();
    }

}
