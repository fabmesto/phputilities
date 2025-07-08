# PHPUtilities

[![CI](https://github.com/fabmesto/phputilities/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/fabmesto/phputilities/actions)
[![Latest Release](https://img.shields.io/github/v/release/fabmesto/phputilities)](https://github.com/fabmesto/phputilities/releases)
[![Packagist](https://img.shields.io/packagist/v/fabmesto/phputilities)](https://packagist.org/packages/fabmesto/phputilities)
[![License](https://img.shields.io/github/license/fabmesto/phputilities)](LICENSE)

**PHPUtilities** è una collezione di funzioni utili per lo sviluppo PHP quotidiano: date, array, CSV, querystring e altro ancora.

---

## 🚀 Installazione

Con Composer:

```bash
composer require fabmesto/phputilities
```

---

## ✨ Funzionalità

### 📆 Date

- `date_to_mysql($itDate)`
- `date_to_it($mysqlDate)`
- `invert_date($date)`
- `invert_date_zero($date)`
- `is_zero_date($date)`
- `now()` → `2025-07-08 12:34:56`

### 🧩 Array & String

- `arraymulti_to_keys_values($array, $keyField, $valueField)`
- `split_comune_provincia("Bari (BA)")` → `['comune' => 'Bari', 'provincia' => 'BA']`
- `value_by_key($array, $key)`

### 📄 File & CSV

- `csv_to_array($filepath)` → parsing CSV

### 🌐 Query e parametri

- `params_from_get($defaults)`
- `params_from_post($defaults)`
- `get_in_query_string($excludeKeys)`

---

## 🧪 Esempi

```php
use fab\functions;

// Estrarre key => value da array multidimensionale
$data = [
    ['id' => 1, 'label' => 'Uno'],
    ['id' => 2, 'label' => 'Due'],
];

$res = functions::arraymulti_to_keys_values($data, 'id', 'label');
// Risultato: [1 => 'Uno', 2 => 'Due']
```

---

## 🧪 Testing

Esegui PHPUnit:

```bash
composer test
```

Il pacchetto è testato con PHPUnit 10.

---

## 🔄 Integrazione CI

La libreria è testata automaticamente tramite GitHub Actions:

- su `push` e `pull_request` su `main`
- su PHP 8.2

File CI: `.github/workflows/ci.yml`

---

## 📦 Pubblicazione Packagist

https://packagist.org/packages/fabmesto/phputilities
