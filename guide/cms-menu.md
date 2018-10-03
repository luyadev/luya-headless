# LUYA CMS MODULE

When you have installed the [luya-module-cms] on your API you can also retrieve menu data and render content of a given CMS Page. This guide sections explains how LUYA retrieves the data and how to iterate trough menus.

## Retrieve Menu

A very basic example of how to iterate through the menu for a given container and language id returning the root level of the menu (first level):

```php
$menu = Menu::find()->container(2)->language(1)->root()->response($client);

foreach ($menu as $nav) {
    echo "<a href=\"?navId=".$nav->id.\">".$nav->item->title."</a>";
}
```

Now you can pass the navigation id with the language id to the application logic where you want to render the page.

## Structure of Nav

Every nav (navigation) is only holding the meta informations about this page, its offline, its visible, etc, the information about the page name itself or the slug is stored in the item of the nav. This depending on the input language. As the navigation entry can have different titles for every language.

#### Further reading

+ [How can i render the page itself with blocks?](cms-page.md)