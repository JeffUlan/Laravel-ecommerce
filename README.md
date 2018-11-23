&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;![Bagisto Logo](https://bagisto.com/wp-content/themes/bagisto/images/logo.png)


1. #### Introduction
2. #### Requirements
3. #### Installation
4. #### Configuration


# 1. About Bagisto:

[Bagisto](https://www.bagisto.com) is a hand tailored E-Commerce framework designed on some of the hottest opensource technologies
such as [Laravel](https://laravel.com) a [PHP](https://secure.php.net/) framework, [Vue.js](https://vuejs.org)
a [Node.js](https://nodejs.org) framework.

**Bagisto is viable attempt to cut down your time, cost and workforce for building online stores or migrating from physical stores
to the ever demanding online world. Your business whether small or huge it suits all and very simple to set it up.**

It packs in lots of demanding features that allows your business to scale in no time:

* Multiple Channels, Locale, Currencies.
* Built-in Access Control Layer.
* Beautiful and Responsive Storefront.
* Descriptive and Simple Admin Panel.
* Admin Dashboard.
* Custom Attributes.
* Built on Modular Approach.
* Support for Multiple Store Themes.
* Multistore Inventory System.
* Orders Management System.
* Customer Cart, Wishlist, Product Reviews.
* Simple and Configurable Products.
* check out [more....](https://bagisto.com/features/).

**For Developers**:
Dev guys can take advantage of two of the hottest frameworks used in this project Laravel and Vue.js, both of these frameworks have been used in Bagisto.
Bagisto is using power of both of these frameworks and making best out of it out of the box.


# 2. Requirements:

* **OS**: Ubuntu 16.04 LTS or higher.
* **SERVER**: Apache 2 or NGINX
* **RAM**: 2 GB or higer.
* **PHP**: 7.1.17 or higher.
* **Processor**: Clock Cycle 1Ghz or higher.
* **Mysql**: 5.7.23 or higher.
* **Node**: 8.11.3 LTS or higher.
* **Composer**: 1.6.5 or higher.

# 3. Configuration:

**Run this Command** to download the project on to your local machine or server:
~~~
composer create-project bagisto/bagisto
~~~

if the above command's process was successful, you will find directory **bagisto** and all of the code will be inside it.

After it set your **.env** variable, especially the ones below:
* **APP_URL**
* **DB_CONNECTION**
* **DB_HOST**
* **DB_PORT**
* **DB_DATABASE**
* **DB_USERNAME**
* **DB_PASSWORD**

Although you have to set the mailer variables also for full functioning of your store for sending emails at various events by
default.


# 4.Installation:

**1. Run the Command**
~~~
php artisan migrate
~~~

**2. Run the Command**
~~~
php artisan db:seed
~~~

**3. Run the Command**
~~~
php artisan vendor:publish

-> Press 0 and then press enter to publish all assets and configurations.
~~~

**4. Run the Command**
```
php artisan storage:link
```

> That's it, now just execute the project on your specified domain entry point pointing to public folder inside installation directory.



# License
Bagisto is a truly opensource E-Commerce framework which will always be free under the [MIT License](https://opensource.org/licenses/MIT).
