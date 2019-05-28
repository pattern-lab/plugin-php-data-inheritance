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

    protected $storeData;
    protected $storePatternData;
    // To keep track of the processed patterns
    protected $processedPatterns;

    /**
    * Add the listeners for this plug-in
    */
    public function __construct() {

        // add listener
        $this->addListener("patternData.lineageHelperEnd", "inherit");

    }

    /**
    * Look up data in lineages, update pattern store data, replace store
    */
    public function inherit() {

        if ((bool)Config::getOption("plugins.dataInheritance.enabled")) {
            $this->storeData        = Data::get();
            $this->storePatternData = PatternData::get();
            $this->processedPatterns = [];
            foreach ($this->storePatternData as $patternKey => $patternData) {
                if (isset($patternData["lineages"]) && is_array($patternData["lineages"]) && (count($patternData["lineages"]) > 0)) {
                    if (in_array($patternKey, $this->processedPatterns)) {
                        continue;
                    }
                    $this->processPatternData($patternKey);
                }
            }
            Data::replaceStore($this->storeData);
        }

    }

    private function processPatternData($patternKey) {

        $patternData = $this->storePatternData[$patternKey];
        $dataLineage = array();

        foreach ($patternData["lineages"] as $lineage) {

            $lineageKey  = $lineage["lineagePattern"];
            // process sub-lineages first with recursive calls
            if (isset($this->storePatternData[$lineageKey]['lineages']) && count($this->storePatternData[$lineageKey]['lineages']) > 0) {
                $this->processPatternData($lineageKey);
            }
            // merge the lineage data with the lineage store. newer/higher-level data is more important.
            $lineageData = isset($this->storeData["patternSpecific"][$lineageKey]) && isset($this->storeData["patternSpecific"][$lineageKey]["data"]) ? $this->storeData["patternSpecific"][$lineageKey]["data"] : array();

            if (!empty($lineageData)) {
                $dataLineage = array_replace_recursive($dataLineage, $lineageData);
            }

        }

        // merge the lineage data with the pattern data. pattern data is more important.
        $dataPattern = isset($this->storeData["patternSpecific"][$patternKey]) && isset($this->storeData["patternSpecific"][$patternKey]["data"]) ? $this->storeData["patternSpecific"][$patternKey]["data"] : array();
        $dataPattern = array_replace_recursive($dataLineage, $dataPattern);
        if (!empty($dataPattern)) {
            $this->storeData["patternSpecific"][$patternKey]["data"] = $dataPattern;
        }

        $this->processedPatterns[] = $patternKey;

    }

}
