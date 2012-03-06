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
}
