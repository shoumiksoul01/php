<?php
function readConfigFile(string $path): array
{
    $content = @file_get_contents($path);
    if ($content === false) {
        throw new RuntimeException("Failed to read config file: $path");
    }
    return json_decode($content, true);
}
function bootapplication():void{
    try{
        $config = readconfigfile('config.json');
    }
    catch(RuntimeException  $e){
        throw new RuntimeException("Application failed to boot",0,$e);

    }

}
try{
    bootapplication();
}
catch (RuntimeException $e){
    echo $e->getMessage();
    echo  $e->getPrevious()->getMessage();
}



?>