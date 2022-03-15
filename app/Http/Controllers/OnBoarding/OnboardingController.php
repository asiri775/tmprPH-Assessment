<?php

namespace App\Http\Controllers\OnBoarding;

use App\Http\Controllers\Controller;
use App\Interfaces\StorageInterface;
class OnboardingController extends Controller
{
    private $storage;

    public function __construct(StorageInterface $storage){
        $this->storage = $storage;
    }

    public function weeklyData(){
        return $this->storage->getWeeklyRetention();
    }

    
    public function upcaseData(){
        return $this->storage->getUpScaleRetention();
    }


    public function index()
    {
          return view('Onboarding/home');
    }

}
