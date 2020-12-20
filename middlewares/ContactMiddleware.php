<?php


class ContactMiddleware extends Exception {

    public function handleMiddleware(){
        //flkj kfhje k hfkjez hfkjh
        Request::redirect('home');
    }

}