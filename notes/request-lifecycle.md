1. When the user submits login or register request the webserver sends the request to index.php.
Then the index.php file loads the composer autoloader loads all the classes and creates the instance of our application.
2. Next the request goes to HTTP kernel. The middleware inspects the csrf in blade views. If malicious entry is done and  a user donot pass credentials then the lifecycle breaks and shows error message.
3.  After that the request goes to  routes/web.php and it sees which URL matches the request and sends it to the controller.
4. In the next step it doesnot go to controller.It goes to request and if the user is authorized the request gets continues.
5. Then based on the route it executes the login or register and then gives the response back.
