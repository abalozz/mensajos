<?php

/**
 * Tipo de mensaje (type):
 *   0 - mensaje normal.
 *   1 - mensaje reenviado.
 */
class Message extends Model {

    static $table = 'messages';

    private $user_id;
    private $content;
    private $created_at;

    private $user;

    public function __construct($data, $exists = false)
    {
        if (isset($data['user']) && $data['user'] instanceof User) {
            $this->user_id = $data['user']->get_id();
            $this->user = $data['user'];
        } else {
            $this->user_id = $data['user_id'];
            $this->user = User::where(['id' => $data['user_id']], 1);
        }

        $this->content = $data['content'];
        if (isset($data['created_at'])) {
            $this->created_at = $data['created_at'];
        }

        parent::__construct($data, $exists);
    }

    public function get_user_id()
    {
        return $this->user_id;
    }

    public function get_content()
    {
        return $this->content;
    }

    public function get_created_at()
    {
        return $this->created_at;
    }

    public function get_user()
    {
        return $this->user;
    }

    public function set_user_id($user_id)
    {
        $this->user_id = $user_id;
    }

    public function set_content($content)
    {
        $this->content = $content;
    }

    public function set_created_at($created_at)
    {
        $this->created_at = $created_at;
    }

    public function is_valid()
    {
        if (is_numeric($this->user_id) && $this->content) {
            return true;
        }
        return false;
    }

    public function is_forwarded($user)
    {
        $tl = DB::query('SELECT id FROM timeline WHERE user_id = ? AND message_id = ? LIMIT 1', [$user->get_id(), $this->id]);
        return (isset($tl[0])) ? $tl[0]['id'] : false;
    }

    public function forward($user)
    {
        if ($id = $this->is_forwarded($user)) {
            DB::query('DELETE FROM timeline WHERE id = ? ', [$id]);
        } else {
            DB::query('INSERT INTO timeline (id, user_id, message_id, type) VALUES (null, ?, ?, 1)', [$user->get_id(), $this->id]);
        }
    }

    public function is_from($user)
    {
        return $user->get_id() == $this->user_id;
    }

    public function save()
    {
        if ($this->exists) {
            DB::query('UPDATE ' . self::$table . ' SET user_id = ?, content = ? WHERE id = ?;', [
                $this->user_id, $this->content, $this->id
                ]);
        } else {
            DB::query('INSERT INTO ' . self::$table . ' (id, user_id, content, created_at) VALUES (null, ?, ?, null);', [
                $this->user_id, $this->content
                ]);
            $this->id = DB::lastInsertId();

            DB::query('INSERT INTO timeline (id, user_id, message_id, type) VALUES (null, ?, ?, ?)', [$this->user_id, $this->id, 0]);

            $this->exists = true;
        }
    }

    public function delete()
    {
        if ($this->exists) {
            DB::query('DELETE FROM timeline WHERE message_id = ?', [$this->id]);
            parent::delete();
        }
    }

    public function to_array()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'user' => $this->user->to_array(),
        ];
    }

}
