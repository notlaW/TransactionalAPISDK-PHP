<?php
namespace SixCRM\Form;

use GuzzleHttp\Client as GuzzleClient;

class Helper {

    /**
      * Parses a customer from a form input array
      */
    public function parseCustomer($formInput = array()) 
    {

        $firstName  = isset($formInput['firstName']) ? $formInput['firstName'] : null;
        $lastName   = isset($formInput['lastName']) ? $formInput['lastName'] : null;
        $address1   = isset($formInput['shipAddress1']) ? $formInput['shipAddress1'] : null;
        $city       = isset($formInput['shipCity']) ? $formInput['shipCity'] : null;
        $zip        = isset($formInput['shipPostalCode']) ? $formInput['shipPostalCode'] : null;
        $state      = isset($formInput['shipState']) ? $formInput['shipState'] : null;
        $phone      = isset($formInput['phoneNumber']) ? $formInput['phoneNumber'] : null;
        $email      = isset($formInput['emailAddress']) ? $formInput['emailAddress'] : null;

        if (    null !== $firstName &&
                null !== $lastName &&
                null !== $address1 &&
                null !== $city &&
                null !== $zip &&
                null !== $state &&
                null !== $phone &&
                null !== $email)
        {

            $customer = array(
                'customer' => array(
                    'firstname' => $firstName,
                    'lastname' => $lastName,
                    'email' => $email,
                    'phone' => $phone,
                    'billing' => array(
                        'line1' => $address1,
                        'city' => $city,
                        'state' => $state,
                        'zip' => $zip,
                        'country' => 'US'
                    ),
                    'address' => array(
                        'line1' => $address1,
                        'city' => $city,
                        'state' => $state,
                        'zip' => $zip,
                        'country' => 'US'
                    )
                )
            );

            $_SESSION['customer'] = $customer;
            return $customer;
        }

        return null;
    }

    public function parseCreditCard($formInput = array())
    {

        $ccNumber           = isset($formInput['cardNumber']) ? $formInput['cardNumber'] : null;
        $ccv                = isset($formInput['cardSecurityCode']) ? $formInput['cardSecurityCode'] : null;
        $expirationMonth    = isset($formInput['cardMonth']) ? (string) $formInput['cardMonth'] : null;
        $expirationYear     = isset($formInput['cardYear']) ? (string) $formInput['cardYear'] : null;

        if (    null !== $ccNumber &&
                null !== $ccv &&
                null !== $expirationMonth &&
                null !== $expirationYear
        ) {

            $creditcard = array(
                'number'        => $ccNumber,
                'expiration'    => $expirationMonth . $expirationYear,
                'ccv'           => $ccv,
            );

            $_SESSION['creditcard'] = $creditcard;
            return $creditcard;
        }

        return null;
    }
}
