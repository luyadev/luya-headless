# Render a CMS Page with blocks

The [previous section about CMS apis](cms.md) shows how architecture of headless cms items is done, in order to get a faster resulate you can use the PageRenderer class with blocks. Blocks are a visual representation of the data you get from the api.  

## Page Object

In order to get the CMS content retrieve the page content for a given nav id and language:

```php
$client = new Client($token, $url);
$page = Page::find($langId, $navId)->response($client);

if ($page->isPage()) {
  // render the a page in your application an assign the $page response object, we will use this later.
}

if ($page->isRedirect()) {
   // redirect to the given location based on your application logic
}
```

If the page type is a redirect maybe you want to redirect the page inside your controller logic, we will skip this now an proceed to block rendering.

## Render the Page

The below example shows you how you can iterate trough the rows and cols and access the block object, this is of course just a demonstration of how its done a not very usefull for a full and complex rendering of a page, there fore take a look at the next section instead.

An example of how a nested view could look like with bootstrap grid system (as its the same for the admin). Keep in mind we have a max amount of 12 cols per row.

```php
<?php foreach ($page->getCurrentPageVersion()->getRows() as $row): ?>
    <div class="row">
    <?php foreach ($row->getCols() as $col): ?>
        <div class="col-md-<?= $col->getSize(); ?>">
        <?php foreach ($col->getItems() as $block): ?>
            <p>Block: <?= $block->name; ?>
        <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
    </div>
<?php endforeach; ?>
```

> We recommend to use PageRenderer class instead!

## Create Blocks

Create a block class and render the view for this block including the variables from the API accessed trough `varValue()`, `cfgValue()` and `extraValue()`:

```php
class TextBlock extends AbstractBlockView
{
    public function render()
    {
        return '<p>'.$this->varValue('text').'</p>';
    }
}
```

> Every block needs at least a render method in order to work. Take a look at the 

## Connect Block with Page

Now as you have created all your blocks for the frontend, used in the administration you have to connect those blocks into the page rendere object.

You need a Page object

```php
$page = Page::find(1, 1)->response($client);
```

Create a new renderer

```php
$renderer = new PageRenderer($page->getCurrentPageVersion());
```

Register the blocks for the page renderer, using the id, class or full qualified class name:

```php
TextBlock::register(1, $renderer); // block id 1 on admin side
HeadingBlock::register('HeadingBlock', $renderer); // assuming the block class name is HeadingBlock on admin side
TableBlock::register('app\blocks\TableBlock', $renderer); // full qualified class name on admin side.
```

Render the page

```php
echo $renderer->render();
```

#### Further reading

+ [How can create a menu from the CMS?](cms-menu.md)
