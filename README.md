# Instruct-service-finder

## Description

This repository allows for the client to run a PHP command line interface (CLI) program which enables them to retrieve services based on country code. It provides the client with a breakdown of the summary output including the reference, centre and service.

## Prerequisites
Before you start, make sure you have the following prerequisites installed:
- [XAMPP](https://www.apachefriends.org/index.html)
- [Composer](https://getcomposer.org/)

## Installation

1. Start XAMPP and ensure Apache and MySQL servers are running

2. Navigate to the `htdocs` directory:

```
cd xampp/htdocs
```

3. Clone the repository:

```
git clone https://github.com/karisan/instruct-service-finder.git
```

4. Navigate to the project directory:

```
cd instruct-service-finder
```

5. Install dependencies:

```
composer install
```

## Usage

Ensure PHP can run on your terminal:

1. Navigate to Advanced System Settings in your system's Control Panel
2. Click on Environment Variables
3. Click on path
4. Create New Path
5. Copy in the directory in which php.exe exists

This [video](https://www.youtube.com/watch?v=NAvMN2tqBZM) provides a step-by-step guide.

To run the command line application, use the following command

```
php service_finder.php <csv_file_path> <country_code>
```

Ensure you replace `<csv_file_path>` with the path to your CSV file containing service data in the format of `'Ref', 'Centre', 'Service', 'Country'` and for ease, ensure it is located in the root directory (or just provide the path instead).

Ensure you replace `<country_code>` with the relevant country code to filter services by. Capitalisation is not necessary in this program.

Example usage:

```
php service_finder.php services.csv fr
```

## Tests
This command line application was creating using test-driven development (TDD) to ensure everything worked as intended. To run the PHPUnit tests yourself, execute the following command:

```
vendor/bin/phpunit tests/service_finder_test.php
```
