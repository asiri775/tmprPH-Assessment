<?php

namespace App\Storage;

use App\Interfaces\StorageInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FileStorage implements StorageInterface
{
    private $dataCollection;
    public function __construct()
    {
        $collection = [];
        try {
            if (($open = fopen(storage_path() . env('DATA_SOURCE_FILE'), "r")) !== false) {
                while (($data = fgetcsv($open, 1000, ";")) !== false) {
                    $collection[] = $data;
                }
                fclose($open);
            }
            $this->dataCollection = $collection;
        } catch (\Exception $e) {
            // Log errors
            //Log::error($e->getMessage());
        }
    }

    public function getWeeklyRetention()
    {
        return response()->json([$this->weeklyData($this->dataCollection)], 200);
    }

    public function getUpScaleRetention()
    {
        return response()->json([$this->upcaseData($this->dataCollection)], 200);
    }

    private function weeklyData($data)
    {
        try {
            $groupedData = [];
            $collection = [];
            $weekList = [];
            $count = 0;
            for ($i = 1; $i < count($data); $i++) {
                $startDay = Carbon::parse($data[$i][1])->startOfWeek()->format('Y-m-d');
                $endDay = Carbon::parse($data[$i][1])->endOfWeek()->format('Y-m-d');
                $accumulationOfCompletion = 0;
                $accumulationCount = 0;
                $averageAccumulationOfCompletion = 0;
                foreach ($data as $key => $record) {
                    if ($key != 0) {
                        $currentDay = Carbon::parse($record[1])->format('Y-m-d');
                        if ($currentDay >= $startDay && $currentDay <= $endDay) {
                            $accumulationOfCompletion += (int)$record[2];
                            $accumulationCount += 1;
                            $i += 1;
                        }
                    }
                }
                $count += 1;
                $i -= 1;
                $averageAccumulationOfCompletion = $accumulationOfCompletion / $accumulationCount;
                array_push($weekList, [$count . ' weeks later']);
                array_push($groupedData, [(int)floor($averageAccumulationOfCompletion)]);
            }
            $collection['x'] = $weekList;
            $collection['y'] = $groupedData;
            return $collection;
        } catch (\Exception $e) {
            // Log errors
            //Log::error($e->getMessage());
        }
    }

    public static function upcaseData($data)
    {
        try {
            $groupedData = [];
            $collection = [];
            $weekList = [];
            $count = 0;
            for ($i = 1; $i < count($data); $i++) {

                $startDay = Carbon::parse($data[$i][1])->startOfWeek()->format('Y-m-d');
                $endDay = Carbon::parse($data[$i][1])->endOfWeek()->format('Y-m-d');
                $recordCount = 0;
                foreach ($data as $key => $record) {
                    if ($key != 0) {
                        $currentDay = Carbon::parse($record[1])->format('Y-m-d');
                        if ($currentDay >= $startDay && $currentDay <= $endDay) {
                            $recordCount += 1;
                            $i += 1;
                        }
                    }
                }
                $count += 1;
                $i -= 1;

                array_push($weekList, [$count . ' weeks later']);
                array_push($groupedData, [(int)floor($recordCount)]);
            }
            $collection['x'] = $weekList;
            $collection['y'] = $groupedData;
            return $collection;
            
        } catch (\Exception $e) {
            // Log errors
            //Log::error($e->getMessage());
        }
    }
}
