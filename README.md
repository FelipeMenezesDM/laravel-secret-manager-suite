# Laravel Secret Manager Suite

## Descrição
Biblioteca para gerenciamento de secrets em ambiente cloud, compatível com Amazon Web Services e Google Cloud Platform.

## Instalação
Para instalar esta dependência, é necessário ter o Composer disponível em sua máquina. Baixe e instale o Composer a partir deste link: https://getcomposer.org/download/

Após ter instalado o composer, execute o seguinte comando para instalar a dependência no seu projeto Laravel:

```
composer require felipemenezesdm/laravel-secret-manager-suite
```

## Uso
Após a instalação desta dependência no laravel, o método global _suite()_ estará disponível e pode ser utilizado em qualquer parte do código. Abaixo, um exemplo de uso do suite em um arquivo de configuração do laravel:
```php
<?php

return [
    # ...
    'key1' => suite()->getSecretData("my-secret-1"),
    'key2' => suite()->getSecretData("my-secret-2"),
    # ...
];
```

## Configuração
Abaixo, todas as variáveis de ambiente disponíveis para a configuração da lib:

| Name                           | Valor padrão       | Example                                                                          |
|--------------------------------|--------------------|----------------------------------------------------------------------------------|
| APP_SUITE                      | gcp, aws ou vazio  | Definição do suite para recuperação de secrets                                   |
| AWS_ACCOUNT_ID                 | 000000000000       | Definir a ID da conta AWS para a aplicação                                       |
| AWS_ENDPOINT                   | http:\/\/127.0.0.1 | Definir o endpoint dos serviços AWS (indicado quando houver o uso do localstack) |
| AWS_DEFAULT_REGION             | us-east-1          | Definir a região padrão para uma aplicação alocada na AWS                        |
| GCP_PROJECT_ID                 | N/A                | ID do projeto no Google Cloud Plataform                                          |
| GOOGLE_APPLICATION_CREDENTIALS | N/A                | Arquivo de credenciais do Google Cloud Platform                                  |

## Links úteis

- [Laravel Secret Manager Suite on GitHub](https://github.com/FelipeMenezesDM/laravel-secret-manager-suite)
- [Laravel Secret Manager Suite on Packagist](https://packagist.org/packages/felipemenezesdm/laravel-secret-manager-suite)
