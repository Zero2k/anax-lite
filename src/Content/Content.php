<?php

namespace Vibe\Content;

/**
* Content class.
*/
class Content
{
    /**
    * Show all content in database
    * @param $resultset Object. The result object from the database
    * @return $html
    */
    public function showContent($resultset)
    {
        $html = <<<EOD
        <a href="?add" class="btn btn-primary" role="button" aria-pressed="true">Add content</a>
        <hr>
        <div class="table-responsive">
        <table class="table">
        <tr >
        <th>ID</th>
        <th>PATH</th>
        <th>SLUG</th>
        <th>TITLE</th>
        <th>PUBLISHED</th>
        <th>CREATED</th>
        <th>DELETED</th>
        <th>ACTION</th>
        </tr >
EOD;

        foreach ($resultset as $result) {
            $html .= <<<EOD
            <tr >
            <td><a href="?preview={$result->id}">{$result->id}</a></td>
            <td>{$result->path}</td>
            <td>{$result->slug}</td>
            <td>{$result->title}</td>
            <td>{$result->published}</td>
            <td>{$result->created}</td>
            <td>{$result->deleted}</td>
            <td><a href="?preview={$result->id}">Preview</a> | <a href="?edit={$result->id}">Edit</a> | <a href="?delete={$result->id}">Delete</a></td>
            </tr >
EOD;
        }

        $html .= <<<EOD
        </table>
        </div>
EOD;
        return $html;
    }

    public function previewContent($title, $data, $createdAt, $type, $filter)
    {
            $html = <<<EOD
        <h1>{$title}</h1>
        <div class="content">{$data}</div>
        <dl class="dl-horizontal">
            <dt>CreatedAt:</dt>
            <dd>{$createdAt}</dd>
            <dt>Type:</dt>
            <dd>{$type}</dd>
            <dt>Filter:</dt>
            <dd>{$filter}</dd>
        </dl>
EOD;
        return $html;
    }

    public function addContent($createContent, $currentDate, $type)
    {
        $html = <<<EOD
        <form action="{$createContent}" method="POST">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control" placeholder="Title" id="title" required autofocus>
            </div>
            <div class="form-group">
                <label for="path">Path</label>
                <input type="text" name="path" class="form-control" placeholder="Path" id="path" autofocus>
            </div>
            <div class="form-group">
                <label for="data">Text</label>
                <textarea class="form-control" rows="15" name="data"></textarea>
            </div>
            <div class="form-group">
                <label for="published">Published</label>
                <input type="date" name="published" class="form-control" value="{$currentDate}" id="published" readonly>
            </div>
            <input type="hidden" name="type" class="form-control" value="{$type}" id="type">
            <div class="form-group">
            <label for="type">Filter</label>
            <label class="checkbox-inline">
            <input type="checkbox" name="filter[]" value="bbcode" checked> bbcode
            </label>
            <label class="checkbox-inline">
            <input type="checkbox" name="filter[]" value="nl2br" checked> nl2br
            </label>
            <label class="checkbox-inline">
            <input type="checkbox" name="filter[]" value="clickable"> clickable
            </label>
            <label class="checkbox-inline">
            <input type="checkbox" name="filter[]" value="markdown"> markdown
            </label>
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Add content</button>
        </form>
EOD;
        return $html;
    }

    public function editContent($resultset, $editContent, $currentDate)
    {
        foreach ($resultset as $result) {
            $html = <<<EOD
        <form action="{$editContent}" method="POST">
            <input type="hidden" name="id" class="form-control" id="id" value="{$result->id}">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control" placeholder="Title" id="title" value="{$result->title}" required autofocus>
            </div>
            <div class="form-group">
                <label for="path">Path</label>
                <input type="text" name="path" class="form-control" placeholder="Path" id="path" value="{$result->path}">
            </div>
            <div class="form-group">
                <label for="data">Text</label>
                <textarea class="form-control" rows="15" name="data">{$result->data}</textarea>
            </div>
            <div class="form-group">
                <label for="published">Published (2017-10-05 15:05:25)</label>
                <input type="date" name="published" class="form-control" value="{$result->published}" id="published">
            </div>
            <div class="form-group">
                <label for="updated">Updated</label>
                <input type="date" name="updated" class="form-control" value="{$currentDate}" id="updated" readonly>
            </div>
            <div class="form-group">
                <label for="filter">Filter (bbcode,nl2br,clickable,markdown)</label>
                <input type="text" name="filter" class="form-control" value="{$result->filter}" id="filter">
            </div>
            <div class="form-group">
                <label for="deleted">Deleted (1 = Deleted | 0 = Published)</label>
                <input type="number" name="deleted" class="form-control" value="{$result->deleted}" id="deleted">
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Update content</button>
        </form>
EOD;
        }
        
        return $html;
    }

    public function blogList($resultset)
    {
        $html = <<<EOD
            <div>
EOD;
        foreach ($resultset as $result) {
            $html .= <<<EOD
            <div class="panel panel-default">
            <img src="https://dummyimage.com/900x400/000/fff" alt="..." class="img-responsive">
            <div class="panel-body">
                <h2>{$result->title}</h2>
                <a href="blog/{$result->slug}" class="btn btn-primary">Read More →</a>
            </div>
            <div class="panel-footer">Published: {$result->published}</div>
            </div>
EOD;
        }
        $html .= <<<EOD
            </div>
EOD;

        return $html;
    }

    public function blogContent($data, $published)
    {
            $html = <<<EOD
            <div class="content">{$data}</div>
            <div>Published: {$published}</div>
EOD;
        return $html;
    }

    public function pageList($resultset)
    {
        $html = <<<EOD
            <div>
EOD;
        foreach ($resultset as $result) {
            $html .= <<<EOD
            <div class="panel panel-default">
            <div class="panel-body">
                <h2>{$result->title}</h2>
                <a href="page/{$result->slug}" class="btn btn-primary">Read More →</a>
            </div>
            <div class="panel-footer">Created: {$result->created}</div>
            </div>
EOD;
        }
        $html .= <<<EOD
            </div>
EOD;

        return $html;
    }

    public function pageContent($data)
    {
            $html = <<<EOD
            <div class="content">{$data}</div>
EOD;
        return $html;
    }

    public function slugify($str) 
    {
        $str = mb_strtolower(trim($str));
        $str = str_replace(array('å','ä','ö'), array('a','a','o'), $str);
        $str = preg_replace('/[^a-z0-9-]/', '-', $str);
        $str = trim(preg_replace('/-+/', '-', $str), '-');
        return $str;
    }
}
