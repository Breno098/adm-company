<?php

trait BaseTrait
{
    public static function bootBaseTrait()
    {
        static::creating(function($model) {
        });

        static::updating(function($item) {
        });
    }
}
