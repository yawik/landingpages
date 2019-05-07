# YAWIK Landingpages

Create landingpages for job searches

- [Overview](#overview)
- [Installation](#installation)
- [Usage](#usage)
  - [Configuration](#configuration)
    - [Example configuration](#example-configuration)
  - [Rendering](#rendering)
    - [Landingpages helper](#landingpages-helper)
    - [Landingpage helper](#landingpage-helper)
- [License](#license)

## Overview

- Configurable landing page url
- Group landing pages in categories at any depth
- Combine categories or landing pages dynamically; Either via configuration or
  the view helpers
- Convenient view helper to display links to landing pages

## Installation

To use as yawik module, you need to require it in your YAWIK project:
``` sh
composer require yawik/landingpages
```

To contribute, you need to clone the repository:

```sh
git clone git@github.com:yawik/landingpages.git
cd landingpages
composer install
```

Start a local webserver with:
``` sh
php -S localhost:8000 -t test/sandbox/public test/sandbox/public/router.php
```
or
``` sh
composer serve
```

you should be able to access your yawik at http://localhost:8000

## Usage

### Configuration

Copy `config/landingpages.config.local.php.dist` to your autoload directory and
rename to `landingpages.config.local.php`.

In this file you can configure the route and the landing pages and categories
``` php
$route = '/landingpage/%slug%';
```
- This route is a child route of the "lang" route
  (prefixed always with "<basePath>/<lang>", e.g.: "/en/")
- The string "%slug%" must be in the definition - this will be replaced by the
  landing page slug (see below)
- The route must not contain "--" (two dashes) - this is used to indicate
  combined categories

``` php
$landingpages = [];
```

Here you configure the categories, landing pages and combined items as nested 
arrays. The array keys are the slugs of the items - they must be unique, even in
nested arrays.

There are three "types" of items

- categories:
  - have the array key 'items', which value is an array of slug => item
  - may have the array key 'text', which value is used as the display name
- landing pages:
  - have the array key 'query', which is an array where you specify the values
    of the search form fields:
    - 'q' : Search text
    - 'l' : Location
    - 'd' : Distance
  - may have the array key 'params', which is an array of additional route
    parameters injected to the RouteMatch object.
  - may have the array key 'text', which value is used as the display name
- combined items:
  - have the array key 'combine', which value is an array of slugs.
    These slugs need to be defined in the configuration array.
  - may have the array key 'glue', which value is used as concatenation string
    between the display names of the combined items.
  - may have the array key 'text', which value us used as the display name.

#### Example configuration

``` php
<?php
$landingpages = [
  'top' => [
    'text' => 'Top is a category'
    'items' => [
      'topPage' => [
        'text' => 'This is a landing page',
        'query' => [
          'q' => 'manager',
          'l' => 'Frankfurt am Main, Germany',
          'd' => 50
        ],
        'params' => [
          'param1' => 'Additional route parameter',
        ],
      ],
    ],
  ],
  'middle' => [
    'text' => 'This combines top and bottom',
    'combine' => ['top', 'bottom'],
    'glue' => 'and',
  ],
  'bottom' => [
    'text' => 'Bottom category',
    'items' => [
      'bottomPage' => [
        'text' => 'Also a landing page',
        'query' => [
          'q' => 'executive',
        ],
      ],
      'executive-manager' => [
        'text' => 'you can also combine landing pages',
        'combine' => ['topPage', 'bottomPage'],
        'glue' => 'combined with',
      ],
    ],
  ],
];
```

### Rendering

This module provides two view helpers, one to easily render links to landingpages
or categories and the second one for access to the landing page entity when on a 
landing page.

#### Landingpages helper

``` php
<?=$this->landingpages(string|array $slug, string $partial, array $values)?>
```

* `$slug` is either a string corresponding to a slug defined in the configuration
  or an array of slugs. In the latter case the resulting category entity which is
  passed to the `$partial` is the combination of all slugs. You may specify the
  glue to use with an array entry with the key 'glue'.

* `$partial` is the name of the view partial used to render the links.
  At the moment, only one partial is provided by this module, which is
  used as the default:   
  [landingpages/button](./view/partial/buttons.phtml)

* `$values` is an array of variable name => value pairs to be injected in the
   view partial.  
   The category entity is passed as the key 'category'.

#### Landingpage helper

```php
<?=$this->landingpage()?>
```
* returns `null` if currently no landingpage route is invoked.
* Acts as proxy to the [landing page entity](./src/Entity/Landingpage.php)
* Provides the method `isCombined()` to quickly check, if the current 
  landingpage is a combination of multiple slugs.

__Examples__
```php
<?php
// get the landingpage params
$params = $this->landingpage()->getParams();

// render a combination of this landingpage with a category
if ($this->landingpage()):
    if ($this->landingpage()->getCategory()->getName() == 'top'):
        echo $this->landingpages(
            [
                $this->landingpage()->getSlug(),
                'bottom',
                'glue' => 'and'
            ]
        );
    elseif ($this->landingpage()->getCategory()->getName() == 'bottom'):
        // ...
    endif;
endif;  
```

## License

[MIT](./LICENSE)
