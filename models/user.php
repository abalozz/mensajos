<?php

class User extends Model {

    static $table = 'users';

    static $max_id_of_default_images = 7;

    private $username;
    private $email;
    private $password;
    private $name;
    private $profile_image;
    private $header_image;

    private $number_of_messages = 0;

    private $followers = [];
    private $followings = [];
    private $number_of_followers;
    private $number_of_followings;
    private $new_follows = [];
    private $new_unfollows = [];

    private $is_password_crypted = false;

    public function __construct($data, $exists = false)
    {
        parent::__construct($data, $exists);

        $this->username = $data['username'];
        $this->email = $data['email'];
        if (isset($data['password'])) {
            $this->password = $data['password'];
            if ($exists) {
                $is_password_crypted = true;
            }
        }
        if (isset($data['name'])) {
            $this->name = $data['name'];
        } else {
            $this->name = $data['username'];
        }
        if (isset($data['profile_image'])) {
            $this->profile_image = $data['profile_image'];
        } else {
            $this->profile_image = 'img/default-profile-images/' . mt_rand(0, self::$max_id_of_default_images) . '.png';
        }
        if (isset($data['header_image'])) {
            $this->header_image = $data['header_image'];
        } else {
            $this->header_image = 'img/default-header-images/' . mt_rand(0, self::$max_id_of_default_images) . '.png';
        }

        if ($exists) {
            $followings = DB::query('SELECT u.id, u.username, u.email FROM users u, follows f WHERE f.user_id = ? AND u.id = f.followed_id', [$this->id]);
            $this->number_of_followings = count($followings);
            foreach ($followings as $follow) {
                $this->followings[] = new User($follow);
            }

            $followers = DB::query('SELECT u.id, u.username, u.email FROM users u, follows f WHERE f.followed_id = ? AND u.id = f.user_id', [$this->id]);
            $this->number_of_followers = count($followers);
            foreach ($followers as $follower) {
                $this->followers[] = new User($follower);
            }

            $number_of_messages = DB::query('SELECT COUNT(id) count FROM timeline WHERE user_id = ?', [$this->id]);
            $this->number_of_messages = $number_of_messages[0]['count'];
        }
    }

    public function get_username()
    {
        return $this->username;
    }

    public function get_email()
    {
        return $this->email;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function get_profile_image()
    {
        return $this->profile_image;
    }

    public function get_header_image()
    {
        return $this->header_image;
    }

    public function get_followings()
    {
        return $this->followings;
    }

    public function get_followers()
    {
        return $this->followers;
    }

    public function get_number_of_followings()
    {
        return $this->number_of_followings;
    }

    public function get_number_of_followers()
    {
        return $this->number_of_followers;
    }

    public function get_number_of_messages()
    {
        return $this->number_of_messages;
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
        if ($this->is_following_to($user)) {
            $this->new_unfollows[] = $user;
        } else {
            if ($this->id != $user->get_id()) {
                $this->new_follows[] = $user;
            }$this->follow($user);
        }
    }

    public function is_following_to($user)
    {
        if ($this->followings) {
            foreach ($this->followings as $follow)
                if ($follow->get_id() == $user->get_id())
                    return true;
        }
        return false;
    }

    public function timeline()
    {
        $messages = DB::query('SELECT u.username, u.email, u.name, u.profile_image, m.id message_id, m.user_id, m.content, m.created_at
            FROM follows f, timeline t, messages m, users u
            WHERE ((f.user_id = ? AND f.followed_id = t.user_id) OR t.user_id = ?)
            AND t.message_id = m.id
            AND m.user_id = u.id
            GROUP BY m.id
            ORDER BY t.id DESC', [$this->id, $this->id]);

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

    public function is_valid()
    {
        if ($this->username &&
            filter_var($this->username, FILTER_SANITIZE_STRING) == $this->username &&
            strlen($this->password) >= 6 &&
            filter_var($this->email, FILTER_VALIDATE_EMAIL)
        ) {
            if (User::findOne(['username' => $this->username]) || User::findOne(['email' => $this->email])) {
                return false;
            }
            return true;
        }
        return false;
    }

    public function save()
    {
        if ($this->exists) {
            DB::query('UPDATE ' . self::$table . ' SET username = ?, email = ?,
                name = ?, profile_image = ?, header_image = ? WHERE id = ?;', [
                $this->username, $this->email, $this->name, $this->profile_image, $this->header_image, $this->id
                ]);
        } else {
           $a = DB::query('INSERT INTO ' . self::$table . ' (id, username, email, password, name, profile_image, header_image)
                    VALUES (null, ?, ?, "", ?, ?, ?);', [
                $this->username, $this->email, $this->name, $this->profile_image, $this->header_image
                ]);
           // var_dump($a);
           //  die;
        }

        parent::save();

        // Contraseña
        if (isset($this->password) && $this->password && !$this->is_password_crypted) {
            $this->password = encrypt($this->password);
            $this->is_password_crypted = true;

            DB::query('UPDATE ' . self::$table . ' SET password = ? WHERE id = ?;', [
                $this->password, $this->id
                ]);
        }

        // Guarda en la base de datos los nuevos seguidores.
        if (count($this->new_follows) > 0) {
            $sql = 'INSERT INTO follows (id, user_id, followed_id) VALUES ';
            foreach ($this->new_follows as $followed) {
                $sql .= sprintf('(null, %s, %s), ', $this->id, $followed->get_id());
                $this->number_of_followings++;
            }
            $sql = substr($sql, 0, -2);

            DB::query($sql);

            $this->followings = array_merge($this->followings, $this->new_follows);
            $this->new_follows = [];
        }

        // Elimina de la base de datos los seguidores a los que se ha dejado de seguir.
        if (count($this->new_unfollows) > 0) {
            $sql = 'DELETE FROM follows WHERE ';
            foreach ($this->new_unfollows as $followed) {
                $sql .= sprintf('(user_id = %s AND followed_id = %s) OR ', $this->id, $followed->get_id());
            }
            $sql = substr($sql, 0, -3);
            
            DB::query($sql);

            foreach ($this->new_unfollows as $unfollowed) {
                foreach ($this->followings as $key => $followed) {
                    if ($unfollowed->get_id() == $followed->get_id()) {
                        unset($this->followings[$key]);
                        $this->number_of_followings--;
                    }
                }
            }
            $this->new_unfollows = [];
        }
    }

    public function to_array()
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'name' => $this->name,
            'profile_image' => $this->profile_image,
            'header_image' => $this->header_image,
            'number_of_messages' => $this->number_of_messages,
            'number_of_followers' => $this->number_of_followers,
            'number_of_followings' => $this->number_of_followings,
        ];
    }

}
