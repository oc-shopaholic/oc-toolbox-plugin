# Toolbox

[![Build Status](https://travis-ci.org/lovata/oc-toolbox-plugin.svg?branch=master)](https://travis-ci.org/lovata/oc-toolbox-plugin)
[![Dependency Status](https://www.versioneye.com/user/projects/59d504fc2de28c002c1882b5/badge.svg)](https://www.versioneye.com/user/projects/59d504fc2de28c002c1882b5)
[![Coverage Status](https://coveralls.io/repos/github/lovata/oc-toolbox-plugin/badge.svg?branch=master)](https://coveralls.io/github/lovata/oc-toolbox-plugin?branch=master)
[![Code Climate](https://codeclimate.com/github/lovata/oc-toolbox-plugin/badges/gpa.svg)](https://codeclimate.com/github/lovata/oc-toolbox-plugin)
[![Crowdin](https://d322cqt584bo4o.cloudfront.net/toolbox-plugin-for-october-cms/localized.svg)](https://crowdin.com/project/toolbox-plugin-for-october-cms)
[![SemVer 2.0.0](http://img.shields.io/SemVer/2.0.0.png)](http://semver.org/spec/v2.0.0.html)
[![License: GPL v3](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)

![Toolbox Banner](assets/images/toolbox-banner.png)

Toolbox is a set of helpers for faster development for [October CMS](https://github.com/octobercms/october). It includes a universal component for rendering pagination navigation controls, a set of tools for caching of objects and lists. Also, it includes a list of most common language strings for using in third-party plugins: name, title, description etc.

## Features

* Components
    * Pagination is used to get a list of pagination buttons and provides flexible settings.
* Classes
    * `ElementCollection` provides a flexible tool for working with cached lists of model elements.
    * `ElementData` is an abstract class for components that returns object of `ElementItem` class for model.
    * `ElementItem` provides a flexible tool for single element data caching.
    * etc.

[Read more…](https://github.com/lovata/oc-toolbox-plugin/wiki)

## Image sets for different screen widths

A model can store extra image sets for different screen widths, for example a close-up photo for phones.

1. Enable image sets in Settings → "Application settings" → "Images" and choose the approach: `desktop_first` (default) or `mobile_first`.
2. Add screen types in Settings → "Image profiles": name and screen width in px. A width of 768 gives `(width < 768px)` for desktop-first and `(width >= 768px)` for mobile-first.

Connect image sets to a model:

```php
public $morphMany = [
    'image_sets' => [\Lovata\Toolbox\Models\ImageSet::class, 'name' => 'imageable', 'delete' => true],
];

public $cached = [
    // ...
    'image_sets',
];
```

Add `image_sets.images` and `image_sets.profile` to `$arQueryWith` of the model's Item class. The Item cache is cleared automatically for models that have a `ModelHandler` with an Item class and the `id` identifier.

Add the field to the backend form with a subclass of `AbstractImageSetsFieldHandler`:

```php
class ExtendProductImageSetsHandler extends \Lovata\Toolbox\Classes\Event\AbstractImageSetsFieldHandler
{
    protected function getTabName() : string
    {
        return 'lovata.toolbox::lang.tab.images';
    }

    protected function getModelClass() : string
    {
        return Product::class;
    }

    protected function getControllerClass() : string
    {
        return Products::class;
    }
}
```

The field is shown only when image sets are enabled and at least one active profile exists.

`image_sets` of the Item returns sets of active profiles, already ordered for `<picture>`: each set has `profile` (`id`, `name`, `width`, `media`) and `images` (array of `System\Models\File` objects).

```twig
<picture>
    {% for obSet in obElement.image_sets %}
        <source media="{{ obSet.profile.media }}" srcset="{{ (obSet.images|first).path }}">
    {% endfor %}
    <img src="{{ obElement.preview_image.path }}" alt="{{ obElement.preview_image.description }}">
</picture>
```

## Get involved

If you're interested in the improvement of this project you can help in the following ways:
* bug reporting and new feature requesting by creating issues on plugin [GitHub page](https://github.com/lovata/oc-toolbox-plugin/issues);
* contribution to a project following these [instructions](https://github.com/lovata/oc-toolbox-plugin/blob/master/CONTRIBUTING.md);
* localization to your language using [Crowdin](https://crowdin.com/project/toolbox-plugin-for-october-cms) service.

## License

© 2017, [LOVATA Group, LLC](https://github.com/lovata) under [GNU GPL v3](https://opensource.org/licenses/GPL-3.0).

Developed by [Andrey Kharanenka](https://github.com/kharanenka).
