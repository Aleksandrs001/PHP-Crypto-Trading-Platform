# PHP-Based Cryptocurrency Trading Platform with API Integration

## Author
- [Niks Kuprēvičs](https://github.com/NiksKphp)

## Description
This platform allows users to buy and sell various cryptocurrencies with real-time pricing information and actual market data
provided by the coinmarketcap API. The website is designed using Tailwind CSS and features user profiles and
the ability to transfer cryptocurrencies between accounts. The project is built using PHP and utilizes Twig templates
and PHP-DI for a secure and efficient user experience. The goal of this project was to study how to provide a convenient and user-friendly platform for trading cryptocurrencies 
and set up the required backend PHP code

## Features

- Real-time pricing information from the coinmarketcap API
- User profiles with the ability to transfer cryptocurrencies between accounts
- Options to buy, sell, and short crypto stocks
- Transaction history and profit/loss tracking
- Tailwind CSS-based design

## Prerequisites

- PHP 7.4 or higher
- MySQL 8.0.31
- Composer

## Installation

1. Clone this repository to your local machine 
`git clone https://github.com/NiksKphp/Homework-Crypto-page.git`
2. Navigate to the project directory and install the project dependencies using Composer:
`composer install`
3. Import the databse.sql
`mysql -u myuser -p mydatabase < database.sql`
5. Configure .env_example and rename the file to .env

## Preview

### Buying and Selling Crypto Stocks
![screenshot-3.png](https://github.com/NiksKphp/Screenshots/blob/main/crypto_buysell.gif)

### Shorting a Crypto Stock
![screenshot-3.png](https://github.com/NiksKphp/Screenshots/blob/main/crypto_short.gif)

### Sending Crypto Stock to Another Account
![screenshot-3.png](https://github.com/NiksKphp/Screenshots/blob/main/crypto_send.gif)

### Login and Registration Forms
![screenshot-3.png](https://github.com/NiksKphp/Screenshots/blob/main/crypto_register.gif)

### Transactions Table
![screenshot-3.png](https://github.com/NiksKphp/Screenshots/blob/main/23_12_4.png)
