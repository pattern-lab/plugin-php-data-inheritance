![license](https://img.shields.io/github/license/pattern-lab/plugin-php-data-inheritance.svg)
[![Packagist](https://img.shields.io/packagist/v/pattern-lab/plugin-data-inheritance.svg)](https://packagist.org/packages/pattern-lab/plugin-data-inheritance) [![Gitter](https://img.shields.io/gitter/room/pattern-lab/php.svg)](https://gitter.im/pattern-lab/php)

# Data Inheritance Plugin for Pattern Lab

The Data Inheritance Plugin forces patterns to inherit data from their lineage. This allows data in included patterns to bubble
to the top of the pattern stack. With this plugin pseudo-patterns become first-class citizens.

This is a crap intro. Rewrite it.

## Installation

To add the Data Inheritance Plugin to your project using [Composer](https://getcomposer.org/) type:

    composer require pattern-lab/plugin-data-inheritance

See Packagist for [information on the latest release](https://packagist.org/packages/pattern-lab/plugin-data-inheritance).

## Usage

After install make sure to generate your site again with:

    php core/console --generate


## Disabling the Plugin

To disable the Faker plugin you can either directly edit `./config/config.yml` or use the command line option:

    php core/console --config --set plugins.dataInheritance.enabled=false
