<?php
  
	
return [
    
    "protocol"  =>  "smtp",
    "host"      =>  "smtp.gmail.com",
    "port"      =>  465,
    "auth"      =>  true,
    "secure"    =>  "ssl",
    "username"  =>  "briansimpokolwe@gmail.com",
    "password"  =>  "1d0ntkn0w",
    "sender_address"    =>  "briansimpokolwe@gmail.com",
    "sender_name"       =>  "Brian",
    "options"   =>  [ 
        "ssl" => [
            "verify_peer" => false, 
            "verify_peer_name" => false, 
            "allow_self_signed" => true
        ]
    ]
];
	
    