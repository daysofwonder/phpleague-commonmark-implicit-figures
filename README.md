# CommonMark task Lists

[![License](https://github.com/daysofwonder/phpleague-commonmark-implicit-figures/blob/master/LICENSE)](https://github.com/daysofwonder/phpleague-commonmark-implicit-figures/blob/master/LICENSE)

Render images occurring by itself in a paragraph as `<figure><img ...></figure>`, clearly inspired from markdown-it-implicit-figures and similar to [pandoc's implicit figures](http://pandoc.org/README.html#images).

## Installation

This project can be installed via Composer:

```
composer require daysofwonder/phpleague-commonmark-implicit-figures
```

## Usage

```php
use League\CommonMark\Converter;
use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use League\CommonMark\HtmlRenderer;
use DoW\CommonMark\ImplicitFigures\Extension;

$environment = Environment::createCommonMarkEnvironment();
$environment->addExtension(new \DoW\CommonMark\ImplicitFigures\Extension());

$converter = new Converter(new DocParser($environment), new HtmlRenderer($environment));

echo $converter->convertToHtml('![title](fig.png)');
```

## Syntax

Example input:
```md
text with ![](img.png)

![title](fig.png)

works with links too:

[![](fig.png)](page.html)
```

Output:
```html
<p>text with <img src="img.png" alt=""></p>
<figure><img src="fig.png" alt=""></figure>
<p>works with links too:</p>
<figure><a href="page.html"><img src="fig.png" alt=""></a></figure>
```
