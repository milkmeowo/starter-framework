<?php

namespace Milkmeowo\Framework\Base\Validators;

use Prettus\Validator\AbstractValidator;

class Validator extends AbstractValidator
{
    /**
     * Validation Custom Messages.
     *
     * @var array
     */
    protected $messages = [];

    /**
     * Validation Custom Attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Get Custom error messages for validation.
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Set Custom error messages for Validation.
     * @param array $messages
     *
     * @return $this
     */
    public function setMessages(array $messages)
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * Get Custom error attributes for validation.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set Custom error attributes for Validation.
     *
     * @param array $attributes
     *
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Pass the data and the rules to the validator.
     *
     * @param string $action
     * @return bool
     */
    public function passes($action = null)
    {
        $rules = $this->getRules($action);
        $messages = $this->getMessages();
        $attributes = $this->getAttributes();
        $validator = app('validator')->make($this->data, $rules, $messages, $attributes);
        if ($validator->fails()) {
            $this->errors = $validator->messages();

            return false;
        }

        return true;
    }
}
