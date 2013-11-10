# Themes

This is a list of themes available with HeyDoc.



## How choose

The default theme is `default` but you can choose an other theme. Themes into this folder are the only available.

You can create our custom theme and add it with conf `theme_dirs`

You can PR to propose new theme.



## For developpers


### Accessible vars

* `app`
  * `app.config`
  * `app.request`
* `page`
* `content`



### Twig helpers

#### Methods

* `path(url)`
* `heydoc_homepage()`
* `heydoc_version()`

#### Filters

* `|markdown_transform`
* `|highligth(language)`
