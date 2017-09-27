<?php

namespace Vibe\Admin;

/**
* Admin class.
*/
class Admin
{
    /**
    * Show all users in database
    * @param $resultset Object. The result object from the database
    * @param $limit String. Limit result returned
    * @return $html
    */
    public function showUsers($resultset, $total, $limit, $query, $page)
    {
        $html = <<<EOD
        <form class="form-inline">
            <div class="form-group">
                <input type="text" class="form-control" name="search" placeholder="Search user">
            </div>
            <button type="submit" class="btn btn-default">Search</button>
            <a href="?add" class="btn btn-primary pull-right" role="button" aria-pressed="true">Add user</a>
        </form>
        <hr>
        <div class="table-responsive">
        <table class="table">
        <tr >
        <th>ID</th>
        <th>USERNAME <a href="?search=$query&limit=$limit&page=$page&orderby=username&order=DESC">↓</a><a href="?search=$query&limit=$limit&page=$page&orderby=username&order=ASC">↑</a></th>
        <th>FRIST NAME <a href="?search=$query&limit=$limit&page=$page&orderby=firstname&order=DESC">↓</a><a href="?search=$query&limit=$limit&page=$page&orderby=firstname&order=ASC">↑</a></th>
        <th>LAST NAME <a href="?search=$query&limit=$limit&page=$page&orderby=lastname&order=DESC">↓</a><a href="?search=$query&limit=$limit&page=$page&orderby=lastname&order=ASC">↑</a></th>
        <th>PASSWORD</th>
        <th>EDIT</th>
        </tr >
EOD;

        foreach ($resultset as $result) {
            $html .= <<<EOD
            <tr >
            <td>{$result->id}</td>
            <td>{$result->username}</td>
            <td>{$result->firstname}</td>
            <td>{$result->lastname}</td>
            <td>{$result->password}</td>
            <td><a href="?edit={$result->username}">User</a> | <a href="?password={$result->username}">Password</a> | <a href="?delete={$result->username}">Remove</a></td>
            </tr >
EOD;
        }

        $html .= "</table>";
        $html .= "<hr>";
        $html .= "</div>";
        $html .= <<<EOD
        <nav aria-label="Page navigation">
        <ul class="pagination">
EOD;
        for ($i = 1; $i <= $total; $i++) {
            $html .= "<li><a href='?limit={$limit}&page={$i}'>{$i}</a></li>";
        }
        $html .= <<<EOD
        </ul>
        </nav>
EOD;
        return $html;
    }

    /**
    * Edit single user
    * @param $user Object. 
    * @param $editUser String. Link to edit user
    * @return $html
    */
    public function editUser($user, $editUser)
    {
        $html = <<<EOD
        <form action="{$editUser}" method="POST">
            <div class="form-group">
                <label for="exampleInputUsername">Username</label>
                <input type="text" name="username" class="form-control" value="{$user["username"]}" id="exampleInputUsername" placeholder="Username">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail">Email address</label>
                <input type="email" name="email" class="form-control" value="{$user["email"]}" id="exampleInputEmail" placeholder="Email address">
            </div>
            <div class="form-group">
                <label for="exampleInputFirstname">Firstname</label>
                <input type="text" name="firstname" class="form-control" value="{$user["firstname"]}" id="exampleInputFirstname" placeholder="Firstname">
            </div>
            <div class="form-group">
                <label for="exampleInputLastname">Lastname</label>
                <input type="text" name="lastname" class="form-control" value="{$user["lastname"]}" id="exampleInputLastname" placeholder="Lastname">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
EOD;
        return $html;
    }

    /**
    * Add new user
    * @param $addUSer String. Link to add user
    * @return $html
    */
    public function addUser($addUser)
    {
        $html = <<<EOD
        <form action="{$addUser}" method="POST">
            <div class="form-group">
                <label for="exampleInputUsername">Username</label>
                <input type="text" name="username" class="form-control" id="exampleInputUsername" placeholder="Username">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail">Email address</label>
                <input type="email" name="email" class="form-control" id="exampleInputEmail" placeholder="Email address">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword">Password</label>
                <input type="password" name="password" class="form-control" id="exampleInputPassword" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
EOD;
        return $html;
    }

    /**
    * Change password for user
    * @param $user Object. User object from database
    * @param $changePassword String. Url to change password
    * @return $html
    */
    public function changePassword($user, $changePassword)
    {
        $html = <<<EOD
        <form action="{$changePassword}" method="POST">
            <div class="form-group">
                <label for="exampleInputNewPassword">Username</label>
                <input type="text" name="username" value="{$user["username"]}" class="form-control" id="exampleInputUsername">
            </div>
            <div class="form-group">
                <label for="exampleInputNewPassword">New Password</label>
                <input type="password" name="newPassword" class="form-control" id="exampleInputNewPassword" placeholder="New password">
            </div>
            <div class="form-group">
                <label for="exampleInputNewPassword2">Confirm New Password</label>
                <input type="password" name="confirmPassword" class="form-control" id="exampleInputNewPassword" placeholder="Confirm New Password">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
EOD;
        return $html;
    }

    /**
    * Limit amout of users that is shown
    * @param $total String. Amount of users in database
    * @param $limit String. Amount of users that should been shown on a single page
    * @return $links
    */
    public function pagination($total, $limit)
    {
        $links = <<<EOD
        <nav aria-label="Page navigation">
        <ul class="pagination">
EOD;
        for ($i = 1; $i <= $total; $i++) {
            $links .= "<li><a href='?limit={$limit}&page={$i}'>{$i}</a></li>";
        }
        $links .= <<<EOD
        </ul>
        </nav>
EOD;
        return $links;
    }
}
