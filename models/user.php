<?php

class User extends Model {

    static $table = 'users';

    private $username;
    private $email;
    private $password;
    private $name;
    private $profile_image = 'img/profile-image-default.jpg';
    private $header_image = 'img/header-image-default.jpg';

    private $number_of_messages = 0;

    private $followers = [];
    private $followings = [];
    private $number_of_followers;
    private $number_of_followings;
    private $new_follows = [];
    private $new_unfollows = [];

    public function __construct($data, $exists = false)
    {
        parent::__construct($data, $exists);

        $this->username = $data['username'];
        $this->email = $data['email'];
        if (isset($data['password'])) {
            $this->password = $data['password'];
        }
        if (isset($data['name'])) {
            $this->name = $data['name'];
        } else {
            $this->name = $data['username'];
        }
        if (isset($data['profile_image'])) {
            $this->profile_image = $data['profile_image'];
        }
        if (isset($data['header_image'])) {
            $this->header_image = $data['header_image'];
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
        if ($this->id != $user->get_id())
            $this->new_follows[] = $user;
    }

    public function unfollow($user)
    {
        $this->new_unfollows[] = $user;
    }

    public function toggle_follow($user)
    {
        if ($this->is_following_to($user)) {
            $this->unfollow($user);
        } else {
            $this->follow($user);
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
            DB::query('UPDATE ' . self::$table . ' SET username = ?, email = ?,
                password = ?, name = ?, profile_image = ?, header_image = ? WHERE id = ?;', [
                $this->username, $this->email, $this->password, $this->name, $this->profile_image, $this->header_image, $this->id
                ]);
        } else {
            DB::query('INSERT INTO ' . self::$table . ' (id, username, email, password, name) VALUES (null, ?, ?, ?, ?);', [
                $this->username, $this->email, $this->password, $this->name
                ]);
        }

        // Guarda en la base de datos los nuevos seguidores.
        if (count($this->new_follows) > 0) {
            $sql = 'INSERT INTO follows (id, user_id, followed_id) VALUES ';
            foreach ($this->new_follows as $followed) {
                $sql .= sprintf('(null, %s, %s), ', $this->id, $followed->get_id());
                $this->number_of_followers++;
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
                        $this->number_of_followers--;
                    }
                }
            }
            $this->new_unfollows = [];
        }

        parent::save();
    }

}
