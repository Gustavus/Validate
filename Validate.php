<?php
/**
 * @package General
 */

namespace Gustavus\Validate;

use Gustavus\Regex\Regex;

/**
 * Validates data
 *
 * Example:
 * <code>if (Validate::email('joe@gustavus.edu')) echo 'valid e-mail address';</code>
 *
 * @author Joe Lencioni <joe@gustavus.edu>
 * @package General
 */
class Validate
{
  const CARD_AMERICAN_EXPRESS = 'amex';
  const CARD_DISCOVER         = 'discover';
  const CARD_MASTER_CARD      = 'mc';
  const CARD_VISA             = 'visa';
  const CARD_UNKNOWN          = 'unknown';

  /**
  * Validate an e-mail address
  *
  *
  * Boolean output of an email address validity function should never be treated as definitive with respect to displaying errors and restricting what the user of a web app can do.
  *
  * In more simple terms, if a user-supplied email address fails a validity check, don't tell the user the email address they entered is invalid and force them to enter something different.
  *
  * Use such email validity checks as guidelines only, word errors to state that the email address might be invalid, ask the user to check and always always give the user the option of bypassing the validity check - it's really infuriating to be told your email address is invalid when you know for sure this is not the case!
  *
  * Example:
  * <code>if (Validate::email('joe@gustavus.edu')) echo 'valid e-mail address';</code>
  *
  * @param string $email E-mail address to validate
  * @return bool true if valid, otherwise false
  */
  public static function email($email)
  {
    $email  = trim($email);

    // if the email address is empty
    if (empty($email)) {
      return false;
    }

    // if the email address is an invalid format (for a more robust version see http://code.iamcal.com/php/rfc822/)
    if (!preg_match(Regex::emailAddress(), $email, $matches)) {
      return false;
    }

    // if the domain is not in the DNS (check MX first and fall back to A)
    // add a dot for when searching for a fully qualified domain which has the same name as a host on your local domain (this will not affect
    // standard results)
    if (checkdnsrr($matches[2] . '.', 'MX')) {
      return true;
    }

    if (checkdnsrr($matches[2] . '.', 'A')) {
      return true;
    }

    // Something didn't check out
    return false;
  }

  /**
  * Validate a date in MM/DD/YYYY or YYYY/MM/DD
  * MM and DD can be single digits
  *
  * @param string $date
  * @return bool
  */

  public static function date($date)
  {
    $date = str_replace('-', '/', $date);
    $date = date_parse($date);
    if (checkdate($date['month'], $date['day'], $date['year']) && !isset($date['tz_abbr']) && $date['error_count'] === 0 && strlen($date['year']) === 4) {
      return true;
    }
    return false;
  }

  /**
  * Validate a credit card number
  *
  * @param string $card Credit card number
  * @return string|bool Type of credit card if number is valid, false if number is invalid
  */

  public static function creditcard($card)
  {
    // Remove all non-digits
    $card = preg_replace('/\D/', '', $card);

    if (!$card) {
      return false;
    }

    if (self::luhn($card)) {
      return self::cardType($card);
    } else {
      return false;
    }
  }

  /**
  * Luhn algorithm number checker - (c) 2005-2008 shaman - www.planzero.org
  * This code has been released into the public domain, however please
  * give credit to the original author where possible.
  *
  * @param string $number
  * @return bool
  * @link http://planzero.org/code/bits/viewcode.php?src=luhn_check.phps
  */
  protected static function luhn($number)
  {
    // Strip any non-digits (useful for credit card numbers with spaces and hyphens)
    $number = preg_replace('/\D/', '', $number);

    if (empty($number)) {
      return false;
    }

    // Set the string length and parity
    $numberLength = strlen($number);
    $parity       = $numberLength % 2;

    // Loop through each digit and do the maths
    $total = 0;
    for ($i = 0; $i < $numberLength; ++$i) {
      $digit = $number[$i];
      // Multiply alternate digits by two
      if ($i % 2 == $parity) {
        $digit *= 2;
        // If the sum is two digits, add them together (in effect)
        if ($digit > 9) {
          $digit -= 9;
        }
      }
      // Total up the digits
      $total += $digit;
    }

    // If the total mod 10 equals 0, the number is valid
    return ($total % 10 == 0) ? true : false;
  }

  /**
  * Determine the type of credit card
  *
  * @param int $card Credit card number
  * @return string Type of credit card, 'amex', 'discover', 'mc', 'visa', or 'unknown' if type is unknown
  */
  protected static function cardtype($card)
  {
    $len  = strlen($card);
    if ($len == 15 && $card[0] == '3') {
      return self::CARD_AMERICAN_EXPRESS;
    } else if ($len == 16 && substr($card, 0, 4) == '6011') {
      return self::CARD_DISCOVER;
    } else if ($len == 16 && $card[0] == '5') {
      return self::CARD_MASTER_CARD;
    } else if (($len == 16 || $len == 13) && $card[0] == '4') {
      return self::CARD_VISA;
    }

    return self::CARD_UNKNOWN;
  }
}
