<?php
/*
 * This file is part of HNova/api.
 *
 * (c) Heiler Nova <https://github.com/heilernova>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace HNova\Rest\Http;

class FormDataFile
{
    public function __construct(
        public string $name,
        public string $type,
        public string $fullName,
        public string $tmpName,
        public $error,
        public $size
    ){ }

    public function getExtension():string{
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }
}