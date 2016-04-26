<?php

function app_content($request, $args){

    return ['data'=>['name'=>$request->getParam('name')],'status'=>200];
}