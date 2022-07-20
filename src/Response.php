<?php
/*
 * This file is part of HNova/api.
 *
 * (c) Heiler Nova <https://github.com/heilernova>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HNova\Rest;

use JsonSchema\Constraints\TypeConstraint;
use SplFileInfo;

class Response
{
    /**
     * @var 'json'|'file'|'html'|'text' $type
     */
    private string $type = "json";
    private mixed $body;
    private int $status = 200;
    /**
     * @param 'json'|'file'|'html'|'text' $type
     */
    public function __construct(string $type, mixed $body = null, int $status = 200)
    {
        $this->type = $type;
        $this->status = $status;
        $this->body = $body;
    }

    public function status(int $status):Response{
        $this->status = $status;
        return $this;
    }

    /**
     * Print the response
     */
    public function send():void {
        $body = "";
        $conten_type = "Content-Type: text/html";
        
        switch ($this->type) {
            case 'json':
                $body = json_encode($this->body);
                $conten_type = "Content-Type: application/json";
                break;
            
            case 'file':
                $body = file_get_contents($this->body);

                if (!$body){
                    $this->status = 404;
                }
                $file = new SplFileInfo($this->body);
                $conten_type = "Content-Type: " . HttpFuns::getContentType($file->getExtension());
                break;

            case 'text':
                $conten_type = "Content-Type: text";
                $body = $this->body;
            default:
                # code...
                break;
        }

        header($conten_type);
        echo $body;
        $_ENV['api-rest-res']['status'] = $this->status;
        http_response_code($this->status);
        log::request();
    }
}