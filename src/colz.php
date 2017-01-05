<?php

namespace Colz;

/**
 * Manejar colores en PHP
 */
class Colz {

  // Variables
  public $col = array(
    'cmyk' => null,
    'hex'  => null,
    'rgb'  => null,
    'rgbF' => null,
    'rgbLI' => null,
    'bgrLI' => null,
    'hsl'  => null,
    'hslF' => null,
    'hsb'  => null,
    'lab'  => null,
    'xyz'  => null,
    'r' => null, // Red component (int)
    'g' => null, // Green component (int)
    'b' => null, // Blue component (int)
    'h' => null,
    's' => null,
    'l' => null,
    'websafe' => null
  );


  /**
   * Constructor por defecto le pasamos un hex
   */
  public function __construct($val, $format = 'hex') {

    switch ( $format ) {
      case 'rgb':
        $this->col['rgb'] = $val;
        $this->col['r'] = $val[0];
        $this->col['g'] = $val[1];
        $this->col['b'] = $val[2];

        $this->col['hex'] = self::rgb_2_hex($val);
      break;

      case 'rgbF': // Rgb float [ 0.12, 0.99, 0.56 ]
        $this->col['rgbF'] = $val;
        $this->col['r'] = floor( $val[0] * 255.0);
        $this->col['g'] = floor( $val[1] * 255.0);
        $this->col['b'] = floor( $val[2] * 255.0);
        $this->col['rgb'] = [ $this->col['r'], $this->col['g'], $this->col['b'] ];
        $this->col['hex'] = self::rgb_2_hex( $this->col['rgb'] );
      break;

      case 'rgbLI': // Rgb Long-int
        $this->col['rgbLI'] = $val;
        $this->col['hex'] = str_pad( dechex( $this->col['rgbLI'] ), 6, '0', STR_PAD_LEFT);
      break;

      case 'bgrLI': // Bgr Long Int
        $this->col['bgrLI'] = $val;
        $temp_hex = str_pad( dechex( $this->col['bgrLI'] ), 6, '0', STR_PAD_LEFT);
        $temp_rgb = self::hex_2_rgb( $temp_hex );
        $this->col['rgb'] = [ $temp_rgb[2], $temp_rgb[1], $temp_rgb[0] ];
        $this->col['r'] = $temp_rgb[2];
        $this->col['g'] = $temp_rgb[1];
        $this->col['b'] = $temp_rgb[0];
        $this->col['hex'] = self::rgb_2_hex( $this->col['rgb'] );
      break;

      case 'hsl': // HSL
        $this->col['hsl'] = $val;
        $this->col['h'] = $this->col['hsl'][0];
        $this->col['s'] = $this->col['hsl'][1];
        $this->col['l'] = $this->col['hsl'][2];
        $this->col['hslF'] = [ $this->col['h'] / 360.0, $this->col['s'] / 100.0, $this->col['l'] / 100.0 ];

        $this->col['hex'] = self::rgb_2_hex(self::hsl_2_rgb($val));
      break;

      case 'hslF': // HSL Float
        $this->col['hslF'] = $val;
        $this->col['h'] = round( $val[0] * 360.0, 2);
        $this->col['s'] = round( $val[1] * 100.0, 2);
        $this->col['l'] = round( $val[2] * 100.0, 2);
        $this->col['hsl'] = [ $this->col['h'], $this->col['s'], $this->col['l'] ];

        $this->col['hex'] = self::rgb_2_hex(self::hsl_2_rgb( $this->col['hsl'] ));
      break;

      case 'hsb': // HSB
        $this->col['hsb'] = $val;
        $this->col['rgb'] = self::hsb_2_rgb( $this->col['hsb'] );
        $this->col['r'] = $this->col['rgb'][0];
        $this->col['g'] = $this->col['rgb'][1];
        $this->col['b'] = $this->col['rgb'][2];
        $this->col['hex'] = self::rgb_2_hex( $this->col['rgb'] );
      break;

      case 'hex':
      default: // HEX
        // Convert 3 hex to 6 hex
        // Lowercase and remove '#'
        $val = str_replace( '#', '', strtolower($val));
        if (strlen($val) == 3) {
          $this->col['hex'] = $val[0] . $val[0] . $val[1] . $val[1] . $val[2] . $val[2];
        } else {
          $this->col['hex'] = $val;
        }
      break;

    }

    $this->create_from_hex();
  }

  private function set_rgb ( $val, $format ) {
    switch ( $format ) {
      case 'rgb':
        $this->col['rgb'] = $val;
        $this->col['r'] = $val[0];
        $this->col['g'] = $val[1];
        $this->col['b'] = $val[2];
        $this->col['rgbF']  = [ $val[0] / 255, $val[1] / 255, $val[2] / 255 ];
        $this->col['hex']   = self::rgb_2_hex( $this->col['rgb'] );
        $this->col['rgbLI'] = hexdec( $this->col['hex']);
        $this->col['bgrLI'] = hexdec( self::rgb_2_hex( [ $this->col['b'], $this->col['g'], $this->col['r'] ] ) );
      break;
      case 'rgbF':
        $this->col['rgbF'] = $val;
        $this->col['r'] = floor( $val[0] == 1.0 ? 255 : $val[0] * 256.0);
        $this->col['g'] = floor( $val[1] == 1.0 ? 255 : $val[1] * 256.0);
        $this->col['b'] = floor( $val[2] == 1.0 ? 255 : $val[2] * 256.0);
        $this->col['rgb']  = [ $this->col['r'], $this->col['g'], $this->col['b'] ];
        $this->col['hex']   = self::rgb_2_hex( $this->col['rgb'] );
        $this->col['rgbLI'] = hexdec( $this->col['hex']);
        $this->col['bgrLI'] = hexdec( self::rgb_2_hex( [ $this->col['b'], $this->col['g'], $this->col['r'] ] ) );
      break;
      case 'rgbLI':
        $this->col['rgbLI'] = $val;
        $this->col['hex'] = str_tolower ( tr_pad( dechex( $this->col['rgbLI'] ), 6, '0', STR_PAD_LEFT) );
      break;
      case 'bgrLI':
        $this->col['bgrLI'] = $val;
        $temp_hex = str_pad( dechex( $this->col['bgrLI'] ), 6, '0', STR_PAD_LEFT);
        $temp_rgb = self::hex_2_rgb( $temp_hex );
        $this->col['rgb'] = [ $temp_rgb[2], $temp_rgb[1], $temp_rgb[0] ];
        $this->col['r'] = $this->col['rgb'][0];
        $this->col['g'] = $this->col['rgb'][1];
        $this->col['b'] = $this->col['rgb'][2];
      break;

    }
    $this->create_from_hex();
  }

  /**
   * Crearlo todo desde el HEX
   */
  private function create_from_hex ( $force = false ) {

    // RGB
    if ( $force || is_null( $this->col['rgb'] ) ) {
      $this->col['rgb'] = self::hex_2_rgb($this->col['hex']);
      $this->col['r'] = $this->col['rgb'][0];
      $this->col['g'] = $this->col['rgb'][1];
      $this->col['b'] = $this->col['rgb'][2];
    }
    // Rgb Float
    if ( $force || is_null( $this->col['rgbF'] ) ) {
      $this->col['rgbF'] = [ $this->col['r'] / 255.0, $this->col['g'] / 255, $this->col['b'] / 255.0 ];
    }
    // Rgb Long-int
    if ( $force || is_null( $this->col['rgbLI'] ) ) {
       $this->col['rgbLI'] = hexdec( $this->col['hex'] );
    }
    // Rgb Long-int
    if ( $force || is_null( $this->col['bgrLI'] ) ) {
       $this->col['bgrLI'] = hexdec( self::rgb_2_hex( [ $this->col['b'], $this->col['g'], $this->col['r'] ] ) );
    }

    // Now get HSL, HSB, XYZ and LAB

    // HSL
    if ( $force || is_null( $this->col['hsl'] ) ) {
      $this->col['hsl'] = self::rgb_2_hsl($this->col['rgb']);
      $this->col['h'] = $this->col['hsl'][0];
      $this->col['s'] = $this->col['hsl'][1];
      $this->col['l'] = $this->col['hsl'][2];
    }

    // Hsl Float
    if ( $force || is_null( $this->col['hslF'] ) ) {
      $this->col['hslF'] = [ $this->col['h'] / 360.0, $this->col['s'] / 100.0, $this->col['l'] / 100.0 ];
    }

    // HSB
    if ( $force || is_null( $this->col['hsb'] ) ) {
      $this->col['hsb'] = self::rgb_2_hsb($this->col['rgb']);
    }

    // XYZ
    if ( $force || is_null( $this->col['xyz'] ) ) {
      $this->col['xyz'] = self::rgb_2_xyz($this->col['rgb']);
    }

    // LAB
    if ( $force || is_null( $this->col['lab'] ) ) {
      $this->col['lab'] = self::xyz_2_lab($this->col['xyz']);
    }

    // CMYK
    if ( $force || is_null( $this->col['cmyk'] ) ) {
      $this->col['cmyk'] = self::rgb_2_cmyk($this->col['rgb']);
    }

    // Websafe
    $this->col['websafe'] = strtolower(self::websafe($this->col['hex']));
  }

  /**
   * Convierte un array de RGB a HEX
   */
  public static function hex_2_rgb($hex) {
    $r = hexdec(substr($hex,0,2));
    $g = hexdec(substr($hex,2,2));
    $b = hexdec(substr($hex,4,2));

    return array($r, $g, $b);
  }

  /**
   * Convierte un array de RGB a HEX
   */
  public static function rgb_2_hex($rgb) {
     $hex = '';
     $hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
     $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
     $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

     return strtolower( $hex );
  }

  /**
   * Convert RGB to HSL
   */
  public static function rgb_2_hsl($rgb) {

    $r = $rgb[0] / 255.0;
    $g = $rgb[1] / 255.0;
    $b = $rgb[2] / 255.0;

    $max = max($r, $g, $b);
    $min = min($r, $g, $b);

    $h;
    $s;
    $l = ($max + $min) / 2;
    $d = $max - $min;

    if( $d == 0 ){
      $h = $s = 0; // achromatic
    } else {
      $s = $d / ( 1 - abs( 2 * $l - 1 ) );

      switch( $max ){
        case $r:
          $h = 60.0 * fmod( ( ( $g - $b ) / $d ), 6 );
          if ($b > $g) {
            $h += 360.0;
          }
        break;

        case $g:
          $h = 60.0 * ( ( $b - $r ) / $d + 2 );
        break;

        case $b:
          $h = 60.0 * ( ( $r - $g ) / $d + 4 );
        break;
      }
    }

    $h = round($h, 2);
    $s = round($s*100, 2);
    $l = round($l*100, 2);

    return array($h, $s, $l);
  }

  /*
    Convert HSL 2 RGB
  */
  public static function hsl_2_rgb($hsl){

    $h = $hsl[0] / 360.0;
    $s = $hsl[1] / 100.0;
    $l = $hsl[2] / 100.0;

    $r = $l;
    $g = $l;
    $b = $l;
    $v = ($l <= 0.5) ? ($l * (1.0 + $s)) : ($l + $s - $l * $s);
    if ($v > 0){
      $m = $l + $l - $v;
      $sv = ($v - $m ) / $v;
      $h *= 6.0;
      $sextant = floor($h);
      $fract = $h - $sextant;
      $vsf = $v * $sv * $fract;
      $mid1 = $m + $vsf;
      $mid2 = $v - $vsf;

      switch ($sextant) {
        case 0:
          $r = $v;
          $g = $mid1;
          $b = $m;
        break;
        case 1:
          $r = $mid2;
          $g = $v;
          $b = $m;
        break;
        case 2:
          $r = $m;
          $g = $v;
          $b = $mid1;
        break;
        case 3:
          $r = $m;
          $g = $mid2;
          $b = $v;
        break;
        case 4:
          $r = $mid1;
          $g = $m;
          $b = $v;
        break;
        case 5:
          $r = $v;
          $g = $m;
          $b = $mid2;
        break;
        }
      }
      return [
        round($r * 255.0),
        round($g * 255.0),
        round($b * 255.0)
      ];
    }

  /**
   * Converts RGB to Photoshop's HSB
   */
  public static function rgb_2_hsb ($rgb) {

      $r = $rgb[0] / 255;
      $g = $rgb[1] / 255;
      $b = $rgb[2] / 255;

      $max = max($r, $g, $b);
      $min = min($r, $g, $b);
      $v = $max;

      $d = $max - $min;
      if ($max == 0) {
        $s = 0;
      } else {
        $s = $d / $max;
      }

      if ($max == $min) {
        $h = 0; // achromatic
      } else {

        switch( $max ){
          case $r:
            $h = 60 * fmod( ( ( $g - $b ) / $d ), 6 );
            if ($b > $g) {
              $h += 360;
            }
          break;

          case $g:
            $h = 60 * ( ( $b - $r ) / $d + 2 );
          break;

          case $b:
            $h = 60 * ( ( $r - $g ) / $d + 4 );
          break;
        }
      }

      // map top 360,100,100
      $h = round($h, 2);
      $s = round($s * 100, 2);
      $v = round($v * 100, 2);

      return array($h, $s, $v);
  }

  /**
   * HSB to RGB
   */
  public static function hsb_2_rgb ($hsv) {

    $h = $hsv[0] / 60;
    $s = $hsv[1] / 100;
    $v = $hsv[2] / 100;

    // $h = $h / 360;
    if ($v == 0) {
      return [ 0, 0, 0 ];
    }

    if ($s == 0) {
      $_v = round($v * 255);
      return [ $_v, $_v, $_v ];
    }

    $i = floor($h);
    $f = $h - $i;
    $p = $v * ( 1 - $s );
    $q = $v * ( 1 - ( $s * $f ) );
    $t = $v * ( 1 - ( $s * ( 1 - $f ) ) );

    // print_a($h); die;

    if ($i == 0) {
      $r = $v;
      $g = $t;
      $b = $p;
    } else if ($i == 1) {
      $r = $q;
      $g = $v;
      $b = $p;
    } else if ($i == 2) {
      $r = $p;
      $g = $v;
      $b = $t;
    } else if ($i == 3) {
      $r = $p;
      $g = $q;
      $b = $v;
    } else if ($i == 4) {
      $r = $t;
      $g = $p;
      $b = $v;
    } else if ($i == 5) {
      $r = $v;
      $g = $p;
      $b = $q;
    }

    $r = round($r * 255);
    $g = round($g * 255);
    $b = round($b * 255);

    return array($r, $g, $b);
  }

  /**
   * Convert RGB to XYZ
   */
  public static function rgb_2_xyz ($rgb) {

    // Normalize RGB values to 1
    $rgb = array_map(function($item){
      return $item / 255;
    }, $rgb);

    $rgb = array_map(function($item){
      if ($item > 0.04045) {
        $item = pow((($item + 0.055) / 1.055), 2.4);
      } else {
        $item = $item / 12.92;
      }
      return ($item * 100);
    }, $rgb);

    //Observer. = 2°, Illuminant = D65
    return array(
      ($rgb[0] * 0.4124) + ($rgb[1] * 0.3576) + ($rgb[2] * 0.1805),
      ($rgb[0] * 0.2126) + ($rgb[1] * 0.7152) + ($rgb[2] * 0.0722),
      ($rgb[0] * 0.0193) + ($rgb[1] * 0.1192) + ($rgb[2] * 0.9505)
    );
  }

  /**
   * Convert RGB to CMYK
   */
  public static function rgb_2_cmyk($rgb) {

    $c = 0;
    $m = 0;
    $y = 0;
    $k = 0;

    $r = $rgb[0];
    $g = $rgb[1];
    $b = $rgb[2];

    // Black
    if ($r == 0 && $g == 0 & $b == 0) {
      return array(0,0,0,0);
    } else {
      $c = 1 - ($r/255);
      $m = 1 - ($g/255);
      $y = 1 - ($b/255);

      $minCMY = min($c, min($m, $y));

      $c = ($c - $minCMY) / (1 - $minCMY) ;
      $m = ($m - $minCMY) / (1 - $minCMY) ;
      $y = ($y - $minCMY) / (1 - $minCMY) ;
      $k = $minCMY;

      $c = round($c*100);
      $m = round($m*100);
      $y = round($y*100);
      $k = round($k*100);

      return array($c,$m,$y,$k);
    }
  }

  /**
   * Converts to LabCIE
   */
  public static function xyz_2_lab($xyz) {

    //Ovserver = 2*, Iluminant=D65
    $xyz[0] /= 95.047;
    $xyz[1] /= 100;
    $xyz[2] /= 108.883;

    $xyz = array_map(function($item){
      if ($item > 0.008856) {
        //return $item ^ (1/3);
        return pow($item, 1/3);
      } else {
        return (7.787 * $item) + (16 / 116);
      }
    }, $xyz);

    return array(
      (116 * $xyz[1]) - 16,
      500 * ($xyz[0] - $xyz[1]),
      200 * ($xyz[1] - $xyz[2])
    );
  }

  /**
   * Return a random HEX number
   */
  public static function get_rand_hex () {
    return substr(md5(rand()), 0, 6);
  }

  /**
   * Get distance between 2 colors
   */
  public static function get_distance_lab ($lab1, $lab2, $method = 'FAST') {
    // More info at:
    // http://www.easyrgb.com/index.php?X=DELT&H=03#text3

    // Methods
    // $method = 'FAST';
    // 'DELTA-E'
    // 'DELTA-E-1994'
    // 'DELTA-CMC'
    // 'DELTA-2000'
    $method = 'DELTA-2000'; // ACCURATE BUT SLOW

    $L1 = $lab1[0];
    $A1 = $lab1[1];
    $B1 = $lab1[2];
    $L2 = $lab2[0];
    $A2 = $lab2[1];
    $B2 = $lab2[2];

    switch ($method) {

      case 'FAST':
        // ORIGINAL -------------------
        $lDiff = abs($L2 - $L1);
        $aDiff = abs($A2 - $A1);
        $bDiff = abs($B2 - $B1);
        $delta = sqrt($lDiff + $aDiff + $bDiff);
      break;

      case 'DELTA-E':
        // DELTA-E --------------------
        $lDiff = pow(($L2 - $L1),2);
        $aDiff = pow(($A2 - $A1),2);
        $bDiff = pow(($B2 - $B1),2);
        $delta = sqrt($lDiff + $aDiff + $bDiff);
      break;

      case 'DELTA-E-1994':
        // DELTA-E 1994 ---------------
        //Weighting factors depending on the application (1 = default)
        $WHT_L = 1;
        $WHT_C = 1;
        $WHT_H = 1;

        $xC1 = sqrt( ( pow($A1, 2) ) + ( pow($B1, 2) ) );
        $xC2 = sqrt( ( pow($A2, 2) ) + ( pow($B2, 2) ) );
        $xDL = $L2 - $L1;
        $xDC = $xC2 - $xC1;
        $xDE = sqrt( ( ( $L1 - $L2 ) * ( $L1 - $L2 ) )
                  + ( ( $A1 - $A2 ) * ( $A1 - $A2 ) )
                  + ( ( $B1 - $B2 ) * ( $B1 - $B2 ) ) );
        if ( sqrt( $xDE ) > ( sqrt( abs( $xDL ) ) + sqrt( abs( $xDC ) ) ) ) {
           $xDH = sqrt( ( $xDE * $xDE ) - ( $xDL * $xDL ) - ( $xDC * $xDC ) );
        }
        else {
           $xDH = 0;
        }
        $xSC = 1 + ( 0.045 * $xC1 );
        $xSH = 1 + ( 0.015 * $xC1 );
        $xDL /= $WHT_L;
        $xDC /= $WHT_C * $xSC;
        $xDH /= $WHT_H * $xSH;
        $delta = sqrt( pow($xDL, 2) + pow($xDC, 2) + pow($xDH, 2) );
      break;

      case 'DELTA-CMC':
        // DELTA CMC l:c --------------
        $WHT_L = 1;
        $WHT_C = 1;

        $xC1 = sqrt( ( pow($A1, 2) ) + ( pow($B1, 2) ) );
        $xC2 = sqrt( ( pow($A2, 2) ) + ( pow($B2, 2) ) );
        $xff = sqrt( ( pow($xC1, 4) ) / ( ( pow($xC1, 4) ) + 1900 ) );
        $xH1 = self::cie_lab_2_hue( $A1, $B1 );

        if ( $xH1 < 164 || $xH1 > 345 ) {
          $xTT = 0.36 + abs( 0.4 * cos( deg2rad(  35 + $xH1 ) ) );
        } else{
          $xTT = 0.56 + abs( 0.2 * cos( deg2rad( 168 + $xH1 ) ) );
        }

        if ( $L1 < 16 ) {
          $xSL = 0.511;
        } else {
          $xSL = ( 0.040975 * $L1 ) / ( 1 + ( 0.01765 * $L1 ) );
        }

        $xSC = ( ( 0.0638 * $xC1 ) / ( 1 + ( 0.0131 * $xC1 ) ) ) + 0.638;
        $xSH = ( ( $xff * $xTT ) + 1 - $xff ) * $xSC;
        $xDH = sqrt( pow( ( $A2 - $A1 ), 2) + pow( ( $B2 - $B1 ), 2) - pow( ( $xC2 - $xC1 ), 2) );
        $xSL = ( $L2 - $L1 ) / $WHT_L * $xSL;
        $xSC = ( $xC2 - $xC1 ) / $WHT_C * $xSC;
        $xSH = $xDH / $xSH;
        $delta = sqrt( pow($xSL, 2) + pow($xSC, 2) + pow($xSH, 2) );
      break;

      case 'DELTA-2000':
        // DELTA E 2000 ---------------
        $WHT_L = 1;
        $WHT_C = 1;
        $WHT_H = 1; //Wheight factors

        $xC1 = sqrt( $A1 * $A1 + $B1 * $B1 );
        $xC2 = sqrt( $A2 * $A2 + $B2 * $B2 );
        $xCX = ( $xC1 + $xC2 ) / 2;
        // ----
        // $xGX = 0.5 * ( 1 - sqrt( ( pow($xCX, 7) ) / ( ( pow($xCX, 7) ) + ( pow(25 , 7) ) ) ) );
        $temp = $xCX*$xCX*$xCX*$xCX*$xCX*$xCX*$xCX;
        $xGX = 0.5 * ( 1 - sqrt( ( $temp ) / ( ( $temp ) + 6103515625 ) ) );
        // ----
        $xNN = ( 1 + $xGX ) * $A1;
        $xC1 = sqrt( $xNN * $xNN + $B1 * $B1 );
        $xH1 = self::cie_lab_2_hue( $xNN, $B1 );
        $xNN = ( 1 + $xGX ) * $A2;
        $xC2 = sqrt( $xNN * $xNN + $B2 * $B2 );
        $xH2 = self::cie_lab_2_hue( $xNN, $B2 );
        $xDL = $L2 - $L1;
        $xDC = $xC2 - $xC1;
        if ( ( $xC1 * $xC2 ) == 0 ) {
          $xDH = 0;
        } else {
          $xNN = round( $xH2 - $xH1, 12 );
          if ( abs( $xNN ) <= 180 ) {
            $xDH = $xH2 - $xH1;
          } else {
            if ( $xNN > 180 ) {
              $xDH = $xH2 - $xH1 - 360;
            } else {
              $xDH = $xH2 - $xH1 + 360;
            }
          }
        }
        $xDH = 2 * sqrt( $xC1 * $xC2 ) * sin( deg2rad( $xDH / 2 ) );
        $xLX = ( $L1 + $L2 ) / 2;
        $xCY = ( $xC1 + $xC2 ) / 2;
        if ( ( $xC1 *  $xC2 ) == 0 ) {
          $xHX = $xH1 + $xH2;
        } else {
          $xNN = abs( round( $xH1 - $xH2, 12 ) );
          if ( $xNN >  180 ) {
            if ( ( $xH2 + $xH1 ) <  360 ) {
              $xHX = $xH1 + $xH2 + 360;
            } else {
              $xHX = $xH1 + $xH2 - 360;
            }
          } else {
            $xHX = $xH1 + $xH2;
          }
          $xHX /= 2;
        }
        $xTX = 1 - 0.17 * cos( deg2rad( $xHX - 30 ) ) + 0.24
                       * cos( deg2rad( 2 * $xHX ) ) + 0.32
                       * cos( deg2rad( 3 * $xHX + 6 ) ) - 0.20
                       * cos( deg2rad( 4 * $xHX - 63 ) );
        $xPH = 30 * exp( - ( ( $xHX  - 275 ) / 25 ) * ( ( $xHX  - 275 ) / 25 ) );
        // ---
        // $xRC = 2 * sqrt( ( pow($xCY, 7) ) / ( ( pow($xCY, 7) ) + ( pow(25, 7) ) ) );
        $temp = $xCY*$xCY*$xCY*$xCY*$xCY*$xCY*$xCY;
        $xRC = 2 * sqrt( ( $temp ) / ( ( $temp ) + 6103515625 ) );
        // ---
        $xSL = 1 + ( ( 0.015 * ( ( $xLX - 50 ) * ( $xLX - 50 ) ) )
                / sqrt( 20 + ( ( $xLX - 50 ) * ( $xLX - 50 ) ) ) );
        $xSC = 1 + 0.045 * $xCY;
        $xSH = 1 + 0.015 * $xCY * $xTX;
        $xRT = - sin( deg2rad( 2 * $xPH ) ) * $xRC;
        $xDL = $xDL / ( $WHT_L * $xSL );
        $xDC = $xDC / ( $WHT_C * $xSC );
        $xDH = $xDH / ( $WHT_H * $xSH );
        // ---
        // $delta = sqrt( pow($xDL, 2) + pow($xDC, 2) + pow($xDH, 2) + $xRT * $xDC * $xDH );
        $delta = sqrt( ($xDL*$xDL) + ($xDC*$xDC) + ($xDH*$xDH) + $xRT * $xDC * $xDH );
        // ---
      break;
    }

    // Return result
    return $delta;
  }

  /**
   * Find nearest color
   */
  public function find_nearest($needle, $haystack) {
    $min_dist = 10000;
    $match = null;

    foreach($haystack as $key => $color) {
      $dist = get_distance_lab($needle, $color);
      if ($dist < $min_dist) {
        $min_dist = $dist;
        $match = $key;
      }
    }
    return $match;
  }

  /**
   * Create websafe palette
   */
  public static function get_websafe_pal() {
    $pal = array();
    $c= array('00','cc','33','66','99','ff');

    for ($i=0; $i<6; $i++){
      for ($j=0; $j<6; $j++){
        for ($k=0; $k<6; $k++){
          $hex = $c[$i].$c[$j].$c[$k];
          $pal[] = new colz($hex);
        }
      }
    }
    return $pal;
  }

  /**
   * CieLab 2 HUE
   */
  public static function cie_lab_2_hue( $var_a, $var_b ) {
    $var_bias = 0;

    if ( $var_a >= 0 && $var_b == 0 ) { return 0; }
    if ( $var_a <  0 && $var_b == 0 ) { return 180; }
    if ( $var_a == 0 && $var_b >  0 ) { return 90; }
    if ( $var_a == 0 && $var_b <  0 ) { return 270; }
    if ( $var_a >  0 && $var_b >  0 ) { $var_bias = 0; }
    if ( $var_a <  0                ) { $var_bias = 180; }
    if ( $var_a >  0 && $var_b <  0 ) { $var_bias = 360; }

    return ( rad2deg( atan( $var_b / $var_a ) ) + $var_bias );
  }

  /**
   * Determina si un color es claro u oscuro
   */
  public static function get_relative_luminance ($rgb) {
    $r = $rgb[0] / 255;
    $g = $rgb[1] / 255;
    $b = $rgb[2] / 255;

    $r = ($r <= 0.03928) ? $r/12.92 : pow(($r+0.055)/1.055, 2.4);
    $g = ($g <= 0.03928) ? $g/12.92 : pow(($g+0.055)/1.055, 2.4);
    $b = ($b <= 0.03928) ? $b/12.92 : pow(($b+0.055)/1.055, 2.4);

    return (0.2126 * $r) + (0.7152 * $g) + (0.0722 *$b);
  }

  /**
   * Returns #FFF or #000 depending on contrast
   */
  public static function grl($col) {
    $return = "fff";
    // Is hex
    if (is_string($col)) {
      $col = self::hex_2_rgb($col);
    }
    $rl = self::get_relative_luminance($col);
    if ($rl > 0.40) {
      $return = "000";
    }
    return $return;
  }
  // Lo mismo con classes
  public static function grl_class($col) {
    $return = "";
    // Is hex
    if (self::grl($col) === "fff") {
      $return = ' w';
    }
    return $return;
  }

  /**
   * Convert rgb / hex to websafe
   */
  public static function websafe ($col) {
    $out = '';

    // If HEX
    if (is_string($col)) {
      $col = self::hex_2_rgb($col);
    }

    foreach ($col as $c) {
      // convert value
      $c = (round($c/51) * 51);
      // convert to HEX
      $out .= str_pad(dechex($c), 2, '0', STR_PAD_LEFT);
    }
    return $out;
  }

  /**
   * Imprime los valores en formato estandard
   */
  public function prnt( $tipo = 'hex' ) {
    $return = '';
    switch ($tipo) {
      case 'rgb': // RGB
        $return = implode(', ', $this->col['rgb']);
        break;
      case 'rgbF': // RGB Float
        $return = implode(', ', $this->col['rgbF']);
        break;
      case 'rgbLI': // RGB Long Int
        $return = $this->col['rgbLI'];
        break;
      case 'bgrLI': // BGR Long Int
        $return = $this->col['bgrLI'];
        break;
      case 'rgbcss': // RGB CSS
        $return = 'rgb('. implode(', ', $this->col['rgb']) . ');';
        break;
      case 'hsl': // HSL
        $return = implode(', ', $this->col['hsl']);
        break;
      case 'hslF': // HSL Float
        $return = implode(', ', $this->col['hslF']);
        break;
      case 'hslcss': // HSL CSS
        $return = 'hsl('. $this->col['h'] . ', ' . $this->col['s'] . '%, '. $this->col['l'] . '%);';
        break;
      case 'hsb': // HSB
        $return = implode(', ', $this->col['hsb']);
        break;
      case 'cmyk': // CMYK
        $return = implode(', ', $this->col['cmyk']);
        break;
      case 'hex': // HEX
        $return = '#'.$this->col['hex'];
        break;
    }
    return $return;
  }

  /**
   * Set HSL componentes
   */
  public function set_hue ($newhue) {
    $this->col['h'] = $newhue;
    $this->col['hsl'][0] = $newhue;
    $this->update_from_hsl();
  }

  public function set_sat ($newsat) {
    $this->col['s'] = $newsat;
    $this->col['hsl'][1] = $newsat;
    $this->update_from_hsl();
  }

  public function set_lum ($newlum) {
    $this->col['l'] = $newlum;
    $this->col['hsl'][2] = $newlum;
    $this->update_from_hsl();
  }

  public function update_from_hsl() {
    // Updates Rgb

    $this->col['hex'] = self::rgb_2_hex(self::hsl_2_rgb($val));
    $this->col['h'] = $this->col['hsl'][0];
    $this->col['s'] = $this->col['hsl'][1];
    $this->col['l'] = $this->col['hsl'][2];

    $this->col['cmyk']  = null;
    $this->col['rgb']   = null;
    $this->col['rgbF']  = null;
    $this->col['rgbLI'] = null;
    $this->col['bgrLI'] = null;
    $this->col['hslF']  = null;
    $this->col['hsb']   = null;
    $this->col['lab']   = null;
    $this->col['xyz']   = null;

    $this->create_from_hex();

    // Updates Hex
    $this->col['hex'] = $this->rgb_2_hex($this->col['rgb']);
  }

  public function get_JSON($encoded = true) {
    $ret = '';

    $ret['hex'] = '#'.$this->col['hex'];

    $ret['websafe'] = '#'.$this->col['websafe'];

    $ret['rgb'] = $this->col['rgb'];

    $ret['rgb']['r'] = $this->col['rgb'][0];
    $ret['rgb']['g'] = $this->col['rgb'][1];
    $ret['rgb']['b'] = $this->col['rgb'][2];

    $ret['rgbF'] = $this->col['rgbF'];

    $ret['rgbLI'] = $this->col['rgbLI'];

    $ret['bgrLI'] = $this->col['bgrLI'];

    $ret['hsl']['h'] = $this->col['hsl'][0];
    $ret['hsl']['s'] = $this->col['hsl'][1];
    $ret['hsl']['l'] = $this->col['hsl'][2];

    $ret['hslF'] = $this->col['hslF'];

    $ret['hsb']['h'] = $this->col['hsb'][0];
    $ret['hsb']['s'] = $this->col['hsb'][1];
    $ret['hsb']['b'] = $this->col['hsb'][2];

    // $ret['lab']['l'] = $this->col['lab'][0];
    // $ret['lab']['a'] = $this->col['lab'][1];
    // $ret['lab']['b'] = $this->col['lab'][2];

    $ret['cmyk']['c'] = $this->col['cmyk'][0];
    $ret['cmyk']['m'] = $this->col['cmyk'][1];
    $ret['cmyk']['y'] = $this->col['cmyk'][2];
    $ret['cmyk']['k'] = $this->col['cmyk'][3];

    if ($encoded) {
      $ret = json_encode($ret);
      $ret = base64_encode($ret);
    }
    // print_a($ret);

    return $ret;
  }

  public static function palette_from_angles ($hex, $angle_array) {

    $palette = array();
    $palette[] = new self($hex);

    // print_a($palette[0]->col['h']);
    foreach ($angle_array as $val) {
      $temp_hue = ($palette[0]->col['h'] + $val) % 360;
      // print_a($temp_hue);
      $palette[] = new self( [ $temp_hue, $palette[0]->col['s'], $palette[0]->col['l'] ], 'hsl');
    }
    return $palette;
  }

  /* Complementary colors constructors */
  public static function pal_compl ($color_val) {
    return self::palette_from_angles($color_val, array(180));
  }

  /* Triad */
  public static function pal_triad ($color_val) {
    return self::palette_from_angles($color_val, array(120,240));
  }

  /* Tretrad */
  public static function pal_tetrad ($color_val) {
    return self::palette_from_angles($color_val, array(60,180,240));
  }

  /* Analogous */
  public static function pal_analog ($color_val) {
    return self::palette_from_angles($color_val, array(-45,45));
  }

  /* Split complementary */
  public static function pal_split ($color_val) {
    return self::palette_from_angles($color_val, array(150,210));
  }

  /* Accented Analogous */
  public static function pal_accent ($color_val) {
    return self::palette_from_angles($color_val, array(-45,45,180));
  }


}

?>
