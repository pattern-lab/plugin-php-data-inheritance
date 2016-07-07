![license](https://img.shields.io/github/license/pattern-lab/plugin-php-data-inheritance.svg)
[![Packagist](https://img.shields.io/packagist/v/pattern-lab/plugin-data-inheritance.svg)](https://packagist.org/packages/pattern-lab/plugin-data-inheritance) [![Gitter](https://img.shields.io/gitter/room/pattern-lab/php.svg)](https://gitter.im/pattern-lab/php)

# Data Inheritance Plugin for Pattern Lab

The Data Inheritance Plugin allows patterns to inherit data from patterns within its lineage. This means that data from included patterns is merged with the current pattern. The current pattern's data takes precedence.

## Installation

To add the Data Inheritance Plugin to your project using [Composer](https://getcomposer.org/) type:

    composer require pattern-lab/plugin-data-inheritance

See Packagist for [information on the latest release](https://packagist.org/packages/pattern-lab/plugin-data-inheritance).

## Usage

This plugin is automatically enabled when it is installed. To use this plugin use the appropriate command to rebuild your patterns:

    php core/console --generate

## Example

This plugin allows for a pattern to inherit data from included patterns. For example, if we have the following pattern structure:

    organisms/
      - global/
        - header.mustache
        - header.yml
    template/
      - homepage.mustache
      - homepage.yml

And the template `homepage.mustache` includes `header.mustache` using `{{> organisms-header }}` then `homepage.mustache` will be rendered with data from `./data/*`, `homepage.yml` _and_ `header.yml`. Without this plugin Pattern Lab will only use the first two data sources for rendering content.

### Special Note About Pseudo-Patterns

[Pseudo-patterns](http://patternlab.io/docs/pattern-pseudo-patterns.html) give developers a way to view different states of a pattern. They're especially useful at the template- and page-level of Pattern Lab. By using the Data Inheritance Plugin pseudo-patterns become a first-class pattern just like their Mustache counterparts and can be included in parent patterns. For example, if you have the pseudo-pattern `header~company-a.yml` that shows a variation of the header for Company A then you can include that in a template using `{{> header-company-a }}`. The template will use the Company A data when it is rendered.

### Order of Inheritance

The order of inheritance is:

    ./data/* < included patterns < pattern-specific content

## Disabling the Plugin

To disable the Data Inheritance plugin you can either directly edit `./config/config.yml` or use the command line option:

    php core/console --config --set plugins.dataInheritance.enabled=false
