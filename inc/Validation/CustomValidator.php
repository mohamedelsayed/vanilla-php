<?php

namespace Inc\Validation;

use Sirius\Validation\Validator;

class CustomValidator extends Validator
{
    public function getMessages($item = null): array
    {
        if (is_string($item)) {
            return parent::getMessages();
        }
        $messages = [];
        if ($this->messages) {
            foreach ($this->messages as $key => $value) {
                $messages[$key] = [];
                foreach ($this->messages[$key] as $valueIn) {
                    $messages[$key][] = $valueIn->__toString();
                }
            }
        }
        return $messages;
    }
}
