<?php
/**
 * @package Validate
 * @subpackage Test
 */

namespace Gustavus\Validate\Test;

use Gustavus\Validate\Validate,
  Gustavus\Test\Test;

/**
 * @package Validate
 * @subpackage Test
 */
class ValidateTest extends Test
{

  /**
   * @return array
   */
  public static function emailData()
  {
    return array(
      array(true, 'jlencion@gustavus.edu'),
      array(true, ' jlencion@gustavus.edu '),

      array(true, 'jlencion+test@gustavus.edu'),
      array(true, 'jlencion@gac.edu'),
      array(true, 'user@q.com'),

      array(false, 'jlencion@gustavus'),
      array(false, '@gustavus.edu'),
      array(false, 'jlencion@.edu'),
      array(false, 'jlencion@gustavus@gustavus.edu'),
      array(false, 'user@totallyabogusdomainnamethisdoesnotexist.com'),
      array(false, 'jlencion'),
      array(false, ''),
    );
  }

  /**
   * @test
   * @dataProvider emailData
   * @param boolean $expected
   * @param string $email
   */
  public function email($expected, $email)
  {
    $this->assertSame($expected, Validate::email($email));
  }

  /**
   * @return array
   */
  public static function dateData()
  {
    return array(
      array(true, '01/09/1983'),
      array(true, '01/9/1983'),
      array(true, '1/09/1983'),
      array(true, '1/9/1983'),
      array(true, '01/09/83'),
      array(true, '1/09/83'),
      array(true, '01/9/83'),
      array(true, '1/9/83'),

      array(true, '1983/01/09'),
      array(true, '1983/01/9'),
      array(true, '1983/1/09'),
      array(true, '1983/1/9'),

      array(true, '01-09-1983'),
      array(true, '1-09-1983'),
      array(true, '01-9-1983'),
      array(true, '1-9-1983'),
      array(true, '01-09-83'),
      array(true, '1-09-83'),
      array(true, '01-9-83'),
      array(true, '1-9-83'),

      array(false, '83/01/09'),
      array(false, '13/09/1983'),
      array(false, '01/32/1983'),
    );
  }

  /**
   * @test
   * @dataProvider dateData
   * @param boolean $expected
   * @param string $date
   */
  public function date($expected, $date)
  {
    $this->assertSame($expected, Validate::date($date));
  }
}
