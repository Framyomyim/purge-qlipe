<?php 

    function getParser(string $tag, string $raw, string $start, string $end) : array {
        preg_match_all('/'. $start . $tag .'\s*([^>]*)\s*\/?'. $end .'/', $raw, $customTags, PREG_SET_ORDER);

        $results = [];

        foreach($customTags as $customTag) {
            $originalTag = $customTag[0];
            $rawAttributes = $customTag[1];

            preg_match_all('/([^=\s]+)="([^"]+)"/', $rawAttributes, $attributes, PREG_SET_ORDER);

            $formatedAttributes = array();

            foreach($attributes as $attribute) {
                $name = $attribute[1];
                $value = $attribute[2];

                $formatedAttributes[$name] = $value;
            }

            array_push($results, [
                'raw'           => $originalTag,
                'attributes'    => $formatedAttributes
            ]);

        }

        return $results;
    }

?>