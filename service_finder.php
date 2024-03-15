<?php

class CommandLineParser
{
    //Parses command line arguments and returns an array containing the csv file and country code
    public static function parseArguments(array $arguments): array
    {
        //Checks if exactly three arguments are provided (filepath, csv file and country code)
        if (count($arguments) !== 3) {
            throw new Exception("Usage: php service_finder.php <csv_file_path> <country_code>");
        }
        //Returns an array with the csv file and country code in uppercase
        return [$arguments[1], strtoupper($arguments[2])];
    }
}

class CsvReader
{
    //Reads the csv file and returns its contents as an array
    public static function read(string $filePath): array
    {
        $data = [];
        //Attempts to open the file to then read it
        if (($handle = @fopen($filePath, "r")) !== FALSE) {
            //Reads the file line by line and stores each line as an array in $data
            while (($row = fgetcsv($handle)) !== FALSE) {
                $data[] = $row;
            }
            //This closes the file handle
            fclose($handle);
            //Returns the data as an array
            return $data;
        } else {
            //Throws an exception message if the file does not exist
            throw new Exception("\nError: File path '$filePath' does not exist.\n");
        }
    }
}

class ServiceFilter
{
    //Filters the services based on the provided country code
    public static function filterByCountryCode(array $data, string $countryCode): array
    {
        $matchedServices = [];
        //Iterates over each service data
        foreach ($data as $service) {
            //Checks to see if service's country code matches the provided country code
            if (strtoupper($service[3]) === $countryCode) {
                //Adds to matchedServices array if it matches country code
                $matchedServices[] = $service;
            }
        }
        //Returns$matched services as an array
        return $matchedServices;
    }
}

class ServiceDisplay
{
    //Displays the matched services or a message if no services are found
    public static function display(array $matchedServices, string $countryCode): void
    {
        //Check if there are any matched services
        if (empty($matchedServices)) {
            //Displays a message if there are no matched services to the country code
            echo "No services found for country code $countryCode.\n";
        } else {
            //Displays the macthed services which matches the given country code
            echo "\nServices for country code $countryCode:\n\n";
            foreach ($matchedServices as $service) {
                echo "Ref: $service[0]\n";
                echo "Centre: $service[1]\n";
                echo "Service: $service[2]\n";
                echo "Country: $service[3]\n\n";
            }
        }
    }
}

class Main
{
    //Runs the main function
    public static function run(array $arguments): void
    {
        //Calls the necessary functions to provide services based on given country code
        try {
            list($filePath, $countryCode) = CommandLineParser::parseArguments($arguments);
            $data = CsvReader::read($filePath);
            $matchedServices = ServiceFilter::filterByCountryCode($data, $countryCode);
            ServiceDisplay::display($matchedServices, $countryCode);
        } catch (Exception $e) {
            //Displays an error message if an exception occurs
            echo $e->getMessage() . "\n";
        }
    }
}

// Check if the script is being executed from the command line or in PHPUnit test suite
if (isset($argv) && count($argv) === 3) {
    //If executed from the command line with valid arguments, it runs the main function
    Main::run($argv);
} else {
    //If not executed with valid arguments, it displays usage instructions in PHPUnit test suite
    echo "Usage: php service_finder.php <csv_file_path> <country_code>\n";
}
