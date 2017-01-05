<?php
  include 'src/colz.php';

  use PHPUnit\Framework\TestCase;

  class ColzTest extends TestCase {

    public function testSample() {

      $num_tests = 500000;

      for ($i=0; $i < $num_tests; $i++) {
        # code...
        $random_hex = substr(md5(rand()), 0, 6);

        $hex = new Colz\Colz( '#'.$random_hex, 'hex' );

        echo "\n---\nColor #$random_hex";

        $rgb   = new Colz\Colz( $hex->col['rgb'],   'rgb');
        $rgbF  = new Colz\Colz( $hex->col['rgbF'],  'rgbF');
        $rgbLI = new Colz\Colz( $hex->col['rgbLI'], 'rgbLI');
        $bgrLI = new Colz\Colz( $hex->col['bgrLI'], 'bgrLI');
        $hsl   = new Colz\Colz( $hex->col['hsl'],   'hsl');
        $hslF  = new Colz\Colz( $hex->col['hslF'],  'hslF');
        $hsb  = new Colz\Colz( $hex->col['hsb'],  'hsb');

        // print_a($hex);
        // print_a($rgbF); die;

        $keys = [ 'hex', 'rgb', 'rgbF', 'rgbLI', 'bgrLI', 'hsl', 'hslF' ];

        // Assert
        foreach ($keys as $key) {
          echo "\nTesting $key";
          # code...
          $this->assertEquals(
            $hex->col[ $key ],
            $rgb->col[ $key ]
          );
          $this->assertEquals(
            $hex->col[ $key ],
            $rgbF->col[ $key ]
          );
          $this->assertEquals(
            $hex->col[ $key ],
            $rgbLI->col[ $key ]
          );
          $this->assertEquals(
            $hex->col[ $key ],
            $bgrLI->col[ $key ]
          );
          $this->assertEquals(
            $hex->col[ $key ],
            $hsl->col[ $key ]
          );
          $this->assertEquals(
            $hex->col[ $key ],
            $hslF->col[ $key ]
          );
          $this->assertEquals(
            $hex->col[ $key ],
            $hsb->col[ $key ]
          );
        }
      }


    }

  }


?>