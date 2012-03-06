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

  /**
   * @return array
   * @link http://www.paypalobjects.com/en_US/vhelp/paypalmanager_help/credit_card_numbers.htm
   */
  public static function cardData()
  {
    return array(
      array(Validate::CARD_AMERICAN_EXPRESS, '378282246310005'),
      array(Validate::CARD_AMERICAN_EXPRESS, '371449635398431'),
      array(Validate::CARD_AMERICAN_EXPRESS, '378734493671000'), // American Express Corporate

      //array(Validate::CARD_UNKNOWN, '5610591081018250'), // Australian BankCard (defunct)
      array(Validate::CARD_UNKNOWN, '30569309025904'), // Diners Club
      array(Validate::CARD_UNKNOWN, '38520000023237'), // Diners Club

      array(Validate::CARD_DISCOVER, '6011111111111117'), // Discover
      array(Validate::CARD_DISCOVER, '6011000990139424'), // Discover

      array(Validate::CARD_UNKNOWN, '3530111333300000'), // JCB
      array(Validate::CARD_UNKNOWN, '3566002020360505'), // JCB

      array(Validate::CARD_MASTER_CARD, '5555555555554444'), // MasterCard
      array(Validate::CARD_MASTER_CARD, '5105105105105100'), // MasterCard

      array(Validate::CARD_VISA, '4111111111111111'), // Visa
      array(Validate::CARD_VISA, '4012888888881881'), // Visa

      // According to PayPal, even though this number has a different character count than the other test numbers, it is the correct and functional number.
      array(Validate::CARD_VISA, '4222222222222'), // Visa

      // array(Validate::CARD_UNKNOWN, '76009244561'), // Dankort (PBS)
      // array(Validate::CARD_UNKNOWN, '5019717010103742'), // Dankort (PBS)
      array(Validate::CARD_UNKNOWN, '6331101999990016'), // Switch/Solo (Paymentech)


      array(false, ''),
      array(false, '0'),
      array(false, '1234567812345678'),
    );
  }

  /**
   * @test
   * @dataProvider cardData
   * @param string $cardType
   * @param string $cardNumber
   */
  public function cardType($cardType, $cardNumber)
  {
    if ($cardType === false) {
      $cardType = Validate::CARD_UNKNOWN;
    }

    $this->assertSame($cardType, $this->call('\Gustavus\Validate\Validate', 'cardType', array($cardNumber)));
  }

  /**
   * @test
   * @dataProvider cardData
   * @param string $cardType
   * @param string $cardNumber
   */
  public function luhn($cardType, $cardNumber)
  {
    $this->assertSame(is_string($cardType), $this->call('\Gustavus\Validate\Validate', 'luhn', array($cardNumber)));
  }

  /**
   * @test
   * @dataProvider cardData
   * @param string $cardType
   * @param string $cardNumber
   */
  public function creditCard($cardType, $cardNumber)
  {
    $this->assertSame($cardType, Validate::creditCard($cardNumber));
  }


}
