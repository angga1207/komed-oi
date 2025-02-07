<?php

declare(strict_types=1);

return [
    /*
     * ------------------------------------------------------------------------
     * Default Firebase project
     * ------------------------------------------------------------------------
     */

    'default' => env('FIREBASE_PROJECT', 'app'),

    /*
     * ------------------------------------------------------------------------
     * Firebase project configurations
     * ------------------------------------------------------------------------
     */

    'projects' => [
        'app' => [

            /*
             * ------------------------------------------------------------------------
             * Credentials / Service Account
             * ------------------------------------------------------------------------
             *
             * In order to access a Firebase project and its related services using a
             * server SDK, requests must be authenticated. For server-to-server
             * communication this is done with a Service Account.
             *
             * If you don't already have generated a Service Account, you can do so by
             * following the instructions from the official documentation pages at
             *
             * https://firebase.google.com/docs/admin/setup#initialize_the_sdk
             *
             * Once you have downloaded the Service Account JSON file, you can use it
             * to configure the package.
             *
             * If you don't provide credentials, the Firebase Admin SDK will try to
             * auto-discover them
             *
             * - by checking the environment variable FIREBASE_CREDENTIALS
             * - by checking the environment variable GOOGLE_APPLICATION_CREDENTIALS
             * - by trying to find Google's well known file
             * - by checking if the application is running on GCE/GCP
             *
             * If no credentials file can be found, an exception will be thrown the
             * first time you try to access a component of the Firebase Admin SDK.
             *
             */

            'credentials' => env('FIREBASE_CREDENTIALS', env('GOOGLE_APPLICATION_CREDENTIALS')),
            // 'credentials' => [
            //     "type" => "service_account",
            //     "project_id" => "komed-oi",
            //     "private_key_id" => "3cbf4bfa651e600b2595172f7379529f95719010",
            //     "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDSJnqN5PUe1hUd\nj6P29kFWRee/kfIN9EhaBriLK47HeQWYOJB5DuhNoiY+20hGslTWIi9Rt7zgoGBh\nDNFbNGsk9IHA9ZxOiJ+04RDsUMnDXkLSRNAGs9mxDdpvaQR/dZaM2Swokvq7jg4l\nUPDodDF33zUNxLnz8ZsGJqLtsH5r3Vd6oVuzGqf0ISAw5ZL7G/Vc9A+TZkGgBibs\nXrQcYT4e4MjE61IGXmTxiSy6HyoMDNpPlXy9MAXXEKnShNY4RbQecCzwgS6Hc9rB\nBoqUpEjQAtTxe8pAJKv3vcLW1sKXKjM58w6p3hFVZAJEvXHAELsxd9vcciI4DHbh\n3/El4bijAgMBAAECggEAIXFxMHKe1aTxIE6zI8KlvDKMwm5Z7IGTIAyQwskQREtM\nqiBaUYGfU3d6CafA9Us8+AThreoFG2Q6Ykd1a8PIR03H9mq/0LmqXNEybcEfksFF\n9gtwZZiqjKQtPmyihBw09L486koVr9JYCvU6GWwgUTjKyyGjDCDHcG+mnxfmzohV\nfg3zOFR0FRSEbi26dZORDnzK0lYuzqaCDpo+IF7y7aZTydDYSnsDTs6eKNvqqegL\ntVxTiL/FG6j3qxg7yiImd9ZMO+Us3r/3GeYLiCbbA9GpCZWnsZRzD0bSAI5sCCyD\n4xI+46v5/hJUOO2rvx4csevXjA+68C6qAWNilKlHjQKBgQD38MUDalpdAtiTDyyH\n8OHP5rsC6hXOsG+p/atrfFhc0r9xxq6dZ0pFQhe9qDo5j9LMDwoqFCLbXBE6iUAe\nSgiC1zC099vOdyEYgXpyUfd35AtV8UrhHmY2WqscgLt61Kv3ajDtLXmTTJmk4DPf\n5PLO25eb1Nt8rcostpanEuy0nQKBgQDY+z0u9FvK0zmESlq8JHQh5r6L15IUrywK\nTQ8j/OCOaoxlzY9DOWo2AHhxR5LxmBsKDp4HgvxbCJ/+JBimP6imvgr5zSQPyefR\nClc5k5SljrBHtJXcTQbj+l/NcNa4ymVLLNIAJ6CG98KWv/XvWx09kZ6UJnwZtHzH\nFMfPrOx+PwKBgQCT/9SaRVHLU3eT0Orz+oXQZBodkz2RMyB0iEda0c6tGd/NOpvg\nuVdMIDerrk+TLJbX1+JpW7uy1TIzjtoHOon6EBmy7ID4rYPD7QX6V5MwrZ9WncHk\nvxCkQVsCmJVQrMI53dl2uj5n/sF3+Zz9eNy3Pb0x981MjzCvoA4tjM5ewQKBgQC+\nbpHeGdxOAF/keRV1NlEuR8NIHGQ6/xqZ19PH8/JpS5344xYvG77ZskQCt5yBqMti\nYj3TveQZSVKRy8BOeGReI0CiyKYzrTJlRrwS7jFxs++Gnzk/ZeL8Rwu51KS+/fM0\nrRuJwz2h9uSzRbpk3gSlup4AzF3yeXNhjh1naQ8OJQKBgA4Nj544Y18NBHroPQE0\ns+7ObVn9IURynWc5yXcTs5oMYlDvQDv51afqrsktOsJyfr/vC4w2zzZ+Z3ep0x2d\nBt0ZKMFhDg+X0cZz4foVoIKIlkfW5EhoEcRuOO9gPQeLmVAgd2siL/thz6ygr+cn\n1Oqbc24WsME00gGPLl0BGsK/\n-----END PRIVATE KEY-----\n",
            //     "client_email" => "firebase-adminsdk-fbsvc@komed-oi.iam.gserviceaccount.com",
            //     "client_id" => "110147966269249738031",
            //     "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
            //     "token_uri" => "https://oauth2.googleapis.com/token",
            //     "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
            //     "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-fbsvc%40komed-oi.iam.gserviceaccount.com",
            //     "universe_domain" => "googleapis.com"
            // ],
            // 'credentials' => [
            //     'path' => 'storage/app/firebase-auth.json',
            // ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Auth Component
             * ------------------------------------------------------------------------
             */

            'auth' => [
                'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firestore Component
             * ------------------------------------------------------------------------
             */

            'firestore' => [

                /*
                 * If you want to access a Firestore database other than the default database,
                 * enter its name here.
                 *
                 * By default, the Firestore client will connect to the `(default)` database.
                 *
                 * https://firebase.google.com/docs/firestore/manage-databases
                 */

                // 'database' => env('FIREBASE_FIRESTORE_DATABASE'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Realtime Database
             * ------------------------------------------------------------------------
             */

            'database' => [

                /*
                 * In most of the cases the project ID defined in the credentials file
                 * determines the URL of your project's Realtime Database. If the
                 * connection to the Realtime Database fails, you can override
                 * its URL with the value you see at
                 *
                 * https://console.firebase.google.com/u/1/project/_/database
                 *
                 * Please make sure that you use a full URL like, for example,
                 * https://my-project-id.firebaseio.com
                 */

                'url' => env('FIREBASE_DATABASE_URL'),

                /*
                 * As a best practice, a service should have access to only the resources it needs.
                 * To get more fine-grained control over the resources a Firebase app instance can access,
                 * use a unique identifier in your Security Rules to represent your service.
                 *
                 * https://firebase.google.com/docs/database/admin/start#authenticate-with-limited-privileges
                 */

                // 'auth_variable_override' => [
                //     'uid' => 'my-service-worker'
                // ],

            ],

            'dynamic_links' => [

                /*
                 * Dynamic links can be built with any URL prefix registered on
                 *
                 * https://console.firebase.google.com/u/1/project/_/durablelinks/links/
                 *
                 * You can define one of those domains as the default for new Dynamic
                 * Links created within your project.
                 *
                 * The value must be a valid domain, for example,
                 * https://example.page.link
                 */

                'default_domain' => env('FIREBASE_DYNAMIC_LINKS_DEFAULT_DOMAIN'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Cloud Storage
             * ------------------------------------------------------------------------
             */

            'storage' => [

                /*
                 * Your project's default storage bucket usually uses the project ID
                 * as its name. If you have multiple storage buckets and want to
                 * use another one as the default for your application, you can
                 * override it here.
                 */

                'default_bucket' => env('FIREBASE_STORAGE_DEFAULT_BUCKET'),

            ],

            /*
             * ------------------------------------------------------------------------
             * Caching
             * ------------------------------------------------------------------------
             *
             * The Firebase Admin SDK can cache some data returned from the Firebase
             * API, for example Google's public keys used to verify ID tokens.
             *
             */

            'cache_store' => env('FIREBASE_CACHE_STORE', 'file'),

            /*
             * ------------------------------------------------------------------------
             * Logging
             * ------------------------------------------------------------------------
             *
             * Enable logging of HTTP interaction for insights and/or debugging.
             *
             * Log channels are defined in config/logging.php
             *
             * Successful HTTP messages are logged with the log level 'info'.
             * Failed HTTP messages are logged with the log level 'notice'.
             *
             * Note: Using the same channel for simple and debug logs will result in
             * two entries per request and response.
             */

            'logging' => [
                'http_log_channel' => env('FIREBASE_HTTP_LOG_CHANNEL'),
                'http_debug_log_channel' => env('FIREBASE_HTTP_DEBUG_LOG_CHANNEL'),
            ],

            /*
             * ------------------------------------------------------------------------
             * HTTP Client Options
             * ------------------------------------------------------------------------
             *
             * Behavior of the HTTP Client performing the API requests
             */

            'http_client_options' => [

                /*
                 * Use a proxy that all API requests should be passed through.
                 * (default: none)
                 */

                'proxy' => env('FIREBASE_HTTP_CLIENT_PROXY'),

                /*
                 * Set the maximum amount of seconds (float) that can pass before
                 * a request is considered timed out
                 *
                 * The default time out can be reviewed at
                 * https://github.com/kreait/firebase-php/blob/6.x/src/Firebase/Http/HttpClientOptions.php
                 */

                'timeout' => env('FIREBASE_HTTP_CLIENT_TIMEOUT'),

                'guzzle_middlewares' => [],
            ],
        ],
    ],
];
