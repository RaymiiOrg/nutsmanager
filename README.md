# NutsManager

![Screenshot with webpagescreenshot.info](http://www.webpagescreenshot.info/i/15431-102201293433pm.png)

NutsManager is a power/gas/water usage tracker written in PHP. It uses a JSON text file for the values and the visual side is created with Bootstrap. The graphs are made with the flot framework.  

It helps me to keep track of my power usage, and shows me on which days I use a lot, and on which days I do not. That way, I know that a LAN party costs me a lot.

## Changelog

### v0.0.4
- Program is now i18n compatible and has a Dutch and English translation.

### v0.0.3 
- Full overview is now sorted by month.

### v0.0.2
- Fixed a bug where PHP takes variables as int. and not as float.

### v0.0.1
- Initial release

## Features

- 4 different measurement units:
  - Power
  - Discount priced power
  - Gas
  - Water
- Graph which shows the difference with the day before
- Overview of all values, sorted per month
- Average difference
- Average price
- Pretty colours

## Install

- Download zip file: https://github.com/RaymiiOrg/nutsmanager/zipball/master and unzip to webroot (/var/www).  
- Make sure json file is writable by webserver: *chown www-data:www-date power.json*
  - (Or, *chmod 777 power.json* if you are lazy)
- Change the price values for the power, gas and water in the *config.php* file.
  - Make sure you use a dot (.) and not a comma (,).
- Set the language in the *config.php* file.
  - For english: language.en.php.
  - For dutch: language.nl.php.
- Start adding the values every day.


## Links

Raymii.org: https://raymii.org/cms/p_NutsManager  
Flot: https://github.com/flot/flot  
