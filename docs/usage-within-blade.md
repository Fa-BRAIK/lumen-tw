# Usage within Blade

## Blade Directives

You can use `@twMerge` directive to merge Tailwind CSS classes.

```bladehtml
<div @twMerge("text-lg text-center", "text-xl")>
    ...
</div>
```

> Name of the directive is configurable, you can find it in the config file.

## Blade Component Attribute Bag Macros

### `twMerge`

You can use it in a Blade component to automatically merge classes passed from attributes.

```bladehtml
{{-- circle.blade.php --}}

<div {{ $attributes->twMerge('w-10 h-10 rounded-full bg-red-500') }}></div>
```

### `twMergeFor && withoutTwMergeClasses`

```bladehtml
{{-- button.blade.php --}}

<button type="button" {{ $attributes->withoutTwMergeClasses()->merge(['class' => 'p-4']) }}>
    <svg {{ $attributes->twMergeFor('icon', 'h-5 w-5 text-gray-500') }}></svg>
</button>
```
