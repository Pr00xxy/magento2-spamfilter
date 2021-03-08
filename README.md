# SpamFilter

A plug and play ready module for Magento 2 that helps store owners tackle the frustration 
of dealing spam users created by bots and/or foreign spam factories.

## Installation

Use the [composer](https://getcomposer.org/) to install.

```bash
composer install prooxxy/magento2-spamfilter
```

Make sure the module is enable before start using

```bash
php bin/magento module:enable PrOOxxy_SpamFilter
```

### Alternative installation

Download the [Latest release](https://github.com/Pr00xxy/magento2-spamfilter/releases)

## Features

This module provides features to restrict the following areas
1. Customer account creation
2. Newsletter sign up
3. Contact Form

All three areas can be configured independently with different levels of restrictions

Go to Stores -> configuration -> advanced -> spamfilter

![scoped config](docs/scoped_config.png)

The following types of blocking can be enabled on all areas listed above

### Alphabet blocking

Block entries based on a list of languages.
e.g you may block Cyrillic or Hanzi characters

### Link blocking

Prevents malicious actors to send web links in the customer information or contact forms

This feature allows the store owner to:
* block contact form entries from containing web links
* block customers from putting links in their firstname or lastname on customer registration

### Email Blocking

Block newsletter signup, customer registration or contact form entries from specific domains or matching patterns.

For example a store owner may configure wildcard patterns like:

`*@yandex.ru, *@*.fi`

![block known domains](docs/spam_reg_domain.png)

## Compability Matrix

|       | Magento 2.2 | Magento 2.3 | Magento 2.4 |
|-------|-------------|-------------|-------------|
| `1.x` |      ×      |      ✓      |      ×      |
| `2.x` |      ×      |      ×      |      ✓      |

## License

[MIT](https://choosealicense.com/licenses/mit/)
