# Render a CMS Page with blocks

The [previous section about CMS apis](cms.md) shows how architecture of headless cms items is done, in order to get a faster resulate you can use the PageRenderer class with blocks. Blocks are a visual representation of the data you get from the api.  

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

Register the blocks for the page renderer, where the id is the id of the block from the cms:

```php
TextBlock::register(1, $renderer);
HeadingBlock::register(2, $renderer);
```

Render the page

```php
echo $renderer->render();
```