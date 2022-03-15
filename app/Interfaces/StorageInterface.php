<?php
namespace App\Interfaces;
interface StorageInterface 
{
    public function getWeeklyRetention();
    public function getUpScaleRetention();
    
}