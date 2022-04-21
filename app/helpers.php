<?php

if (! function_exists('normatizeRelationshipSync')) {

    /**
     * @param array $relationArray
     *
     * @return array
     */
    function normatizeRelationshipSync(array $relationArray): array
    {
        $normatize = [];
        foreach ($relationArray as $item) {
            $normatize[] = is_array($item) ? $item['id'] : $item;
        }
        return $normatize;
    }
}
