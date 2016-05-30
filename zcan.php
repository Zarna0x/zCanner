<?php
error_reporting(0);
/*
 * zCanner
 * @Author:Zarna0x (c) 2016
 */
try
{

$handle = fopen("php://stdin","r");
echo "სკანერი z-Canner v0.1 \n";
echo "ავტორი: Zarna0x \n";
echo "(c) 2016 \n";
echo "ჩაწერეთ Yes გასაგრძელებლად,  No პროგრამის გასათიშად: ";
/*
* Confirm Message
*/
$confirm = strtolower(trim(fgets($handle)));
echo "\n";

switch($confirm)
{ 
   case 'yes':

     require_once 'vendor/autoload.php';
     echo "ჩაწერეთ ვებ გვერდი სკანირებისათვის: ";
     $url = strtolower(trim(fgets($handle)));
     echo "\n";
     if(isset($url) && !empty($url))
     {
      
         $myClient = new GuzzleHttp\Client([
           'headers' => ['User-Agent' => 'xmap']
         ]); 

        $feed_response = $myClient->request('GET',$url);
      
        echo "აირჩიეთ მეთოდი: \n";
        echo "1) საიტის შიგთავსის წამოღება \n";
        echo "2) საიტის ინფორმაციის წამოღება \n";
        echo "3) კონკრეტული პორტის სკანირება \n";
        echo "4) ძირითადი პორტების სკანირება \n";
        echo "მიუთითეთ მეთოდი: ";
        
        $opt = strtolower(trim(fgets($handle)));   
        
        echo "\n";


        if($opt == 1)
        {
           echo "<pre>",print_R((string) $feed_response->getBody(),1),"</pre>"; 
           return;
        }
        else if($opt == 2)
        {
           /*
           *  Get Headers
           */
           foreach ($feed_response->getHeaders() as $name => $values) {
                echo $name . ': ' . implode(', ', $values) . "\r\n";
            }
        }
        elseif ($opt == 3) {
          echo "ჩაწერეთ პორტი: ";
           $port = trim(fgets($handle));
           echo "\n";
           if(fsockopen($url,$port))
           {
               echo $port."\033[32m პორტი ღიაა \033[0m  \n \n ";
           }else
           {
              echo $port."\033[31m პორტი დახურულია \033[0m \n \n ";
           }

           foreach ($feed_response->getHeaders() as $name => $values) {
                echo $name . ': ' . implode(', ', $values) . "\r\n";
            }
        }
        elseif($opt == 4)
        {
           echo "\n";
           echo "გთხოვთ დაიცადოთ. სკანირებამ შესაძლოა წაიღოს გარკვეული დრო \n";
           echo "\n";

           $ports_for_scan = [
                    20,
                    21,
                    22,
                    23,
                    25,
                    43,
                    53,
                    67,
                    68,
                    80,
                    110,
                    113,
                    143,
                    161,
                    194,
                    389,
                    443,
                    587,
                    631,
                    666
             ]; 
           
           foreach($ports_for_scan as $ports)
           {
                 if(fsockopen($url,$ports))
                 {
                     echo $ports."\033[32m პორტი ღიაა \033[0m  \n \n ";
                 }else
                 {
                    echo $ports."\033[31m პორტი დახურულია \033[0m \n \n ";
                 }

           }

           # get headers 
           foreach ($feed_response->getHeaders() as $name => $values) {
                echo $name . ': ' . implode(', ', $values) . "\r\n";
            }
        }
     }

   break;

   case 'no':
      return;
   break;

   default:
    echo "Incorrect Command! \n";
}


return;


}catch(Exception $e)
{
   $e->getMessage();
}

?>