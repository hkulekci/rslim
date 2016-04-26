<?php

function app_content($request, $args){

    return ['data'=>['name'=>$args['name']],'status'=>200];
}