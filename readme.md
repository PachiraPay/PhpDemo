## Change History

| Version |    Date    | Changes |
| ------- | :--------: | ------: |
| 1.0.1   | 30/01/2020 |         |

## PHP connector - Demo page

## 1. Requirements

- PHP 5.5 and later: https://www.php.net/
- Composer: http://getcomposer.org/
- Nodejs 10 and later: https://nodejs.org/en/

## 2. Installation

- Clone the projet from [Php connector demo](http://tfs.cdbdx.biz:8080/tfs/DefaultCollection/PaymentSAAS/_git/connecteur-php-Symfony) into your apache/php enabled folder. (eg. wamp/www)

- In the projet root folder, open a terminal and type:

```
npm install
```

```
composer install
```

```
npm run build
```

## 3. Launch the Demo page

Open your browser:

```
localhost/<path-to-this-project-root>/public
```

eg: localhost/pachirapayPhpConnector/connecteur-php-Symfony/public/CardPayment

## 4. Miscellaneous

### 4.1 Error : Curl missing certificate

If you encounter this error: Curl error 60, SSL certificate issue: self signed certificate in certificate chain

Download this [cacert.pem](https://curl.haxx.se/docs/caextract.html) certificate

Uncomment and change this line in your php.ini:

```
curl.cainfo = <absolute_path_to> cacert.pem
```

Restart apache/php services

### 4.2 Token

Before calling any services, you need to generate a valid token.
When you buy our service, you will get an user and password to generate this token.
It will expire in 48h so you need to check or renew it before calling any service.

- Follow the instructions onscreen
