Rotten Tomatoes ratings exporter
=========

The Rotten Tomatoes ratings exporter is pretty straightforward. It allows you to extract your ratings from the website. You can then save it to the format you want (either with serialize, json_encode and so on).

This exporter was written so that I could export my ratings and import them later on into other movie tracking websites (such as IMDB).

Requirements
------------

* PHP 5.4 <

How to use
----------

In order to use the exporter, you will need to do a couple of things:

1. You need to figure out 2 values that you will need to pass to the constructor of RottenTomatoesExporter. You can get those two values by logging into your Rotten Tomatoes account and then finding the generated cookies key=value required.Those two keys are *session_id* and *fbsr_ID* (where ID is going to be specific to your account).

2. Write a simple script that will load the exporter, set the required values and then call export) to obtain the data. You can then call get_results() to obtain the array of results so that you may save them (or use them) however you like.

```php
<?php
require_once 'exporter.php';

// Change SESSION_ID_FROM_COOKIES
$session_id = 'SESSION_ID_FROM_COOKIES';
// Change fbsr_ID and FBSR_VALUE_FROM_COOKIES
$fb = array('fbsr_ID' => 'FBSR_VALUE_FROM_COOKIES');

$exporter = new RottenTomatoesExporter($session_id, $fb);
$exporter->export();
$exporter->get_results();

// Save in the format you want
```

Example
-------

```php
<?php
require_once 'exporter.php';

$session_id = 'owjfmriqnm-948506847';

$fb = array('fbsr_556748447624' => 'Pqz6H8gVG4ZDe9ijIfvyui-kEx_7GfRfgoOLrhRfXi8.eyJhbGdvcml0aG0iOiJITUFDLVNIQTI1NiIsImNvZGUiOiJBUUQ5LTE1ZFhxN1ExM0I3eWx2RkFnM0M3NXU3MjJxaFpGY1FzX3QxWWdTWmxBYV9ZNzBRVWNNVWpoUHNHUG5lMTljY1BYOHJPZi1HN1dPaTNyQkdGd3NwOEVwVzBXWDdTaExrMjdabU9iRlRvQWVtbjJ5RnpXWXVJUXJ4U1duRDdwUVNTdmtCMlVGRlFha1ZSWnRHbnpKamFPdVhNZnkyNjR6dlBUSHhaZ0xDRmpzcng4NGVoYnRzc3llSkgtV05TUFBoVGUyYlFfM3BVNmXZXjhjTFk4eFZZa2wyd2J2dmdPdDZRY190aFM1MURTT3RKc1hQaWtHWDN1aC1Idmk8PXhoN1lYS1ZncTlCckV6WURJaWxMeTBNZzR1NWYzM2E0LXk3VnhVbVJ3V2haRXhiR1c0VW5ldjJkRDRrM3V5ZUVxWSIsImlzc3VlZF9hdCI6MTM2NzcxODY5MiwidXNlcl9pZCI6IjUzMzM5MDYzNiJ9');

$exporter = new RottenTomatoesExporter($session_id, $fb);
$exporter->export();
$results = $exporter->get_results();

// Save in the format you want
```

3. Done! You should have a json file generated for which the filename is your user id on Rotten Tomatoes.

Results format
------------------

The get_results() output format is pretty simple and minimalist. It is an array of arrays, each containing the title of the movie and the rating you gave it.

```php
<?php
[
  [
    "title" => "The Dark Knight",
    "rating" => "5.0"
  ],
  [
    "title" => "The Matrix",
    "rating" => "5.0"
  ]
]
```

License
-------

The code is licensed under the MIT license (http://opensource.org/licenses/MIT). See license.txt.