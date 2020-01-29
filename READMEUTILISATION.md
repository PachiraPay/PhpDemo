## Change History

| Version |    Date    | Changes |
| ------- | :--------: | ------: |
| 1.0.0   | 27/09/2019 |         |

## 1. Overview

## 1. Requirements

- You need to install PHP 5.5 and later

## 2. Installation Details

### Composer

To install the bindings via [Composer](http://getcomposer.org/), add the following to `composer.json`:

```json
{
  "type": "project",
  "license": "proprietary",
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/pachirapay/phpconnector"
    }
  ],
  "require": {
    "php": "^7.1.3",
    "ext-ctype": "*",
    "ext-iconv": "*",
    "pachirapay/connector": "dev-master",
    "doctrine/annotations": "^1.8",
    "sensio/framework-extra-bundle": "^5.5",
    "symfony/apache-pack": "^1.0",
    "symfony/console": "4.3.*",
    "symfony/dotenv": "4.3.*",
    "symfony/expression-language": "4.3.*",
    "symfony/flex": "^1.3.1",
    "symfony/form": "4.3.*",
    "symfony/framework-bundle": "4.3.*",
    "symfony/http-client": "4.3.*",
    "symfony/intl": "4.3.*",
    "symfony/monolog-bridge": "4.3.*",
    "symfony/monolog-bundle": "^3.4",
    "symfony/orm-pack": "*",
    "symfony/process": "4.3.*",
    "symfony/security-bundle": "4.3.*",
    "symfony/serializer-pack": "*",
    "symfony/swiftmailer-bundle": "^3.1",
    "symfony/translation": "4.3.*",
    "symfony/twig-bundle": "4.3.*",
    "symfony/twig-pack": "^1.0",
    "symfony/validator": "4.3.*",
    "symfony/web-link": "4.3.*",
    "symfony/webpack-encore-bundle": "^1.7",
    "symfony/yaml": "4.3.*"
  },
  "require-dev": {
    "symfony/debug-pack": "*",
    "symfony/maker-bundle": "^1.0",
    "symfony/profiler-pack": "*",
    "symfony/test-pack": "*",
    "symfony/web-server-bundle": "4.3.*"
  },
  "config": {
    "preferred-install": {
      "*": "dist"
    },
    "sort-packages": true
  },
  "autoload": {
    "psr-4": {
      "App\\": "src/"
    }
  },
  "autoload-dev": {
    "psr-4": {
      "App\\Tests\\": "tests/"
    }
  },
  "replace": {
    "paragonie/random_compat": "2.*",
    "symfony/polyfill-ctype": "*",
    "symfony/polyfill-iconv": "*",
    "symfony/polyfill-php71": "*",
    "symfony/polyfill-php70": "*",
    "symfony/polyfill-php56": "*"
  },
  "scripts": {
    "auto-scripts": {
      "cache:clear": "symfony-cmd",
      "assets:install %PUBLIC_DIR%": "symfony-cmd"
    },
    "post-install-cmd": ["@auto-scripts"],
    "post-update-cmd": ["@auto-scripts"]
  },
  "conflict": {
    "symfony/symfony": "*"
  },
  "extra": {
    "symfony": {
      "allow-contrib": false,
      "require": "4.3.*"
    }
  }
}
```

Then run `composer install`

### Manual Installation

Download the files and include `autoload.php`:

```php
    require_once('/path/to/pachirapay.Connector/vendor/autoload.php');
```

## 3. Token

Before calling any services, we need to generate a valid token it will be expire in 48h so you need to check or renew it before calling in other service
When you you buy our service, you will get an user and password to get a token
before calling it, you need to encode it in Base64 and pass it in parameters of the function

### 3.1 Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


$apiInstance = new pachirapay\Api\SecurityTokenApi(

    new GuzzleHttp\Client()
);
$authorization = 'dXNlcjpwYXNzd29yZA==';

try {
    $result = $apiInstance->v1AuthTokenGet($authorization);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling SecurityTokenApi->v1AuthTokenGet: ', $e->getMessage(), PHP_EOL;
}
?>
```

If there everything was good you will receive a valid GUID token : _f40dd89dff8246d285115f6f45ca05d4_ Otherwise you will get an error with the message that explain what happen

### 3.2 Response

The function _v1AuthTokenGet_ return result as a string with response code.
Details:

1. ResponseCode equal to 200 mean Success and you will get the token its 32 character
2. BadRequest equal to 400 mean that you forget to put the authentification in parameter or your parameter is not a valid base64
3. Forbidden equal to 403 your user or password is incorrect
4. ServerError 500 Something wrong was happend in the server side

## 4. Playing with SDK

### 4.1 Classes

List of classes that is included in the SDK

1. Card3DsPaymentApi --> Credit a card payment
2. CardApi --> Achieve a 3DS payment
3. CardPaymentApi : Achieve a payment with a card without 3DSecure
4. DuplicatePaymentApi --> Achieve a payment by copy of a previously processed payment
5. PaymentOptionsApi --> Gets the payment options
6. PaymentsApi --> details for a specified Order
7. PaymentSessionApi --> Payment session
8. SecurityTokenApi --> Get the token provided by the Security Token Service (STS)
9. StoredPaymentMethodsApi --> Sored payment method

### 4.2 Example

### 4.2.1 Payment Options

To get all payments methods supported you can call the function _v1PaymentOptionsMerchantsByMerchantIdSitesByMerchantSiteIdGet()_ in _PaymentOptionsApi_.

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');


$apiInstance = new pachirapay\Api\PaymentOptionsApi(

    new GuzzleHttp\Client()
);
$merchant_id = 1; // int | The merchant identifier.
$merchant_site_id = '100'; // string | The merchant site identifier.
$auth_token = 'f40dd89dff8246d285115f6f45ca05d4'; // string | Gets or sets the authentication token.

try {
    $result = $apiInstance->v1PaymentOptionsMerchantsByMerchantIdSitesByMerchantSiteIdGet($merchant_id, $merchant_site_id, $auth_token);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling PaymentOptionsApi->v1PaymentOptionsMerchantsByMerchantIdSitesByMerchantSiteIdGet: ', $e->getMessage(), PHP_EOL;
}

$cardPaymentApiInstance = new OpenAPI\Client\Api\CardPaymentApi(
        // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
        // This is optional, `GuzzleHttp\Client` will be used as default.
        new GuzzleHttp\Client()
    );

     try {
       $result = $cardPaymentApiInstance->v1PaymentsCardPaymentPost($auth_token, $card_payment_request);
       print_r($result);
    } catch (Exception $e)
          echo 'Exception when calling CardPaymentApi->v1PaymentsCardPaymentPost:', $e->getMessage(), PHP_EOL;
    }
?>
```

### 4.2.2 Achieve a payment with a card

To achieve a payment with a credit card we will use the function _v1PaymentsCardPaymentPost_ in class _CardPaymentApi_ and we need to pass a _CardPaymentRequest_ object to this function

as example we will use this object to simulate a payment with a card and this is the minimum of what you need to achieve this

```
{
  "context": {
    "merchantId": 1,
    "merchantSiteId": "100",
    "currency": "eur",
    "country": "fr",
    "paymentOptionRef": "1",
    "customerRef": "CUSTOMER01",
    "paymentAttempt": 1
  },
  "options": {},
  "order": {
    "orderRef": "ORDERREF_12345XX",
    "invoiceId": 12345,
    "orderTag": "",
    "orderDate": "2019-09-16",
    "amount": 1337
  },
  "card": {
    "cardOptionId": 1,
    "cardScheme": "cb",
    "expirationDate": "2020-09-16",
    "cardNumber": "5017670000001800",
    "securityNumber": "123",
    "cardLabel": "John Doe"
  },
  "validationMode": null
}
```

_Note_ that orderRef need to be unique otherwise you will get an error that tell you this orderRef was already saved.
notificationUrl if you want to send a notification result to an other url.
Let's code

```php
<?php
    $card_payment_request = new pachirapay\Model\CardPaymentRequest(); // pachirapay\Model\CardPaymentRequest | All data needed to make card payment

    $context = new pachirapay\Model\CardPaymentContextData();
    $context->setMerchantId(1);
    $context->setMerchantSiteId("100");
    $context->setCurrency("eur");
    $context->setCountry("fr");
    $context->setPaymentOptionRef("1");
    $context->setCustomerRef("CUSTOMER01");
    $context->setPaymentAttempt(1);

    $option = new pachirapay\Model\Options();

    $order = new pachirapay\Model\Order();
    $order->setOrderRef('Test_201910211514');
    $order->setInvoiceId(12345);
    $order->setOrderDate('2019/10/21');
    $order->setAmount('150');

    $CardData = new pachirapay\Model\CardData();
    $CardData->setCardScheme("cb");
    $CardData->setExpirationDate('12/23');
    $CardData->setCardNumber('5017670000001800');
    $CardData->setSecurityNumber('904');
    $CardData->setCardLabel('Jon doe');

    $validationMode = new pachirapay\Model\ValidationModeOverride();
    $validationMode->setValidationMode("manual");

    $card_payment_request->setContext($context);
    $card_payment_request->setOptions($option);
    $card_payment_request->setOrder($order);
    $card_payment_request->setCard($CardData);
    $card_payment_request->setValidationMode($validationMode);

?>
```

if everything was ok you will have a Response code Successed and response message null

example of success response

```
{
    "merchantAccountRef": "CBCE@PAYLINE",
    "responseCode": "succeeded",
    "complementaryResponseCode": "authorized",
    "schedules": [
        {
            "amount": 150,
            "date": "2019-09-17T16:01:00.6945965+02:00",
            "rank": 1
        }
    ],
    "storedCard": null,
    "responseMessage": null
}
```

if something was wrong you will receive an object with the description of it, in this example we will simulate a no valid token and the response will be

```
{
    "merchantAccountRef": null,
    "responseCode": "unknown",
    "complementaryResponseCode": "unknown",
    "schedules": [],
    "storedCard": null,
    "responseMessage": "Token validation error"
}
```

in this case we need to renew our token before calling the function.

### 4.2.3 Achieve a payment with a card using 3D secure.

The payment 3D secure is done in three steps.

#### Step 1

Using the POST methode _v1PaymentsCard3dsPaymentPost_ in class _Card3DsPaymentApi_ and we need to pass a _CardPaymentRequest_ object to this function _Card3DsPaymentRequest_

As example we will use this object to simulate a payment with a card 3D Secure

```
{
  "returnUrl": "http://merchant.com/return",
  "context": {
    "merchantId": 33,
    "merchantSiteId": "99002",
    "currency": "eur",
    "country": "fr",
    "paymentOptionRef": "21",
    "customerRef": "CUSTOMER10",
    "paymentAttempt": 1
  },
  "order": {
    "orderRef": "OrderRef_Test",
    "invoiceId": 12345,
    "orderDate": "2019-10-07T15:23:42.9722198+02:00",
    "amount": 4337
  },
  "card": {
    "cardScheme": "cb",
    "expirationDate": "2020-12-09",
    "cardNumber": "5017670000001800",
    "securityNumber": "123",
    "cardLabel": "John Doe"
  }
}

```

Remember to take note of the merchantId, merchantSiteId and orderRef you are sending, as well as the paymentRequestId you receive. You will need them again in step 3.

Also please take note of the parameter values you receive in the response, as you will need them in step 2.

If everything was ok you will have a Response code Successed and response message null

Example of success response

```
{
    "card3dsPaymentRequestId": "c27106a2-183b-46b4-ad35-173634575074",
    "card3dsRedirectionData": {
        "redirectionUrl": "https://payment-web.test.sips-atos.com/paymentprovider/threeds/init?threedsKeyRequest=2f8c55fa-8d9d-49fd-a86f-ee1cfe931d81&pid=801e2a3d72234f228eb0cc9195576570",
        "params": {
            "PaReq": "2808abcb-1217-4590-82d7-16250d332b1c",
            "TermUrl": "https://paymentservices.recette-cb4x.fr/Return.ashx?requestID=c27106a2-183b-46b4-ad35-173634575074&hmac=D268877F5D231782C464AF48325279C229F4FA1C",
            "MD": ""
        },
        "redirectionType": "post"
    },
    "cardEnrollmentResponseCode": "enrolled"
}
```

#### Step 2

Then, you need to create and send a form for the user to validate the 3DS part of the card validation. We have provided you an example for the form (see below).

Simply replace the placeholder values for the parameters you received

example of form

```html
<html lang="en">
  <body>
    <form id="formulaire" method="post" action="card3dsRedirectionData.redirectionUrl">
      <input type="hidden" id="PaReq" name="parPaReqam" value="value2" />
      <input type="hidden" id="TermUrl" name="TermUrl" value="value3" />
      <input type="hidden" id="MD" name="MD" value="" />
    </form>
    <script>
      document.forms[0].submit();
    </script>
  </body>
</html>
```

#### Step 3

Finally, you can proceed with this PUT method to finish the payment. Replace the placeholder values of the merchantId, merchantSiteId and orderRef with the values you sent in the POST method, and the paymentRequestId with the value you received from said POST method.

Using the PUT methode _v1PaymentsCard3dsPaymentPut_ in class _Card3DsPaymentApi_ and we need to pass a _CardPaymentRequest_ object to this function _Card3DsPaymentPutRequest_

As example we will use this object to simulate a payment with a card 3D Secure

```
{
  "merchantId": 1,
  "merchantSiteId": "100",
  "paymentRequestId": "ed1337c28a614e02bd060c74803be9b8",
  "orderRef": "ORDER12345",
  "orderTag": "LABEL1"
}
```

If everything was ok you will have a Response code Successed.

Example of success response

```
{
  "merchantAccountRef": "string",
  "responseCode": "unknown",
  "complementaryResponseCode": "unknown",
  "schedules": [
    {
      "amount": 0,
      "date": "2019-12-04T15:30:32.023Z",
      "rank": 0
    }
  ],
  "storedCard": {
    "id": "string",
    "label": "string"
  },
  "responseMessage": "string"
}
```
