<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
| File ini adalah bootstrap utama Pest PHP. Semua test di folder Feature
| otomatis menggunakan TestCase bawaan Laravel (termasuk helper seperti
| $this->getJson(), $this->withToken(), dsb.)
*/

uses(Tests\TestCase::class)->in('Feature');
uses(Tests\TestCase::class)->in('Unit');
