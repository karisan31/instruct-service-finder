<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../service_finder.php';

class service_finder_test extends TestCase
{
    //Test to see if valid arguments returns the csv file and country code in uppercase
    public function testCommandLineParserValidArguments()
    {
        $arguments = ['service_finder.php', 'services.csv', 'fr'];
        $expected = ['services.csv', 'FR'];
        $this->assertEquals($expected, CommandLineParser::parseArguments($arguments));
    }

    //Test to see if too few arguments returns a usage message to the client
    public function testCommandLineParserInsufficientArguments()
    {
        $arguments = ['service_finder.php'];
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Usage: php service_finder.php <csv_file_path> <country_code>");
        CommandLineParser::parseArguments($arguments);
    }

    //Test to see if too many arguments returns a usage message to the client
    public function testCommandLineParserExtraArguments()
    {
        $arguments = ['service_finder.php', 'services.csv', 'fr', 'hello'];
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Usage: php service_finder.php <csv_file_path> <country_code>");
        CommandLineParser::parseArguments($arguments);
    }

    //Test to see if a valid csv file returns all data from within the file
    public function testCsvReaderValidFile()
    {
        $filePath = 'services.csv';
        $expected = [
            ['Ref', 'Centre', 'Service', 'Country'],
            ['APPLAB1', 'Aperture Science', 'Portal Technology', 'fr'],
            ['BLULAB1', 'Blue Sun Corp', 'Behaviour Modification', 'FR'],
            ['BMELAB1', 'Black Mesa', 'Interdimensional Travel', 'de'],
            ['WEYLAB1', 'Weyland Yutani Research', 'Xeno-biology', 'gb'],
            ['BLULAB3', 'Blue Sun R&D', 'Behaviour Modification', 'cz'],
            ['BMELAB2', 'Black Mesa Second Site', 'Interdimensional Travel', 'DE'],
            ['TYRLAB1', 'Tyrell Research', 'Synthetic Consciousness', 'GB'],
            ['BLULAB2', 'Blue Sun Corp', 'Behaviour Modification', 'it'],
            ['TYRLAB2', 'Tyrell Research', 'Synthetic Optics', 'pt']
        ];
        $this->assertEquals($expected, CsvReader::read($filePath));
    }

    //Test to see if an invalid file returns the correct error message
    public function testCsvReaderInvalidFile()
    {
        $filePath = 'invalid.csv';
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("\nError: File path '$filePath' does not exist.");
        CsvReader::read($filePath);
    }

    //Test to see if data gets correctly filtered by country code
    public function testServiceFilterValidCountryCode()
    {
        $data = [
            ['Ref', 'Centre', 'Service', 'Country'],
            ['APPLAB1', 'Aperture Science', 'Portal Technology', 'fr'],
            ['BMELAB1', 'Black Mesa', 'Interdimensional Travel', 'de'],
        ];
        $countryCode = 'FR';
        $expected = [['APPLAB1', 'Aperture Science', 'Portal Technology', 'fr']];
        $this->assertEquals($expected, ServiceFilter::filterByCountryCode($data, $countryCode));
    }

    //Test to see if function returns an empty array if no services for the given country code exists
    public function testServiceFilterInvalidCountryCode()
    {
        $data = [
            ['Ref', 'Centre', 'Service', 'Country'],
            ['APPLAB1', 'Aperture Science', 'Portal Technology', 'fr'],
            ['BMELAB1', 'Black Mesa', 'Interdimensional Travel', 'de'],
        ];
        $countryCode = 'GB';
        $expected = [];
        $this->assertEquals($expected, ServiceFilter::filterByCountryCode($data, $countryCode));
    }

    //Test to see if 'no services found' message is returned if empty matches
    public function testDisplayEmptyMatchedServices()
    {
        $matchedServices = [];
        $countryCode = 'FR';

        ob_start();

        ServiceDisplay::display($matchedServices, $countryCode);

        $output = ob_get_clean();

        $this->assertEquals("No services found for country code FR.\n", $output);
    }

    //Test to see if data gets correctly filtered by country code
    public function testServiceDisplayValidCountryCode()
    {
        $matchedServices = [
            ['APPLAB1', 'Aperture Science', 'Portal Technology', 'fr'],
            ['BLULAB1', 'Blue Sun Corp', 'Behaviour Modification', 'FR'],
        ];
        $countryCode = 'FR';

        ob_start();
        ServiceDisplay::display($matchedServices, $countryCode);
        $output = ob_get_clean();

        $this->assertStringContainsString("Services for country code FR:\n", $output);
        $this->assertStringContainsString("Ref: APPLAB1\n", $output);
        $this->assertStringContainsString("Centre: Aperture Science\n", $output);
        $this->assertStringContainsString("Service: Portal Technology\n", $output);
        $this->assertStringContainsString("Country: fr\n\n", $output);
        $this->assertStringContainsString("Ref: BLULAB1\n", $output);
        $this->assertStringContainsString("Centre: Blue Sun Corp\n", $output);
        $this->assertStringContainsString("Service: Behaviour Modification\n", $output);
        $this->assertStringContainsString("Country: FR\n\n", $output);
    }
}
