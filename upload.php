<?php
// http://r-band.ru/how-to/chtenie-faylov-excel-na-php-osnovnye-metody-klassa-phpexcel.html

// https://wp-kama.ru/question/kak-schitat-excel-fayl-php-skriptom

// https://habrahabr.ru/post/140352/

// https://gist.github.com/searbe/3284011

// https://stackoverflow.com/questions/21115191/reading-values-from-specific-range-of-cells-using-phpexcel/21121965

if(isset($_POST["submit"])) {

}else{
    die("No file parse"); //needed for stoping execude code in case of nothing
}

$target_dir = "uploads/"; //specifies the directory where the file is going to be placed

$uploadOk = 1;

$unic = md5(uniqid(rand(), true)); //generate random value for name of file

$target_file = $target_dir . $unic . basename($_FILES["fileToUpload"]["name"]); // create unical name of file

$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION)); // holds the file extension of the file (in lower case)

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow certain file formats
if($fileType != "xlsx") {
    $uploadOk = 0;
    die("Sorry, only xlsx files are allowed.");
}else{
    echo "Succsessfully uploaded!<br>Result is below.<br><br>";
}

// check moving uploaded file to direction
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    
    require_once dirname(__FILE__) . '/Classes/PHPExcel.php'; //if file uploaded to needed direction go on 
    
    // $result = array();
    
    $file_type = PHPExcel_IOFactory::identify($target_file); // get type of file (xls, xlsx - needed fo correct proceeding
    
    $objReader = PHPExcel_IOFactory::createReader( $file_type ); // create the object for reading
    
    $objPHPExcel = $objReader->load($target_file); // upload data from the file to the object

  $result = $objPHPExcel->getActiveSheet()->toArray(); // download data from the object to the array
   echo "ARRAY ON FIRST WORKSHEET:<br>";
   print_r($result);
   echo "<br>";
   print_r($result[1][0]);
   echo "<br>";
   echo array_sum($result);
   echo "<br>";

   // change a spreadsheet’s active sheet
   $objPHPExcel->setActiveSheetIndex(1);
    $result = $objPHPExcel->getActiveSheet()->toArray();
    echo "ARRAY ON SECOND WORKSHEET:<br>";
    print_r($result);
    echo "<br>";

    // calculate sum of array
    $sumArray=array();
    foreach ($result as $key => $array) 
    {
        
        $sumArray[$key]=array_sum($array);
    }
   echo "<br>SUM OF ARRAY OF SECOND WORKSHEET:<br>";
   print_r($sumArray);
        echo "<br><br>";

   // download data from the object to the array which consists of data from all sheets
    foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
        $lists[] = $worksheet->toArray();
    }

    echo "<br>ALL ARRAYS ARE REPRESENTED IN TABLES:<br>";
    foreach($lists as $list){
 echo '<table border="1">';
 // loop rows
 foreach($list as $row){
   echo '<tr>';
   // loop columns
   foreach($row as $col){
     echo '<td>'.$col.'</td>';
 }
 echo '</tr>';
 }
 echo '</table>';
}
}
?>