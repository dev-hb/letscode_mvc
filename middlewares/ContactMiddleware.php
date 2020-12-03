<?php


class ContactMiddleware extends Exception {

    public function handleMiddleware(){
        echo "Contact middleware has been called";
    }

}