# Laravel Pagination Buttons

## Usage

Get pagination buttons. 

```php
$buttons = \Artes\Pagination\Pagination::execute($pages, $currentPage);
```

`currentPage` is not required argument, default getting param `page` from Request.

Callback is `\Illuminate\Support\Collection` object.  
It is foreachable object.

You can use

```blade
@foreach ($buttons as $button)
    <a href="{{ route('some.route', ['page' => $button->page]) }}"
        class="{{ $button->active ? 'active' : '' }}">
        {{ $button->label }}
    </a>
@endforeach
```
