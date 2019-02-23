# Flooded Silhouettes Calculator #

This tool predicts total areas that would be flooded, based on a 2D silhouettes map, which is generated from an integer matrix. The matrx is a ".txt" input file. 

File's content format must follow this rules:

- First line: An integer that represents the quantity of cases to be analized.

- Secong line: A blank space or an intenger that represents the length of the matrix, in other words, the number of silhouttes of the case.

- Third line: A blank space or a sequence of integers separated by blank spaces. Each integer represents the height of the silhouette.

You can repeat lines 2 and 3 for each case you want to include in the calculation.



## Set Up Application ##

**> Requirements:**

- PHP 7.2 or newer

- Nginx web server service


**> Nginx Server Block Configuration:**

```cfg
server {
        listen 80;
        listen [::]:80;

        server_name test.local;

        root /var/www/html/test;
        index index.php;

        location / {
                try_files $uri $uri/ /index.php;
        }

        location ~ \.php$ {
                try_files $uri /index.php =404;
                fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
                fastcgi_index index.php;
                fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
                include fastcgi_params;
        }
        location ~ \.git {
                deny all;
        }
}
```
**PS:** Remenber to change **server_name**, **root** and the php's fpm to suit your needs.


**> Root directory:**

Put all contents of this application inside directory at the path indicated in server block's **root**.


**> Done!**

Access address specified at server block's **server_name** and you must see this screen:

![oxylogo.png](https://bitbucket.org/repo/p6xdM7/images/2318018827-oxylogo.png)


### Who am i? ###

My name is Gabriel Valentoni Guelfi. I'm an I.T. professional, specialized in PHP and web development. And a technology enthusiastic.

#### Contact me: ####
* Skype: gabriel-guelfi
* Email: gabriel.valguelfi@gmail.com
* Website: [gabrielguelfi.com.br](http://gabrielguelfi.com.br)
* Blog: [Develog](http://blog.gabrielguelfi.com.br)
* Linkedin: [Gabriel Guelfi](https://br.linkedin.com/in/gabriel-valentoni-guelfi-30ba8b4b)