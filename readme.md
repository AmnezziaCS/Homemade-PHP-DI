# Homemade PHP Dependency injection

> Fully working PHP DI made by me.

## Setup 🚀

### Create class constructors

You will have to make a constructor for each class that goes through the DI featuring it's dependencies such as :

```php
class Logger 
{
    public function __construct(
    public readonly Log $log
    )
    {}

    public function example() 
    {
        $logText = $this->log->getText();
    }
} 
```

### Replace the instanciated variables

As shown in the code sample above, you will need to change the way your variables are called as they are now stored locally.

```php
// Before

public function example() 
{
    $log = new Log();
    $logText = $log->getText();
}

// After

public function example() 
{
    $logText = $this->log->getText();
}
```

## Call your class using the DI

If your class and it's dependencies are meeting the requirements shown in the setup part, you will be able to call it using the DI :

```php
$DI = new DI();
$logger = new Logger(...$DI->getConstructArgs(Logger));
```

If one of the classes contains special cases that cannot be instanciated by the DI you will have to fill in the `$specialDependenciesArray` as well as the `setSpecialValue` function and specify what you want in return when meeting these special cases, example :

```php
private array $nonClassDependenciesArray = [
    'normalizers',
    'encoders'
];

public function setSpecialValue(string $varName): mixed {
    switch($varName) {
        case('normalizers'):
            return [new DateTimeNormalizer(), new ObjectNormalizer()];
        case('encoders'):
            return [new JsonEncoder()];
    }
}
```
