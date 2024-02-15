<?php

namespace SamuelPouzet\Api\Form;

use Laminas\Filter\StringTrim;
use Laminas\Filter\StripNewlines;
use Laminas\Filter\StripTags;
use Laminas\Form\Element\Password;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\Validator\StringLength;

class AuthForm extends Form
{

    public function __construct()
    {
        parent::__construct('login-form');
        $this->addElements();
        $this->addInputFilters();
    }

    protected function addElements(): void
    {
        $this->add([
            'type' => Text::class,
            'name' => 'login',
            'attributes' => [
                'id' => 'login'
            ],
            'options' => [
                'label' => 'Login',
            ],
        ]);

        $this->add([
            'type' => Password::class,
            'name' => 'password',
            'attributes' => [
                'id' => 'password'
            ],
            'options' => [
                'label' => 'password',
            ],
        ]);
    }

    protected function addInputFilters(): void
    {
        $inputFilter = $this->getInputFilter();

        $inputFilter->add([
                'name' => 'login',
                'required' => true,
                'filters' => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class],
                    ['name' => StripNewlines::class],
                ],
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'min' => 1,
                            'max' => 128
                        ],
                    ],
                ],
            ]
        );

        $inputFilter->add([
                'name' => 'password',
                'required' => true,
                'filters' => [
                    ['name' => StringTrim::class],
                    ['name' => StripTags::class],
                    ['name' => StripNewlines::class],
                ],
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'min' => 1,
                            'max' => 128
                        ],
                    ],
                ],
            ]
        );
    }

}