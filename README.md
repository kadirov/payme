# Payme

## Installation

### Install the package<br>

```bash
composer req kadirov/payme
```

### Add it to _config/bundles.php_<br>

```php
Kadirov\PaymeBundle::class => ['all' => true],
```

### Add next lines to your _.env_ file <br>

```dotenv
### Payme
PAYME_AFTER_FINISH_PAYMENT_CLASS=Your\Class\Namespace
PAYME_BEFORE_CANCEL_FINISHED_PAYMENT_CLASS=Your\Class\Namespace
PAYME_LOGIN=
PAYME_KEY=
PAYME_TEST_LOGIN=Paycom
PAYME_TEST_KEY=PnKoCEYTEEnf%6tPRuohVSvnqwg44iZdrju5
PAYME_IP1=195.158.31.134
PAYME_IP2=195.158.31.10
### Payme
```

### Add payme route to _config/packages/security.yaml_ file

```yaml
security:
    firewalls:
        payme:
            pattern: ^/api/payments/payme
            security: false
            methods:
                - post
```

## How to use

Implement [AfterFinishPaymentInterface](src/Component/Billing/Payment/Payme/Interfaces/AfterFinishPaymentInterface.php). 
Method in this class will call after payment. 

Also, you have to implement [BeforeCancelFinishedPaymentInterface](src/Component/Billing/Payment/Payme/Interfaces/BeforeCancelFinishedPaymentInterface.php). 
Method in this class will call before cancel a payment. If canceling payment is impossible you can throw 
[BeforeCancelFinishedPaymentException](src/Component/Billing/Payment/Payme/Exceptions/BeforeCancelFinishedPaymentException.php)

