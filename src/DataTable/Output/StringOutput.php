<?php 
namespace Bolt\Extension\IComeFromTheNet\BookMe\DataTable\Output;

/**
 * 
 *
 * @modified Lewis Dyer <getintouch@icomefromthenet.com>
 * @since  1.0.0
 */
class StringOutput extends Output 
{
  
  protected $bytes= null;
  
  
  /**
   * Append a token
   *
   * @param  string $bytes
   * @return void
   */
  public function appendToken($bytes) 
  {
    $this->bytes.= $bytes;
  }
  
  
  /** @return string */
  public function bytes() 
  { 
    return $this->bytes; 
    
  }

}
/* End of Class */