# Configuration

## Installation

```bash
composer require lumen-ui/lumen-tw
```

## Basic Usage

If you're using Tailwind CSS without any extra config, you can use `twMerge` right away. You can safely stop reading the documentation here.

## Usage with custom Tailwind config

If you're using a custom Tailwind config, you may need to configure tailwind-merge as well to merge classes properly.

The default twMerge function is configured in a way that you can still use it if all the following points apply to your Tailwind config:

- Only using color names which don't clash with other Tailwind class names
- Only deviating by number values from number-based Tailwind classes
- Only using font-family classes which don't clash with default font-weight classes
- Sticking to default Tailwind config for everything else
- If some of these points don't apply to you, you can test whether twMerge still works as intended with your custom classes. Otherwise, you need create your own custom merge function by either extending the default tailwind-merge config or using a completely custom one.

The tailwind-merge config is different from the Tailwind config because it's expected to be shipped and run in the browser as opposed to the Tailwind config which is meant to run at build-time. Be careful in case you're using your Tailwind config directly to configure tailwind-merge in your client-side code because that could result in an unnecessarily large bundle size.

## Shape of tailwind-merge config

The tailwind-merge config is an object with a few keys.

```php
use Lumen\TwMerge\Support\Contracts\Config as ConfigContract;
use Lumen\TwMerge\Support\Config;

/** 
 * @var ConfigContract<string, string> $config 
 */
$config = new Config(
    // ↓ Set how many values should be stored in cache.
    cacheSize: 500,
    // ↓ Optional prefix from Tailwind config
    prefix: 'tw',
    theme: [
        // Theme scales are defined here
    ],
    classGroups: [
        // Class groups are defined here
    ],
    conflictingClassGroups: [
        // Conflicts between class groups are defined here
    ],
    conflictingClassGroupModifiers: [
        // Conflicts between postfix modifier of a class group and another class group are defined here
    ],
    orderSensitiveModifiers: [
        // Modifiers whose order among multiple modifiers should be preserved because their order
        // changes which element gets targeted.
    ],
)
```
