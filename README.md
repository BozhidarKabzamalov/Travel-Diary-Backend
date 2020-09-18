# Travel Diary

Single-page application which allows its users to pinpoint cities on a Google map, and in return, receive a list of recommended places, vistas and activities to check out in the selected city.

Utilizes Google Maps API, HTML, CSS, JavaScript, Vue.js, Ajax, PHP, Laravel, Eloquent ORM and MySQL.

![1](https://user-images.githubusercontent.com/16446652/93605377-56524480-f9cf-11ea-9f8d-627084b18559.png)

The application consists of a Vue.js front-end and PHP (Laravel) back-end which are separated from each other. Separating the front-end from the back-end has many advantages such as:

* Readability
* Flexibility
* Scalability
* Easy maintenance
* Modularity
* Easier deployment

**Front-end responsibilities:**

* Make requests to the back-end for information about pinpointed places of interest
* Display all the information in an understandable manner
* Handle routing

**Back-end responsibilities:**

* Make requests to Google Maps API and then return the responses to the front-end
* Store the API responses inside the database
* Deal with user authentication and user roles
* Obfuscate Google Maps API key

![2](https://user-images.githubusercontent.com/16446652/93605416-64a06080-f9cf-11ea-8255-e0fdd0fc1add.png)

![3](https://user-images.githubusercontent.com/16446652/93605516-84d01f80-f9cf-11ea-83d4-4e298a0a657b.png)
