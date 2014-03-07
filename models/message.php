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
        $this->user_id = $data['user']->get_id();
        $this->content = $data['content'];
        if (isset($data['created_at'])) {
            $this->created_at = $data['created_at'];
        }

        $this->user = $data['user'];

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


    static function timeline($user_id)
    {
        $messages = DB::query('SELECT u.username, u.email, u.name, u.profile_image, m.id message_id, m.user_id, m.content, m.created_at
            FROM follows f, timeline t, messages m, users u
            WHERE f.user_id = ?
            AND (f.followed_id = t.user_id OR t.user_id = f.user_id)
            AND t.message_id = m.id
            AND m.user_id = u.id
            GROUP BY m.id
            ORDER BY t.id DESC', [$user_id]);

        foreach ($messages as $k => $v) {
            $user = [
                'id' => $v['user_id'],
                'username' => $v['username'],
                'email' => $v['email'],
                'name' => $v['name'],
                'profile_image' => $v['profile_image'],
            ];
            $message = [
                'id' => $v['message_id'],
                'user_id' => $v['user_id'],
                'content' => $v['content'],
                'created_at' => $v['created_at'],
                'user' => new User($user),
            ];
            $messages[$k] = new Message($message);
        }

        return $messages;
    }

}
