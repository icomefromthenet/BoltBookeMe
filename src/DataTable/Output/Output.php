<?php 
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output;

/**
 * 
 *
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
abstract class Output
{
  
  /**
   * @var Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\Format
   */ 
  public $oFormat;

  /**
   * Creates a new instance
   *
   * @param  Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output\Format $oFormat
   */
  public function __construct(Format $oFormat) 
  {
    $this->oFormat = $oFormat;
  }

  /**
   * Writes a given value using a recursive operation
   *
   * @param  mixed  $value  a value to write
   * @return self
   */
  public function write($value)
  {
    $f= $this->oFormat;
    
    if ($value instanceof \Traversable || is_array($value)) {
      
      $i= 0;
      $map= null;
      
      foreach ($value as $key => $element) {
       
        if (0 === $i++) {
          $map= 0 !== $key;
          $this->appendToken($f->open($map ? '{' : '['));
        } else {
          $this->appendToken($f->comma);
        }

        if ($map) {
          $this->appendToken($f->representationOf((string)$key).$f->colon);
        }
        
        $this->write($element);
        
      }
      
      if (null === $map) {
        $this->appendToken('[]');
      } else {
        $this->appendToken($map ? '}' : ']');
      }
      
    } else {
      $this->appendToken($f->representationOf($value));
    }
    
    
    return $this;
  }

  /**
   * Append a token
   *
   * @param  string $bytes
   * @return void
   */
  public abstract function appendToken($bytes);

  
}
/* End of Class */