# Jefferson Lab Electronic Logbook Bootstrap Subtheme

## Prerequisites
  *  The Drupal bootstrap theme (https://www.drupal.org/project/bootstrap)
  *  The Drupal bootstrap library module (https://www.drupal.org/project/bootstrap_library)

## Installation

Install and enable this theme per standard Drupal 7 procedure. 
````bash
cd sites/all/themes
git clone https://github.com/JeffersonLab/bootstrap_logbooks.git
```` 
Navigate with your browser to admin/appearance to enable the theme and modify its configuration settings.

## Glyphicons

*Note*: In order for the bootstrap module to find its glyphicons fonts, I had to make a symbolic link so that the
bootstrap library fonts subdirectory appeared to be a subdirectory of bootstrap_logbooks.

```
cd sites/all/themes/bootstrap_logbooks
ln -s ../../libraries/bootstrap/fonts .
```

## Configuration

For more detailed information on configuring this theme and the Jefferson Lab elog module, plese see the [wiki](https://github.com/JeffersonLab/elog/wiki)
