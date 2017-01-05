<style>
  .DbugL {
    float: left;
    margin-right: 4px;
  }
</style>
<?php

  include 'src/debuglib.php';
  include 'src/colz.php';

  $loops = 1;

  for ($i = 0; $i < $loops; $i++) {
    # code...
    $random_hex = str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);

    $hex = new Colz\Colz( '#'.$random_hex, 'hex' );

    # $rgb   = new Colz\Colz( $hex->col['rgb'],   'rgb');
    // $rgbF  = new Colz\Colz( $hex->col['rgbF'],  'rgbF');
    // $rgbLI = new Colz\Colz( $hex->col['rgbLI'], 'rgbLI');
    // $bgrLI = new Colz\Colz( $hex->col['bgrLI'], 'bgrLI');
    // $hsl   = new Colz\Colz( $hex->col['hsl'],   'hsl');
    // $hslF  = new Colz\Colz( $hex->col['hslF'],  'hslF');

    /*$hex = '4a06d8';
    print_a( $hex );
    $rgb = Colz\Colz::hex_2_rgb( $hex );
    print_a( $rgb );
    $hsl = Colz\Colz::rgb_2_hsl( $rgb );
    print_a( $hsl );
    $rgb_back = Colz\Colz::hsl_2_rgb( $hsl );
    print_a( $rgb_back );*/

    $hex = new Colz\Colz( '#ececec', 'hex' );
    $hsb  = new Colz\Colz( [ 0, 0, 92.5 ],  'hsb');

    print_a($hex);
    print_a($hsb); die;

    die;
  }


// [ 0.63644444444444, 0.8361, 0.5216 ]
  # print_a($bgrLI);

?>