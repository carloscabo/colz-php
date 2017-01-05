## Usage

Create new color object.

````php
use Colz;

# 'hex' is default color mode
$color = new Colz\Colz( '#fabada', 'hex');

# Other Available color modes
$rgb   = new Colz\Colz( [ 9, 155, 213 ],   'rgb');
$rgbF  = new Colz\Colz( [ 0.035294117647059, 0.6078431372549, 0.83529411764706 ],  'rgbF');
$rgbLI = new Colz\Colz( 629717, 'rgbLI');
$bgrLI = new Colz\Colz( 13998857, 'bgrLI');
$hsl   = new Colz\Colz( [ 197, 92, 44 ],   'hsl');
$hslF  = new Colz\Colz( [ 0.54722222222222, 0.92, 0.44 ],  'hslF');
````

## Executing the tests

MacOS / Linux

````
composer install
./vendor/bin/phpunit
````

In Windows

````
composer install
vendor\bin\phpunit.bat
````