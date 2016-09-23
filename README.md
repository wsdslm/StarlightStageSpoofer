# Starlight Stage Spoofer
Web-based, proof-of-concept spoofing application for *THE iDOLM@STER Cinderella Girls: Starlight Stage* (*Deresute*)
![Screenshot](http://frizz925.github.io/assets/img/StarlightStageSpoofer/Spoofer.png)

## Introduction

### Background
This is a proof-of-concept application to spoof *Deresute* game by intercepting and modifying HTTP communication between the game client and game server (located at [game.starlight-stage.jp](http://game.starlight-stage.jp)). The prototype was first made back in February 2016. It then improved into a usable, friendly application which still works until now.

### How it works
*tl;dr version:* This does not hack the game server. It only *spoof* the data from the server to you and thus you see something that is not actually there, such as **your waifu SSR**.

<p align="center">
    <img src="http://frizz925.github.io/assets/img/StarlightStageSpoofer/Spoofer-1.svg" alt="Diagram">
</p>

*Deresute* does not use encrypted transport protocol such as HTTPS and instead uses plain HTTP. This means that the game is open to the exploit of [man-in-the-middle attack](https://en.wikipedia.org/wiki/Man-in-the-middle_attack).

<p align="center">
    <img src="http://frizz925.github.io/assets/img/StarlightStageSpoofer/Spoofer-1-1.svg" alt="Diagram">
</p>

By deploying a spoofing server and make the game to communicate with that server instead (eg. DNS spoofing), the spoofing server can then modify the packets before being sent either to the *real* game server or back to the client itself. This way the spoofing server acts as a *middle-man*.

However the devs seem to know this and uses their own encryption method to encrypt and decrypt the packet sent between the client and the server.

<p align="center">
    <img src="http://frizz925.github.io/assets/img/StarlightStageSpoofer/Spoofer-2.svg" alt="Diagram">
</p>

Before sending a packet through a network, the game first packs the data using [Msgpack](http://msgpack.org), encrypts them using AES-256 algorithm, and then base64 encodes them so it fits as a regular HTTP request body. What you'll get is a garbled text that you can't do anything with it. Trying to tamper with it will corrupt the data and causes the server to throw errors to the game client.

This method works quite well but they left **a lot of holes to exploit**.

<p align="center">
    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/80/CBC_encryption.svg/601px-CBC_encryption.svg.png" alt="Encryption diagram">
</p>

Decompiling the game APK lets you to reverse engineer the encryption and decryption method of the game. One example of exploit is how the key used to encrypt and decrypt the data is embedded inside the **request body** while the IV based on unique ID of each client is embedded inside the **request header**. Again, they know that putting both the IV and the key out in the open is a bad idea and thus encrypts the IV with a slightly different method.

This again, however, **still has a hole to exploit** and this one is **very critical**.

The game is based on Unity engine. By decompiling the APK you'll find *Assembly-CSharp.dll* somewhere which is a common file for Unity-based games. Opening the file using .NET decompiler such as [dotPeek](https://www.jetbrains.com/decompiler/) will let you explore the code for the game. One code that seriously causes the whole exploit is this.

<p align="center">
    <img src="http://frizz925.github.io/assets/img/StarlightStageSpoofer/Spoofer-3.png" alt="Stupid code">
</p>

**They hardcoded the key to encrypt various things** including encrypting the IV string before being embedded in the request header. Using this key lets attacker to decrypt **everything** and even encrypt them back like nothing happened.

### Technology used
The application consists of three separate applications: 
* Backend: act as a spoofer made using Laravel 5.2
* Frontend: as the dashboard to interact with the spoofer made using React
* Scraper: scrape card data from [starlight.kirara.ca](https://starlight.kirara.ca/) to the spoofer's database using Node.js

### What's working
The initial prototype first features the ability to change the card of a gacha. So instead of getting the ever lame, not so wanted R card, you can set the spoofer to change it to an **SSR card**. You can spoof anything if you know how to do it but what's included in this software mostly consists of spoofing the gacha and the card itself.

You can use the spoofed card like a real one on MV or live but I don't recommend using it to play on events since **it can actually get you b&** as the submitted score would be based on the performance of the spoofed card, not the actual card.

## Requirements
* Apache 2
* PHP 5.6
* Composer
* Node.js v4.5.0
* NPM

## Installation
Clone the repository using git.
```
git clone https://github.com/Frizz925/StarlightStageSpoofer.git
cd StarlightStageSpoofer
```

### Backend
First make sure you have an empty database to be used by the spoofer. MySQL database recommended.
Make sure that you have enabled *mod_rewrite* module and configure Apache's *document_root* to the root directory of the project.
Failing to do these will make the spoofer not working as expected.

Navigate to the backend directory and install the dependencies.
```
cd local/laravel
composer install
```

Create the *.env* file by copying *.env.example* file. Configure the environment variables to your need.
```
cp .env.example .env
```

Run migration to create the required tables into the database.
```
php artisan migrate
```

### Frontend
Install gulp globally if you haven't.
```
npm install -g gulp
```

Navigate to the frontend directory and install the dependencies.
```
cd local/app
npm install
```

Build the bundled, production-ready file 
```
gulp
```

### Scraper
In order to use the frontend dashboard, the scraper must be run at least once.

Navigate to the scraper directory and install the dependencies.
```
cd local/scraper
npm install
```

Run the scraper using node. It will fetch the data into the database.
```
node index.js
```

## How to use
The game client needs to communicate with the spoofer server instead of the real game server. To do this, you need to change the DNS on your Android phone and the spoofer server itself by editing the */etc/hosts* file on both devices. Your phone may need to be rooted to do so or you can configure the DNS on your home router.

Here are the domain names used by the spoofer.
* game.starlight-stage.jp - The spoofer itself
* real-game.starlight-stage.jp - The real game server
* storage.game.starlight-stage.jp - The real game's storage server

Here's an example of a working hosts file.
```
192.168.1.2         game.starlight-stage.jp
203.104.249.195     real-game.starlight-stage.jp
115.124.93.145      storage.game.starlight-stage.jp
```

Note that in the example *192.168.1.2* is the spoofer server IP which is hosted on a local network.

Reboot your phone and open the game. You need to write down your ID on the splash screen if you haven't since this will be needed later. Continue until you get to the room screen.

Next, open the spoofer from your web browser and navigate to the register page. Enter the ID from the splash screen in to the *User ID* field. Name and email can be filled to your liking as the email does not have to be confirmed. Fill in the password as well.

<p align="center">
    <img src="http://frizz925.github.io/assets/img/StarlightStageSpoofer/Spoofer-4.png" alt="Register page">
</p>

You should be taken to the dashboard page where you can tinker with the included spoofing features. Remember that you may need to restart your game after changing settings.
