<?php

return [
    'allowed_origins' => explode( env(',','CORS_DOMAIN'), '*' ),
    'allowed_headers' => explode( env(',','CORS_HEADER_ALLOWED'), 'X-Requested-With' )
];
