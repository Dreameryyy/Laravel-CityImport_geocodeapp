<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use voku\helper\HtmlDomParser;
use App\Models\City;
use Illuminate\Support\Facades\Log;

class ImportCityData extends Command
{
    protected $signature = 'data:import';
    protected $description = 'Import Slovak cities from e-obce.sk into the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('Starting data import...');

        $baseUrl = 'https://www.e-obce.sk';
        $nitraUrl = $baseUrl . '/kraj/NR.html';
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ]);

        // Fetch the HTML content
        $nitraHtml = file_get_contents($nitraUrl, false, $context);

        if ($nitraHtml === false) {
            $this->error("Failed to convert encoding for the main page using iconv.");
            return;
        }

        $nitraDom = HtmlDomParser::str_get_html($nitraHtml);

        $subDistrictLinks = $nitraDom->find('a.okreslink');

        foreach ($subDistrictLinks as $subDistrictLink) {
            $subDistrictUrl = $subDistrictLink->getAttribute('href');
            if (!str_starts_with($subDistrictUrl, 'http')) {
                $subDistrictUrl = $baseUrl . $subDistrictUrl;
            }
            $this->info("Processing sub-district URL: " . $subDistrictUrl);

            // Fetch the subdistrict HTML content
            $subDistrictHtml = file_get_contents($subDistrictUrl, false, $context);

            // Convert encoding from Windows-1250 to UTF-8 using iconv
            $subDistrictHtml = iconv('Windows-1250', 'UTF-8//TRANSLIT//IGNORE', $subDistrictHtml);
            if ($subDistrictHtml === false) {
                $this->error("Failed to convert encoding for URL: $subDistrictUrl using iconv.");
                continue;
            }

            $subDistrictDom = HtmlDomParser::str_get_html($subDistrictHtml, true, true, 'UTF-8');

            $cityLinks = $subDistrictDom->find('table[cellpadding="3"] a');
            $filteredCityLinks = [];

            foreach ($cityLinks as $cityLink) {
                $cityLinkUrl = $cityLink->getAttribute('href');
                if (strpos($cityLinkUrl, 'obec') !== false) {
                    $filteredCityLinks[] = $cityLinkUrl;
                }
            }

            $this->info("Filtered city links found: " . count($filteredCityLinks));

            foreach ($filteredCityLinks as $cityUrl) {
                try {
                    $this->processCity($cityUrl, $baseUrl, $context);
                } catch (\Exception $e) {
                    $this->error("Failed to process city URL: $cityUrl with error: " . $e->getMessage());
                    continue;
                }
            }
        }

        $this->info('All cities have been imported successfully!');
    }

    protected function processCity($cityUrl, $baseUrl, $context)
    {
        if (!str_starts_with($cityUrl, 'https')) {
            $cityUrl = $baseUrl . $cityUrl;
        }

        $this->info("Processing city URL: $cityUrl");

        // Fetch the city HTML content
        $cityHtml = file_get_contents($cityUrl);

        // Convert encoding from Windows-1250 to UTF-8 using iconv
        $cityHtml = iconv('Windows-1250', 'UTF-8//TRANSLIT//IGNORE', $cityHtml);

        $cityDom = HtmlDomParser::str_get_html($cityHtml);

        $cityNameElement = $cityDom->findOne('h1');
        if ($cityNameElement) {
            // Decode HTML entities and trim the name
            $name = trim($cityNameElement->plaintext);
            
            // Correct the bad encoding
            $name = $this->correctEncoding($name);
            // Remove "Obec" and "Mesto" prefix if it exists
            if (str_starts_with($name, 'Obec ')) {
                $name = substr($name, 5); // Remove the first 5 characters ("Obec ")
            } elseif (str_starts_with($name, 'Mesto ')) {
                $name = substr($name, 6); // Remove the first 6 characters ("Mesto ")
            }
            $this->info("Processing city: $name");
        } else {
            $this->error("City name not found for URL: $cityUrl");
            return;
        }

        $mayorName = $address = $phone = $fax = $email = $web = $coatOfArmsUrl = null;

        // Extract phone number
        $phoneElement = $cityDom->findOne('td:contains("Tel:") + td');
        if ($phoneElement) {
            $phone = trim($phoneElement->plaintext);
        }

        // Extract fax
        $faxElement = $cityDom->findOne('td:contains("Fax:") + td');
        if ($faxElement) {
            $fax = trim($faxElement->plaintext);
        }

        // Extract email
        $emailElement = $cityDom->findOne('td:contains("Email:") + td a');
        if ($emailElement) {
            $email = trim($emailElement->getAttribute('href'));
            $email = str_replace('mailto:', '', $email);
        }

        $addressElements = $cityDom->find('td[valign="top"]');

        // Check if the required indices exist in the $addressElements array
        if (isset($addressElements[15]) && isset($addressElements[16])) {
            $addressPart1 = trim($addressElements[15]->plaintext); // First part of the address
            $addressPart2 = trim($addressElements[16]->plaintext); // Second part of the address
            $address = $addressPart1 . ', ' . $addressPart2;
            $address = $this->correctEncoding($address);
        }

        if (!empty($address)) {
            $this->info("Address found: " . $address);
        } else {
            $this->error("Address not found.");
        }


        // Extract web
        $webElement = $cityDom->findOne('td:contains("Web:") + td a');
        if ($webElement) {
            $web = trim($webElement->getAttribute('href'));
        }

        // First, try to find the "Starosta"
        $mayorElement = $cityDom->findOne('td:contains("Starosta:") + td');
        if ($mayorElement) {
            $mayorName = trim($mayorElement->plaintext);
        }

        // If "Starosta" was not found, try "Primátor"
        if (!$mayorName) {
            $mayorElement = $cityDom->findOne('td:contains("PrimĂˇtor:") + td');
            if ($mayorElement) {
                $mayorName = trim($mayorElement->plaintext);
            }
        }
        //If both fail, try element
        if (!$mayorName) {
            $mayorNameElements = $cityDom->find('td');
            $mayorName = trim($mayorNameElements[126]->plaintext);

        }

        if ($mayorName) {
            $mayorName = $this->correctEncoding($mayorName);
            $this->info("Mayor name found: " . $mayorName);
        } else {
            $this->error("Mayor name not found.");
        }

        // Extract coat of arms URL and download the image
        $coatOfArmsElement = $cityDom->findOne('img[alt^="Erb"]');
        if ($coatOfArmsElement) {
            $coatOfArmsUrl = $coatOfArmsElement->getAttribute('src');
            if (!str_starts_with($coatOfArmsUrl, 'http')) {
                $coatOfArmsUrl = $baseUrl . $coatOfArmsUrl;
            }

            // Generate a unique filename based on the coat of arms URL
            $imageHash = md5($coatOfArmsUrl);
            $imageName = $name . '-' . $imageHash . '.png';
            $imagePath = public_path('/images/coat_of_arms/' . $imageName);

            // Check if the image already exists
            if (!file_exists($imagePath)) {
                // Download the coat of arms image if it doesn't exist
                $imageContent = file_get_contents($coatOfArmsUrl, false, $context);
                if ($imageContent !== false) {
                    // Save the image to the designated path
                    file_put_contents($imagePath, $imageContent);

                    // Store the relative path to the image
                    $coatOfArmsUrl = '/images/coat_of_arms/' . $imageName;
                } else {
                    $this->error("Failed to download coat of arms for city: $name");
                }
            } else {
                // If the image already exists, use the existing file
                $coatOfArmsUrl = '/images/coat_of_arms/' . $imageName;
            }
        }

        

        City::updateOrCreate(
            ['name' => $name],
            [
                'mayor_name' => $mayorName,
                'city_hall_address' => $address,
                'phone' => $phone,
                'fax' => $fax,
                'email' => $email,
                'web_address' => $web,
                'coat_of_arms_path' => $coatOfArmsUrl,
            ]
        );

        $this->info("Successfully imported city: $name");
    }
    //Use map to encode 
    //The map is used because iconv and mb_convert_encoding wasn't encoding all characters to desired ones.
    protected function correctEncoding($text)
    {
        $map = [
            'ÄąÂ ' => 'Š',
            'Ä‚' => 'Á',
            'Ä' => 'Č',
            'Ă„Ĺ˝' => 'Ď',
            'Ä‚‰' => 'É',
            'Ă' => 'Í',
            'Ă„Ëť' => 'Ľ',
            'Ĺ' => 'Ň',
            'Ă' => 'Ó',
            'Ĺ¤' => 'Ť',
            'Ĺ˝' => 'Ž',
            'ĂĄ' => 'á',
            'Ä‚¤' => 'ä',
            'Ä' => 'č',
            'Ä' => 'ď',
            'ĂŠ' => 'é',
            'šÄ‚­' => 'í',
            'Äľ' => 'ľ',
            'Ä‚ň‚' => 'ó',
            'Ä‚´' => 'ô',
            'Ĺˇ' => 'š',
            'ÄąÄ„' => 'ť',
            'Ä‚Ëť' => 'ý',
            'ÄąÄľ' => 'ž',
            'ÄŚ' => 'Č',
            'ÄŤ' => 'č',
            'Ĺ˝' => 'Ž',
            'Ă”' => 'Ô',
            'Ă–' => 'Ö',
            'Ăœ' => 'Ü',
            'Ă‡' => 'Ç',
            'Â' => '',
            'Ä' => 'Š',
            'Ä' => 'Ō',
            'Äľ' => 'ľ',
            'Ä˝' => 'Ľ',
            'Ă”' => 'Ô',
            'Ă›' => 'Û',
            'Ă†' => 'Æ',
            'Ă' => 'Ø',
            'Ăž' => 'Þ',
            'ĂŸ' => 'ß',
            'Ăš' => 'Ú',
            'Äľ' => 'ľ',
            'Ăš' => 'Ú',
            'ÄŽ' => 'Ď',
            'Ĺľ' => 'ž',
            'Ă„' => 'Ä',
            'Ä‚©' => 'é',
            'ÄŤ' => 'č',
            'Ĺ' => 'Š',
            'Äť' => 'ľ',
            'Ĺ¤' => 'Ť',
            'Ä‚ňź ' => 'ú',
            'ĂŽ' => 'Î',
            'Ä˝' => 'Ľ',
            'Äľ' => 'ľ',
            'ÄąË‡' => 'š',
            'Ä‚­­' => 'í',
            'Ĺš' => 'Ś',
            'Ä‚Ä˝' => 'ű',
            'Ă‹' => 'Ë',
            'Ĺ˘' => 'Ţ',
            'Ĺ‘' => 'ő',
            'Ă¤' => 'ä',
            'Ă”' => 'Ô',
            'Ăš' => 'Ú',
            'Äąľ' => 'ž',
            'Äľ' => 'ľ',
            'Äľ' => 'ľ',
            'Ă¦' => 'æ',
            'Ă¸' => 'ø',
            'Ă„Äľ' => 'ľ',
            'Ä”' => 'Ó',
            'Äť' => 'ľ',
            'Äť' => 'ľ',
            'Äť' => 'ľ',
            'Ă“' => 'Ó',
            'Ä‚Ë‡' => 'á',
            'Ä‚¶' => 'ö',
            'Ä‚Ľ' => 'ü',
            'ÄąÂ ' => 'Š',
            'Äą' => 'ň',
            'Ą' => 'ť',
            'Ă' => 'Á',
            'Ă„Ĺ¤' => 'č',
            'Ä‚”' => 'Ó',
            'Ä‚Ĺ‚' => 'ó',
            'Ä‚Ĺź' => 'ú',
            'Ă„Ĺš' => 'Č',
            'Ä‚Â­­' => 'í­',
            'Ä‚Â­' => 'í',
            'ÄąÂ ' => 'Š',
            'Ä‚ĹˇĂ„Ä' => 'Ú',
            'ÄąËť' => 'Ž',
            'Äą”' => 'ő',
            'Ă„Ĺą' => 'ď',
            'Ä‚–' => 'Ö',
            'Äąâ„˘' => 'ř',
            'Ă„”ş' => 'ě'

        ];
        $correctedText = strtr($text, $map);
        return $correctedText;
    }
}
