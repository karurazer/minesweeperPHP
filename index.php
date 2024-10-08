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
        private $max_mines_around = 5;
        function can_be_mine(){
            if(!$this->is_mine && $this->mines_around <= $this->max_mines_around){
                return true;
            }else{
                return false;
            }
        }
        
        function set_bomb(){
            $this->is_mine = true;
        }
        function show(){
            if ($this->is_mine){
                echo 'mine ';
            }
            else{
                echo '0';
            }
        }
    }   
    class Minesweeper{
        private $columns;
        private $rows;
        private $size;
        private $bombs;
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
            for ($i=0; $i < $this->bombs; $i++) { 
                $column = random_int(0, $this->columns - 1);
                $row = random_int(0, $this->rows - 1);
                if ($this->board[$row][$column]->can_be_mine()){
                    $this->board[$row][$column]->set_bomb();
                }
                else{
                    $i--;
                }
                
            }
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
$a->place_mines();
$a->show_board();
    ?>
</body>
</html>