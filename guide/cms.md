## Get CMS Content

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