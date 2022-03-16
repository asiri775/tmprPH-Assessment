<?php

namespace App\Storage;

use App\Interfaces\StorageInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FileStorage implements StorageInterface
{
    private $dataCollection = [];
    private $status = false;
    private $feedStatus = false;
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
            $this->status = true;
            $this->feedStatus = true;
            $this->dataCollection = $collection;
        } catch (\Exception $e) {
            $this->status = false;
            $this->feedStatus = false;
            $this->dataCollection  = $collection;
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

    public function dataFeedValidation()
    {
        if ($this->status) {
            if($this->feedStatus){
                return response()->json(['status' => 1, 'message' => 'Good to go'], 200);
            }
            else {
                return response()->json(['status' => 0, 'message' => 'Invalid data format given ,please check your data feed.'], 200);
            }
           
        }
         else {
            return response()->json(['status' => 0, 'message' => 'CSV file not available. Please check your data sources'], 200);
        }
    }

    private function weeklyData($data)
    {
        $groupedData = [];
        $collection = [];
        $weekList = [];
        $count = 0;
        try {
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
            $this->feedStatus = true;
            return $collection;
        } catch (\Exception $e) {
            $this->feedStatus = false;
            return $collection;
        }
    }

    private function upcaseData($data)
    {
        $groupedData = [];
        $collection = [];
        $weekList = [];
        $count = 0;
        try {

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
            $this->feedStatus = true;
            return $collection;
        } catch (\Exception $e) {
            $this->feedStatus = false;
            return $collection;
        }
    }
}
