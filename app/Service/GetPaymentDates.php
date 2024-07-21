<?php

namespace App\Service;

use Error;
use Illuminate\Support\Carbon;

class GetPaymentDates
{
    /**
     * Current Date
     */
    protected $today;

    /**
     * Current Month
     */
    protected $month;

    /**
     * Unix time of current date
     */
    protected $time;

    /**
     * Protected constructor. Use to get default date to get Salary date and Bonus Date
     *
     * @param  $date  is today's date
     */
    public function __construct()
    {
        $this->today = Carbon::now();
        $this->month = $this->today->month;
        $this->time = strtotime($this->today);
    }

    /**
     * Generates Payment Dates Report Main function
     *
     * @return file path where CSV has been stored
     */
    public function getPaymentDatesCSV($fileName)
    {
        try {
            $storageDir = $this->StorageDirPath();
            $filePath = $storageDir.$fileName;
            $salaryDate = $this->GetSalaryDate();
            $bonusDate = $this->GetBonusDate();
            $response = $this->GeneratePaymentDateListCsv($salaryDate, $bonusDate, $filePath);
            if (! empty($response)) {
                return $filePath;
            }
        } catch (Error $e) {
            return false;
        }
    }

    /**
     * Get Storage File path inside Storage/app folder.
     *
     * @return storage folder path
     */
    protected static function StorageDirPath()
    {
        $storagePath = storage_path('/');

        return $storagePath;
    }

    /**
     * Generate CSV file from date
     */
    protected function GeneratePaymentDateListCsv($salaryDate, $bonusDate, $filePath)
    {
        /*Export array data in CSV*/
        $csv_handler = fopen($filePath, 'w+');
        $csv = "id,Salary Date, Bonus Date \n"; //Column headers
        fwrite($csv_handler, $csv);
        foreach ($salaryDate as $month => $salDate) {
            $data = $month.','.$salDate.','.$bonusDate[$month]."\n"; //Append data to csv
            fwrite($csv_handler, $data);
        }
        fclose($csv_handler);

        return true;
    }

    /**
     * Get Salary Date
     *
     * @return array of salary date
     */
    public function GetSalaryDate()
    {
        $salaryDate = [];
        $month = $this->month;
        $time = $this->time;
        for ($i = $month; $i <= 12; $i++) {
            /*Prepare Salary Date array*/
            $lastdate = Carbon::parse($time)->endOfMonth();
            $salMonth = $lastdate->format('F');
            if (isWeekEndDay($lastdate)) {
                $salaryDate[$salMonth] = $lastdate->previousWeekday()->toDateString();
            } else {
                $salaryDate[$salMonth] = $lastdate->toDateString();
            }
            // move to next month
            $time = strtotime('+30 days', $time);
        }

        return $salaryDate;
    }

    /**
     * Get Bonus Date
     *
     * @return array of bonus date
     */
    public function GetBonusDate()
    {
        $bonusDate = [];
        $month = $this->month;
        $time = $this->time;
        for ($i = $month; $i <= 12; $i++) {
            /*Prepare Get Bonus Date array*/
            $lastdate = Carbon::parse($time)->endOfMonth();
            $salMonth = $lastdate->format('F');
            $midMonth = Carbon::parse($time)->firstOfMonth()->addDays(14);
            if (isWeekEndDay($midMonth)) {
                $bonusDate[$salMonth] = $midMonth->next('Wednesday')->toDateString();
            } else {
                $bonusDate[$salMonth] = $midMonth->toDateString();
            }
            // move to next month
            $time = strtotime('+30 days', $time);
        }

        return $bonusDate;
    }
}
