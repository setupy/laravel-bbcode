# Laravel BBCode Parser

## Description

Parse your BBCode easy with bbcode-parser

## Install

Via Composer

``` bash
composer require dot-im/bbcode-parser
```

## Usage
To parse some text it's as easy as this!

```php
echo app('bbcode-parser')->parse('[b]Bold Text![/b]');

// <strong>Bold Text!</strong>
```

Would like the parser to not use all bbcodes? Just do like this.
```php

echo app('bbcode-parser')
        ->only('bold', 'italic')
        ->parse('[b][u]Bold[/u] [i]Italic[/i]![/b]');

echo app('bbcode-parser')
        ->except('bold')
        ->parse('[b]Bold[/b] [i]Italic[/i]');
```

By default, the parser is case sensitive. But if you would like the parser to accept tags like `` [B]Bold Text[/B] `` it's really easy.
```php
#Case insensitive
echo app('bbcode-parser')->parse('[b]Bold[/b] [I]Italic![/I]', true); 

#Or like this
echo app('bbcode-parser')->parseCaseInsensitive('[b]Bold[/b] [i]Italic[/i]');
```

You could also make it more explicit that the parser is case sensitive by using another helper function.
```php
app('bbcode-parser')->parseCaseSensitive('[b]Bold[/b] [I]Italic![/I]');
```

If you would like to completely remove all BBCode it's just one function call away.
```php
app('bbcode-parser')->stripBBCodeTags('[b]Bold[/b] [i]Italic![/i]');
```

The syntax is the same as if you would use it in vanilla PHP but with the ``BBCode::`` before the methods.
Here are some examples.
```php
#Simple parsing
BBCode::parse('[b]Bold Text![/b]');

#Limiting the parsers with the only method
BBCode::only('bold', 'italic')->parse('[b][u]Bold[/u] [i]Italic[/i]![/b]'); 

#Or the except method
BBCode::except('bold')->parse('[b]Bold[/b] [i]Italic[/i]'); 
```

## Blade

```blade
@bb('[b]Bold[/b] [i]Italic[/i]') 
{{-- <strong>Bold</strong> <em>Italic</em> --}}

@bbexcept('bold', '[b]Bold[/b] [i]Italic[/i]') 
{{-- [b]Bold[/b] <em>Italic</em> --}}

@bbonly('bold', '[b]Bold[/b] [i]Italic[/i]')
{{-- <strong>Bold</strong> [i]Italic[/i] --}}
```

## Extending BBCodes
You can add custom bbcode in config file
```bash
php artisan vendor:publish --provider="Dotim\BBCode\BBCodeServiceProvider" --tag="bbcodes-config"
```

Or you can add using method
```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        app('bbcode-parser')->setBBCode(
            'size', // bbcode name
            '/\[size\=([1-7])\](.*?)\[\/size\]/s', // pattern
            '<span style="font-size: $1px;">$2</span>', // replace
            '$2' // content param
        );
    }
}

```

Using
```php
app('bbcode-parser')->parse('[size=2]text[/size] [b]Example[/b]');
app('bbcode-parser')->except('size')->parse('[size=2]text[/size] [b]Example[/b]');
app('bbcode-parser')->only('size')->parse('[size=2]text[/size] [b]Example[/b]');
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
