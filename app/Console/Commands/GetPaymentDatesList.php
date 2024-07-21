<?php

namespace App\Console\Commands;

use App\Service\GetPaymentDates;
use Illuminate\Console\Command;

class getPaymentDatesList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getpaymentdatescsv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate payment date list For Sales Department';

    /**
     * Execute the console command.
     */
    public function handle(GetPaymentDates $getPaymentDates)
    {
        //
        $fileName = $this->getCommandInput();
        if (! empty($fileName)) {
            $csvfile = $getPaymentDates->getPaymentDatesCSV($fileName);
            if (! empty($csvfile)) {
                $this->info(sprintf('CSV file bas been generated and available at given path : %s', $csvfile));

                return;
            } else {
                $this->error('There is an error while generating CSV file');

                return;
            }
        }
    }

    /**
     * Get file name from input command
     */
    protected function getCommandInput()
    {
        $paymentDateFileName = $this->ask('Please Provide Payment Date File Name', 'paymentDatesFileName');
        if (! checkCsvFile($paymentDateFileName)) {
            $this->error('Error::Please provide valid file name with CSV extension');

            return false;
        }

        return $paymentDateFileName;
    }
}
