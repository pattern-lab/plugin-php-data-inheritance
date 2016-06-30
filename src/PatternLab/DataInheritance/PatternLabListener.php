<?php

/*!
 * Data Inheritance Listener Class
 *
 * Copyright (c) 2016 Dave Olsen, http://dmolsen.com
 * Licensed under the MIT license
 *
 * Allows patterns to inherit data from patterns in their lineage
 *
 */

namespace PatternLab\DataInheritance;

use \PatternLab\Config;
use \PatternLab\Data;
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
  * Look up data in lineages, update pattern store data, replace store
  */
  public function inherit() {
    
    if ((bool)Config::getOption("plugins.dataInheritance.enabled")) {
      
      $storeData        = Data::get();
      $storePatternData = PatternData::get();
      
      foreach ($storePatternData as $patternStoreKey => $patternData) {
        
        if (isset($patternData["lineages"]) && (count($patternData["lineages"]) > 0)) {
          
          $dataLineage = array();
          
          foreach($patternData["lineages"] as $lineage) {
            
            // merge the lineage data with the lineage store. newer/higher-level data is more important.
            $lineageKey  = $lineage["lineagePattern"];
            $lineageData = isset($storeData["patternSpecific"][$lineageKey]) && isset($storeData["patternSpecific"][$lineageKey]["data"]) ? $storeData["patternSpecific"][$lineageKey]["data"] : array();
            if (!empty($lineageData)) {
              $dataLineage = array_replace_recursive($dataLineage, $lineageData);
            }
            
          }
          
          // merge the lineage data with the pattern data. pattern data is more important.
          $dataPattern = isset($storeData["patternSpecific"][$patternStoreKey]) && isset($storeData["patternSpecific"][$patternStoreKey]["data"]) ? $storeData["patternSpecific"][$patternStoreKey]["data"] : array();
          $dataPattern = array_replace_recursive($dataLineage, $dataPattern);
          
          if (!empty($dataPattern)) {
            $storeData["patternSpecific"][$patternStoreKey]["data"] = $dataPattern;
          }
          
        }
        
      }
      
      Data::replaceStore($storeData);
      
    }
    
  }
  
}
