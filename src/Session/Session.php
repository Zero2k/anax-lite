<?php

namespace Vibe\Session;

/**
* Session class.
*/
class Session
{
    private $name;

    /**
    * Constructor.
    */
    public function __construct($name = 'MYSESSION')
    {
        $this->name = $name;
    }

    /**
    * Start session.
    */
    public function start()
    {
        // Set session name
        session_name($this->name);
        // Start new session if one alredy exist
        if (!empty(session_id())) {
            session_destroy();
        }
        // Start the session
        session_start();
    }

    /**
    * Destroy session.
    */
    public function destroy()
    {
        session_destroy();
    }

    /**
    * Check if session has key.
    */
    public function has($key)
    {
        return array_key_exists($key, $_SESSION);
    }

    /**
    * Set variable in session.
    */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
    * Get value from session if exist.
    */
    public function get($key, $default = false)
    {
        return (self::has($key)) ? $_SESSION[$key] : $default;
    }

    /**
    * Get value from session if exist and then removes it.
    */
    public function getOnce($key, $default = false)
    {
        $value = $default;
        if ($this->has($key)) {
            $value = $_SESSION[$key];
            unset($_SESSION[$key]);
        }
        return $value;
    }

    /**
    * Delete value from session if exist.
    */
    public function delete($key)
    {
        if (self::has($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
    * Prints the value from the session array.
    */
    public function dump()
    {
        var_dump($_SESSION);
    }
}
