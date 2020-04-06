**ObfuscateIds** is a small php library to generate the short,non-repeat,non-sequential string from number.
Use it when you don't want to expose your database numeric ids to users, or use it to generate the 
invitation code according to the users database numeric ids.

## Getting Started
Require the package with [Composer](https://getcomposer.org) in your project root directory.
```bash
$ composer require obfuscateids/obfuscateids
```
And then you can import the class into your application:
```php
use ObfuscateIds\ObfuscateIds;

$obfuscateids = new ObfuscateIds();

$code = $obfuscateids -> encode(1);
echo $code;
```
## Quick Example
```php
use ObfuscateIds\ObfuscateIds;

$obfuscateids = new ObfuscateIds();

$code = $obfuscateids -> encode(1); // 7D53D5

$nember = $obfuscateids -> decode($code); // 1
```

## More Options
**Using a custom alphabet and custom random string:**
```php
use ObfuscateIds\ObfuscateIds;

$obfuscateids = new ObfuscateIds("C7YQTHLKRGW6F8AMNB42EVX", "5DPUJ3", 8);

$code = $obfuscateids -> encode(1); // 7JJ5PPDU

$nember = $obfuscateids -> decode($code); // 1
```
> **Note:** when the <code>$number</code> is less than custom alphabet length,the code non-unique and non-repeat;
The custom alphabet and the custom random can't have the same characters and numbers.

## Randomness
The primary purpose of ObfuscateIds is to obfuscate numeric ids. It's **not** meant or tested to be used as a security or compression tool. Having said that, this algorithm does try to make these ids random and unpredictable.

## License
MIT License. See the [LICENSE](LICENSE) file. You can use ObfuscateIds in open source projects and commercial products.