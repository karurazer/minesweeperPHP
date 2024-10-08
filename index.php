<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="karurazer">
    <title>Saper</title>
</head>
<body>
    <?php
    class Board{
        public $size = ['x'=>10, 'y'=>10];
    
        public $number_of_bombs = 10;
        private $board=[];
    
        function BoardGenerator(){
            
            for ($j=0;$j<$this->size['y'];$j++){
                $line=[];
                for ($i=0; $i < $this->size['x']; $i++) { 
                    $line[] = 0;
                }
                $this->board[] = $line;
            }
        }
    
    }
$a = new Board();
    ?>
</body>
</html>