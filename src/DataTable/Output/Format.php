<?php 
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output;


/**
 * Basic Formatter
 *
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
abstract class Format 
{
  
  const ESCAPE_SLASHES = -65;  // ~JSON_UNESCAPED_SLASHES
  const ESCAPE_UNICODE = -257; // ~JSON_UNESCAPED_UNICODE
  const ESCAPE_ENTITIES = 11;  // JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT
  
  public $comma;
  public $colon;

 
  /**
   * Creates a new wrapped format
   *
   * @param  string   $comma
   * @param  string   $comma
   * @param  integer  $options
   */
  public function __construct($comma, $colon, $options= 0) 
  {
    $this->comma    = $comma;
    $this->colon    = $colon;
    $this->options  = $options;
  }


  /**
   * Formats an array
   *
   * @param  var[] $value
   * @return string
   */
  protected abstract function formatArray($value);

  /**
   * Formats an object
   *
   * @param  [:var] $value
   * @return string
   */
  protected abstract function formatObject($value);

  /**
   * Open an array or object
   *
   * @param  string $token either `[` or `{`
   * @param  string
   */
  public function open($token) 
  {
    return $token;
  }

  /**
   * Close an array or object
   *
   * @param  string $token either `]` or `}`
   * @param  string
   */
  public function close($token) 
  {
    return $token;
  }

  /**
   * Creates a representation of a given value
   *
   * @param  string $value
   * @return string
   */
  public function representationOf($value) 
  {
    
    if($value instanceof FunctionReferenceType) {
     
      return (string) $value->getValue();
      
    }
    
    $t = gettype($value);
    
    if ('string' === $t) {
      
      return json_encode($value, $this->options);
      
    } else if ('integer' === $t) {
      
      return (string)$value;
      
    } else if ('double' === $t) {
      
      $cast= (string)$value;
     
      return strpos($cast, '.') ? $cast : $cast.'.0';
      
    } else if ('array' === $t) {
      
      if (empty($value)) {
        return '[]';
      } else if (0 === key($value)) {
        return $this->formatArray($value);
      } else {
        return $this->formatObject($value);
      }
      
    } else if (null === $value) {
     
      return 'null';
      
    } else if (true === $value) {
     
      return 'true';
      
    } else if (false === $value) {
      
      return 'false';
      
    } else if ($value instanceof \stdclass) {
      
      $cast= (array)$value;
     
      if (true === empty($cast)) {
        return '{}';
      } else {
        return $this->formatObject($cast);
      }
      
    } else {
      
      throw new OutputException('Cannot represent instances of '. gettype($value));
      
    }
    
  }
  
}
/* End of Class */