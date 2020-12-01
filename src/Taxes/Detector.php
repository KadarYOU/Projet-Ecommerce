<?php

namespace App\Taxes;

use phpDocumentor\Reflection\Types\Boolean;

class Detector
{
  public $min;
  public function __construct($min)
  {
    $this->$min = $min;
  }
  public function detector(float $prix): bool
  {
    if ($prix > $this->$min) {
      return true;
    }
    return false;
  }
}
