<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="karurazer">
    <title>Saper</title>
</head>
<body>
    <?php
    class Cell{
        private $is_mine = false;
        private $is_open = false;
        private $mines_around = 0;
    }
    class Minesweeper{
        public $columns;
        public $rows;
        private $size;
        public $bombs;
        private $board = [];
        function __construct($columns = 8, $rows = 8, $bombs=10){
            $this->size = $this->columns * $this->rows;
            $this->columns = $columns;
            $this->rows = $rows;
            $this->size = $this->rows * $this->columns;
            $this->bombs = $bombs;
            
            for ($j=0;$j<$this->rows;$j++){
                $line=[];
                for ($i=0; $i < $this->columns; $i++) { 
                    $line[] = new Cell();
                }
                $this->board[] = $line;
            }
            

        }
        
        function place_mines(){
            
        }
        function find_neighbors(){

        }
        function play(){
            
        }
        function show_board(){
            var_dump($this->board);
        }
    }
$a = new Minesweeper();
$a->show_board()
    ?>
</body>
</html>