**/!\ THIS PROJECT NO LONGER MAINTAINED /!\**

# HeyDoc

[![Total Downloads](https://poser.pugx.org/heydoc/heydoc/downloads.png)](https://packagist.org/packages/heydoc/heydoc)
[![Latest Stable Version](https://poser.pugx.org/heydoc/heydoc/v/stable.png)](https://packagist.org/packages/heydoc/heydoc)
[![Latest Unstable Version](https://poser.pugx.org/heydoc/heydoc/v/unstable.png)](https://packagist.org/packages/heydoc/heydoc)
[![Build Status](https://travis-ci.org/nicolas-brousse/HeyDoc.png?branch=master)](https://travis-ci.org/nicolas-brousse/HeyDoc)

Documentation website based on markdown and html files.

## Installation

Create `composer.json` file:

```json
{
    "name": "heydoc/heydoc-application",
    "license": "MIT",
    "type": "project",
    "description": "HeyDoc application",
    "require": {
        "php": ">=5.3.3",
        "heydoc/heydoc": "~0.1"
    },
    "config": {
        "bin-dir": "bin"
    }
}
```

And `composer install`.

Then setup HeyDoc with our command `bin/heydoc setup`.


## Themes

List of [themes](https://github.com/nicolas-brousse/HeyDoc/blob/master/HeyDoc/Resources/themes):

* [default](https://github.com/nicolas-brousse/HeyDoc/blob/master/HeyDoc/Resources/default)
