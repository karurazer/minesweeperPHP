<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="karurazer">
    <title>Saper</title>
</head>
<body>
    <?php
    function Generator($x=10, $y=10, $number_of_bombs=10){
        $board = [];
        for ($j=0;$j<$y;$j++){
            $line=[];
            for ($i=0; $i < $x; $i++) { 
                $line[] = 0;
            }
            $board[] = $line;
        }
    var_dump($board);
    }

    

    ?>
</body>
</html>