<?php
	
return [
    /*
    |--------------------------------------------------------------------------
    | Mail Protocol
    |--------------------------------------------------------------------------
    |
    | You may specify which one you're using throughout
    | your application here. By default, Kaliba is setup for SMTP mail.
    |
    | Supported: "smtp", "sendmail", "mail"
    */
    "protocol"  =>  "smtp",

    /*
    |--------------------------------------------------------------------------
    | SMTP Host Address
    |--------------------------------------------------------------------------
    |
    | Here you may provide the host address of the SMTP server used by your
    | applications. A default option is provided.
    |
    */
    "host"      =>  "smtp.gmail.com",

    /*
    |--------------------------------------------------------------------------
    | SMTP Host Port
    |--------------------------------------------------------------------------
    |
    | This is the SMTP port used by your application to deliver e-mails to
    | users of the application.
    |
    */
    "port"      =>  587,

    /*
    |--------------------------------------------------------------------------
    | E-Mail Encryption Protocol
    |--------------------------------------------------------------------------
    |
    | Here you may specify the encryption protocol that should be used when
    | the application send e-mail messages. A sensible default using the
    | transport layer security protocol should provide great security.
    |
    */
    "encryption"  =>  "tls",

    /*
    |--------------------------------------------------------------------------
    | SMTP Server Username
    |--------------------------------------------------------------------------
    |
    | If your SMTP server requires a username for authentication, you should
    | set it here. This will get used to authenticate with your server on
    | connection. You may also set the "password" value below this one.
    |
    */
    "username"  =>  "",
    "password"  =>  "",

    /*
    |--------------------------------------------------------------------------
    | Sender Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all e-mails sent by your application to be sent from
    | the same address. Here, you may specify a name and address that is
    | used globally for all e-mails that are sent by your application.
    |
    */
    "sender_address"    =>  "",
    "sender_name"       =>  "",

    /*
    |--------------------------------------------------------------------------
    | SMTP Authentication
    |--------------------------------------------------------------------------
    |
    | If your SMTP server requires authentication, you should
    | set turn it on.  You may also set the "password" value below this one.
    |
    */
    "authenticate"  =>  true,

    /*
    |--------------------------------------------------------------------------
    | SMTP Authentication Options
    |--------------------------------------------------------------------------
    */
    "options"   =>  [ 
        "ssl" => [
            "verify_peer" => false, 
            "verify_peer_name" => false, 
            "allow_self_signed" => true
        ]
    ]
];
	
    