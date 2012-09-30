# NutsManager

![Screenshot with webpagescreenshot.info](http://www.webpagescreenshot.info/i/265618-9302012113752am.png)

NutsManager is a power/gas/water usage tracker written in PHP. It uses a JSON text file for the values and the visual side is created with the excellent HTML5 Kickstart framework by Joshua Gatcke. The graphs are made with the flot framework.  

It helps me to keep track of my power usage, and shows me on which days I use a lot, and on which days I do not. That way, I know that a LAN party costs me a lot.

## Features

 - 4 different measurement units:
   - Power
   - Discount priced power
   - Gas
   - Water


- Graph which shows the difference with the day before
- Overview of all values
- Average difference
- Pretty colours

## Install

- Download zip file: https://github.com/RaymiiOrg/nutsmanager/zipball/master and unzip to webroot (/var/www).  
- Make sure json file is writable by webserver: *chown www-data:www-date power.json*
  - (Or, *chmod 777 power.json* if you are lazy)
- Change the price values for the power, gas and water in the *functions.php* file.
  - Make sure you use a dot (.) and not a comma (,), php doesn't like comma's.
- Start adding the values every day.
- ???
- PROFIT!!!


## Links

Raymii.org: https://raymii.org/cms/p_NutsManager  
HTML5 Kickstart: https://github.com/joshuagatcke/HTML-KickStart  
Flot: https://github.com/flot/flot  
