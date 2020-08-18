##Client

####Uploading a file
```php
(new \Askync\Utils\HttpUtils\Client(['expectJson' => true, 'throwErrorOnNotJson' => false]))
        ->post('http://url.to',
            [
                'Content-Type' => 'multipart/form-data',
            ],
            [
                'file' => new CURLFile('path/to.file')
            ]
        );
```

####post example

```php
(new \Askync\Utils\HttpUtils\Client(['expectJson' => true, 'throwErrorOnNotJson' => false]))
        ->post('http://url.to',
            [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImZkYWFkZjEyYzEzYTkzOTgxMTQ1ZmNjYzc4ZmVhMWUzNzEyMDI3MzdjNmFmMDE3MzhhNGE0MGNlOTEyYzFmYTZjMTE3NTFjY2Y2ZTljYTc0In0.eyJhdWQiOiI0IiwianRpIjoiZmRhYWRmMTJjMTNhOTM5ODExNDVmY2NjNzhmZWExZTM3MTIwMjczN2M2YWYwMTczOGE0YTQwY2U5MTJjMWZhNmMxMTc1MWNjZjZlOWNhNzQiLCJpYXQiOjE1OTY2MTcyNTEsIm5iZiI6MTU5NjYxNzI1MSwiZXhwIjoxNjI4MTUzMjUxLCJzdWIiOiIyIiwic2NvcGVzIjpbXX0.H1sfLWLMSF8cpAv_X7UKD_WRG7G6ar0HN4vdMEj0gvfYvFFt0aDWdndZkbRHjbMU_yIbChF2h3tX22xZWHY-GjA49Gdf9LCO8jlzd2B47dadkAOAOzzc_7YKExXjjARK3i4JmrjjyhBwnlAm2jREcnZXehOlrllG9Q_XoXk9xd9YFNzpOa6UTKotVhj7ZBJmsjyFhNeCiyemPS2w33QiUNU31lAVZT2nNn-4Hqma67uRu4OIvzXFRWb63P5X_9O7nfh3-K7cdUvuyNPagaZdRlSr11acWk_43af4-a6fIAas1JgHIyb_MI-Bif33VxGlOdZzQfDOPahs6pflaLYjWL4jTmUGngDMF9izAA4DOXqe5gRMzkrgjcPYb1AqAsnyfo3K9WJpUtYotVI1YSO9nT4ZJdC0Iaue4DV3mEJzuaXZVpKnb76A5c-cCDeKERJpI9TbRpnl8dgK9OzQlSTSCxGWcyDzowgs28tNAKTveWDZkhw9Nc3EcQ5e0flGIgv13ukLge0L4U1xFfTOR0tewYAT9NZ2qwBjMe_Qt0rofhWavcOu7wkEzoK-9djrwcNw1MsargQljgCApqYffI30YrH8hLIuN5RPGcd2-qmRHC40EZRhl2Pfl5Q1zQgw3ZHl20Ue5AHdQzAPB8xxl9QuJXXurJz5q-eAQhfFYsffe8E',
            ],
            [
                'store_name' => 'My Store'
            ]
        )
```
