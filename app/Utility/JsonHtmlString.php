<?php

namespace App\Utility;

use Illuminate\Support\HtmlString;
use JsonSerializable;

class JsonHtmlString extends HtmlString implements JsonSerializable
{
    /**
     * Convert the object to a form that can be serialized to JSON.
     *
     * @return string
     */
    public function jsonSerialize(): mixed
    {
        return $this->toHtml();
    }
}
