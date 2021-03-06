<?php

namespace Vibe\Webshop;

/**
* Webshop class.
*/
class Webshop
{
    /**
    * Show all products in database
    * @param $resultset Object. The result object from the database
    * @return $html
    */
    public function productsList($resultset)
    {
        $html = <<<EOD
        <a href="?add" class="btn btn-primary" role="button" aria-pressed="true">Add product</a>
        <hr>
        <div class="table-responsive">
        <table class="table">
        <tr >
        <th>ID</th>
        <th>CATEGORY</th>
        <th>TITLE</th>
        <th>PRICE</th>
        <th>AMOUNT</th>
        <th>ACTION</th>
        </tr >
EOD;

        foreach ($resultset as $result) {
            $html .= <<<EOD
            <tr >
            <td>{$result->id}</td>
            <td>{$result->category}</td>
            <td><a href="?preview={$result->id}">{$result->title}</a></td>
            <td>{$result->price} Kr</td>
            <td>{$result->amount}</td>
            <td><a href="?edit={$result->id}">Edit</a> | <a href="?delete={$result->id}">Delete</a></td>
            </tr >
EOD;
        }

        $html .= <<<EOD
        </table>
        </div>
EOD;
        return $html;
    }

    /**
    * Preview single product
    * @param $resultset Object. The result object from the database
    * @return $html
    */
    public function productPreview($resultset)
    {
        foreach ($resultset as $result) {
            $html = <<<EOD
            <div class="row">
                <div class="col-md-6">
                    <img src="https://dustinweb.azureedge.net/image/{$result->image}" class="img-responsive" alt="Responsive image">
                </div>
                <div class="col-md-6">
                    <h2>{$result->title}</h2>
                    <h3>Category: {$result->category}</h3>
                    <hr>
                    <p>{$result->price} Kr</p>
                    <a class="btn btn-success" href="#" role="button">Buy</a>
                    <a class="btn btn-default" href="#" role="button">Compare</a>
                </div>
            </div>
            <hr>
            <div class="col-md-12">
                <div class="test">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified" role="tablist">
                    <li role="presentation" class="active"><a href="#details" aria-controls="details" role="tab" data-toggle="tab">Details</a></li>
                    <li role="presentation"><a href="#comments" aria-controls="comments" role="tab" data-toggle="tab">Comments</a></li>
                    <li role="presentation"><a href="#accessories" aria-controls="accessories" role="tab" data-toggle="tab">Suitable accessories</a></li>
                </ul>
                
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="details">{$result->description}</div>
                    <div role="tabpanel" class="tab-pane" id="comments">...</div>
                    <div role="tabpanel" class="tab-pane" id="accessories">...</div>
                </div>
                </div>
            </div>
EOD;
        }

        return $html;
    }

    /**
    * View to add product
    * @param $addProduct String. Processing route to add product
    * @param $categories Object. All categories from the database
    * @return $html
    */
    public function addProduct($addProduct, $categories) 
    {
        $html = <<<EOD
        <form action="{$addProduct}" method="POST">
            <div class="form-group">
                <label for="exampleInputTitle">Title</label>
                <input type="text" name="title" class="form-control" id="exampleInputTitle" placeholder="Title">
            </div>
            <div class="form-group">
                <label for="exampleInputImage">Image URL</label>
                <input type="text" name="url" class="form-control" id="exampleInputImage" placeholder="Image">
            </div>
            <div class="form-group">
                <label for="exampleInputCategory">Category</label>
                <select class="form-control" name="categories">
EOD;
        foreach ($categories as $category) {
            $html .= <<<EOD
                    <option value={$category->id}>{$category->category}</option>
EOD;
        }
            $html .= <<<EOD
                </select>
            </div>
            <div class="form-group">
                <label for="exampleInputPrice">Price</label>
                <input type="text" name="price" class="form-control" id="exampleInputPrice" placeholder="Price">
            </div>
            <div class="form-group">
                <label for="exampleInputDescription">Description</label>
                <textarea class="form-control" rows="5" name="description"></textarea>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
EOD;
        return $html;
    }

    /**
    * View to edit product
    * @param $editProduct String. Processing route to edit product
    * @param $resultset Object. The result object from the database
    * @param $categories Object. All categories from the database
    * @return $html
    */
    public function editProduct($editProduct, $resultset, $categories) 
    {
        $html = <<<EOD
        <form action="{$editProduct}" method="POST">
            <input type="hidden" name="id" class="form-control" id="exampleInputid" value="{$resultset[0]->id}">
            <div class="form-group">
                <label for="exampleInputTitle">Title</label>
                <input type="text" name="title" class="form-control" id="exampleInputTitle" value="{$resultset[0]->title}" placeholder="Title">
            </div>
            <div class="form-group">
                <label for="exampleInputImage">Image URL</label>
                <input type="text" name="url" class="form-control" id="exampleInputImage" value="{$resultset[0]->image}" placeholder="Image">
            </div>
            <div class="form-group">
                <label for="exampleInputPrice">Price</label>
                <input type="text" name="price" class="form-control" id="exampleInputPrice" value="{$resultset[0]->price}" placeholder="Price">
            </div>
            <div class="form-group">
                <label for="type">Category</label>
EOD;
        foreach ($categories as $category) {
                $html .= <<<EOD
                <label class="radio-inline">
                <input type="radio" name="categories" value="{$category->id}" {$this->checked($category->category, $resultset[0]->category)}> {$category->category}
                </label>
EOD;
        }
            $html .= <<<EOD
            </div>
            <div class="form-group">
                <label for="exampleInputDescription">Description</label>
                <textarea class="form-control" rows="5" name="description">{$resultset[0]->description}</textarea>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
EOD;
        return $html;
    }

    /**
    * View to see all categories
    * @param $resultset Object. The result object from the database
    * @return $html
    */
    public function categoryList($resultset)
    {
        $html = <<<EOD
        <a href="?add" class="btn btn-primary" role="button" aria-pressed="true">Add category</a>
        <hr>
        <div class="table-responsive">
        <table class="table">
        <tr >
        <th>ID</th>
        <th>CATEGORY</th>
        <th>ACTION</th>
        </tr >
EOD;

        foreach ($resultset as $result) {
            $html .= <<<EOD
            <tr >
            <td>{$result->id}</td>
            <td>{$result->category}</td>
            <td><a href="?edit={$result->id}">Edit</a> | <a href="?delete={$result->id}">Delete</a></td>
            </tr >
EOD;
        }

        $html .= <<<EOD
        </table>
        </div>
EOD;
        return $html;
    }

    /**
    * View to add category
    * @param $addCategory String. Processing route to add category
    * @return $html
    */
    public function addCategory($addCategory)
    {
        $html = <<<EOD
        <form action="{$addCategory}" method="POST">
            <div class="form-group">
                <label for="exampleInputCategory">Category</label>
                <input type="text" name="category" class="form-control" id="exampleInputCategory" placeholder="Category">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
EOD;
        return $html;
    }

    /**
    * View to edit category
    * @param $editCategory String. Processing route to edit category
    * @param $resultset Object. The result object from the database
    * @return $html
    */
    public function editCategory($editCategory, $resultset)
    {
        $html = <<<EOD
        <form action="{$editCategory}" method="POST">
            <div class="form-group">
                <input type="hidden" name="id" class="form-control" id="exampleInputId" value="{$resultset[0]->id}">
                <label for="exampleInputCategory">Category</label>
                <input type="text" name="category" class="form-control" id="exampleInputCategory" value="{$resultset[0]->category}" placeholder="Category">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
EOD;
        return $html;
    }

    /**
    * View to see inventory
    * @param $resultset Object. The result object from the database
    * @return $html
    */
    public function inventoryList($resultset)
    {
        $html = <<<EOD
        <div class="table-responsive">
        <table class="table">
        <tr >
        <th>ID</th>
        <th>CATEGORY</th>
        <th>TITLE</th>
        <th>PRICE</th>
        <th>AMOUNT</th>
        <th>ACTION</th>
        </tr >
EOD;

        foreach ($resultset as $result) {
            $html .= <<<EOD
            <tr >
            <td>{$result->id}</td>
            <td>{$result->category}</td>
            <td>{$result->title}</td>
            <td>{$result->price} Kr</td>
            <td>{$result->amount}</td>
            <td><a href="?edit={$result->id}">Edit</a></td>
            </tr >
EOD;
        }

        $html .= <<<EOD
        </table>
        </div>
EOD;
        return $html;
    }

    /**
    * View to edit inventory
    * @param $editInventory String. Processing route to edit inventory
    * @param $resultset Object. The result object from the database
    * @return $html
    */
    public function editInventory($editInventory, $resultset)
    {
        $html = <<<EOD
        <form action="{$editInventory}" method="POST">
            <div class="form-group">
                <input type="hidden" name="id" class="form-control" id="exampleInputId" value="{$resultset[0]->product_id}">
                <label for="exampleInputAmount">Amount</label>
                <input type="text" name="amount" class="form-control" id="exampleInputAmount" value="{$resultset[0]->amount}" placeholder="Amount">
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
EOD;
        return $html;
    }

    /**
    * View to see inventory log
    * @param $resultset Object. The result object from the database
    * @return $html
    */
    public function inventoryLog($resultset)
    {
        $html = <<<EOD
        <div class="table-responsive">
        <table class="table">
        <tr >
        <th>ID</th>
        <th>WHEN</th>
        <th>WHAT</th>
        <th>PRODUCT ID</th>
        <th>AMOUNT</th>
        </tr >
EOD;

        foreach ($resultset as $result) {
            $html .= <<<EOD
            <tr >
            <td>{$result->id}</td>
            <td>{$result->when}</td>
            <td>{$result->what}</td>
            <td>{$result->productId}</td>
            <td>{$result->amount}</td>
            </tr >
EOD;
        }

        $html .= <<<EOD
        </table>
        </div>
EOD;
        return $html;
    }

    /**
    * Add checked value to radio button
    * @param $compare Object. Category object from database
    * @param $valueToCompare Object. Category object from database
    * @return "checked or null"
    */
    public function checked($compare, $valueToCompare)
    {
        return $compare == $valueToCompare ? "checked" : null;
    }
}
