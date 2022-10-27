# File Gallery API using Laravel 9 &amp; AWS S3 

## Features
- User access with [sanctum](https://laravel.com/docs/9.x/sanctum)
- Upload file to AWS S3
- File gallery for every user
- Filter upload file from script content to prevent Cross-Site Scripting (XSS)
- API Docs using [Swagger UI](https://swagger.io/tools/swagger-ui/)

## Setup for Local Development

1. Update .env file to correct DB access and AWS S3
2. Run migrations

```
php artisan migrate
```
3. Run development in local

```
php artisan serve
```
4. Update local url for swagger docs
    - note: case if domain and port is not http://localhost:8000 / http://127.0.0.1:8000
    - go to  folder /public/swagger-ui-dist
    - edit file __swagger.json__
    ```
    "servers": [
        {
            "url": "http://localhost:8000/api/"
        }
    ],
    ```
5. API document link
    - http://localhost:8000/docs/
## Screenshot
![](/laravel9-api-file-gallery-s3-docs-ss.png)

## Reference Links
- https://laravel.com/
- https://laravel.com/docs/9.x/
- https://laravel.com/docs/9.x/filesystem
- https://laravel.com/docs/9.x/sanctum
- https://ethereal.email/
- https://aws.amazon.com/s3/
- https://aws.amazon.com/rds/
- https://swagger.io/tools/swagger-ui/
- https://editor.swagger.io/
