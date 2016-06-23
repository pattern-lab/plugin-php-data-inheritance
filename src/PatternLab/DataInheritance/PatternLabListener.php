<?php

/*!
 * Faker Listener Class
 *
 * Copyright (c) 2016 Dave Olsen, http://dmolsen.com
 * Licensed under the MIT license
 *
 * Adds Faker support to Pattern Lab
 *
 */

namespace PatternLab\DataInheritance;

use \PatternLab\Config;
use \PatternLab\PatternData;

class PatternLabListener extends \PatternLab\Listener {
  
  /**
  * Add the listeners for this plug-in
  */
  public function __construct() {
    
    // add listener
    $this->addListener("patternData.lineageHelperEnd","inherit");
    
  }
  
  /**
  * Fake some content. Replace the entire store.
  */
  public function inherit() {
    
    if ((bool)Config::getOption("plugins.dataInheritance.enabled")) {
      
      $store = PatternData::get();
      
      foreach ($store as $patternStoreKey => $patternData) {
        
        if (count($patternData["lineages"]) > 0) {
          
          $data = PatternData::getPatternOption($patternStoreKey, "data");
          
          foreach($patternData["lineages"] as $lineage) {
            
            $lineageData = PatternData::getPatternOption($lineage["lineagePattern"], "data");
            $data = array_replace_recursive($data, $lineageData);
            
          }
          
          PatternData::setPatternOption($patternStoreKey, "data", $data);
          
        }
        
      }
      
    }
    
  }
  
}
