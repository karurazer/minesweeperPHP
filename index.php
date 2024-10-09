<?php
    session_start();
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="karurazer">
    <title>Saper</title>
    <link rel="stylesheet" href="src/css/style.css">
    <!-- <style></style> сделать чтобы php сетку создовал как надо, флаги, победа,порожение-->
</head>
<body>
    
        
        
        <?php
    class Cell{
        public $is_mine = false;
        public $is_open = false;
        public $was_mine = false;
        public $row;
        public $column;
        public $mines_around = 0;
        private $max_mines_around = 5;
        function __construct($column, $row){
            $this->column=$column;
            $this->row=$row;
        }
        function open(){
            $this->is_open = true;
        }
        function can_be_mine(){
            if(!$this->is_mine && $this->mines_around <= $this->max_mines_around && !$this->was_mine){
                return true;
            }else{
                return false;
            }
        }
        function plus_neighbor(){
            $this->mines_around++;
        }
        
        function set_bomb(){
            $this->is_mine = true;
        }
        function show(){
            echo '<form action="index.php" method="post">';
            if ($this->is_open){
                if($this->mines_around == 0 && !$this->is_mine){
                    echo '<span></span>';
                }else if($this->is_mine){
                    echo'<img src="src/img/mine.png" alt="mine">';
                }
                else{      
                    echo "<input type='submit' class='number_mines' value='$this->mines_around'>";
                }
               
                }
            else{
                echo '<input type="submit" class="nothing" value="">';
            }
            if(!isset($_SESSION['board'])){
                echo "<input type='hidden' name='cell' value='$this->row $this->column 1'>";
                echo '</form>';
            }else{
                echo "<input type='hidden' name='cell' value='$this->row $this->column 0'>";
                echo '</form>';
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
            if (isset($_POST['restart'])){
                session_unset();  
            }
            $this->columns = $columns;
            $this->rows = $rows;
            $this->size = $this->rows * $this->columns;
            $this->bombs = $bombs;
            if(isset($_SESSION['board'])){
                if(isset($_POST['cell'])){
                    $t = explode(" ",$_POST['cell']);
                    $new_row = $t[0];
                    $new_column = $t[1];
                    $first = $t[2];
                    $this->board = $_SESSION['board'];
                    $this->board[$new_row][$new_column]->open();        
                    if(isset($_POST['cell'])){
                $t = explode(" ",$_POST['cell']);
                $new_row = $t[0];
                $new_column = $t[1];
                $first = $t[2];
                if($first + 0 == 1){
                    if(($this->board[$new_row][$new_column])->is_mine){
                        $this->board[$new_row][$new_column]->is_mine = false;
                        $this->board[$new_row][$new_column]->was_mine = true;
                        foreach($this->get_neighbors($new_row, $new_column) as $item){
                            $item->mines_around--;
                        }
                        $this->place_mines(1);
                    }
                }
            }
                    if($this->board[$new_row][$new_column]->is_mine){
                        $this->lose();
                        return;
                    }
                    if($this->board[$new_row][$new_column]->mines_around == 0 && !$this->board[$new_row][$new_column]->is_mine){
                        $this->open_around_cell($new_row, $new_column);
                    }
                }
            }else{
                for ($j=0;$j<$this->rows;$j++){
                        $line=[];
                    for ($i=0; $i < $this->columns; $i++) { 
                        $line[] = new Cell($i, $j);
                    }
                    $this->board[] = $line;
                    }
                        $this->place_mines($this->bombs);
            }
            $this->update_board();
            
            
        }
        function update_board(){
            if(isset($_POST['cell'])){
                $_SESSION['board'] = $this->board;
            }
            $this->show_board();
           
        }
        private function place_mines($bombs){
            for ($i=0; $i < $bombs; $i++) { 
                $column = random_int(0, $this->columns - 1);
                $row = random_int(0, $this->rows - 1);
                if ($this->board[$row][$column]->can_be_mine()){
                    $this->board[$row][$column]->set_bomb();
                    $this->add_neighbors_mines($row, $column);
                }
                else{
                    $i--;
                }
                
            }
        }
        private function get_neighbors($row, $column){
            $neigbors = [];
            if ($row > 0){
                $neigbors[] = $this->board[$row - 1][$column];
                if ($column != $this->columns - 1){
                    $neigbors[] = $this->board[$row - 1][$column + 1];
                }
                if ($column > 0){
                    $neigbors[] = $this->board[$row - 1][$column - 1];
                }
            }
            if ($row != $this->rows - 1){
                $neigbors[] = $this->board[$row + 1][$column];
                if ($column != $this->columns - 1){
                    $neigbors[] = $this->board[$row + 1][$column + 1];
                }
                if ($column > 0){
                    $neigbors[] = $this->board[$row + 1][$column - 1];
                }
            }
            if ($column != $this->columns - 1){
                $neigbors[] = $this->board[$row][$column + 1];
            }
            if ($column > 0){
                $neigbors[] = $this->board[$row][$column - 1];
            }
            return $neigbors;
        }
        private function add_neighbors_mines($row, $column){
            foreach($this->get_neighbors($row, $column) as $neigbor){
                $neigbor->plus_neighbor();
            }
        }
        function open_around_cell($row, $column){
           $t = $this->get_neighbors($row, $column);
            foreach($t as $cell){
                if($cell->mines_around == 0 && !$cell->is_open){
                    $cell->open();
                    $this->open_around_cell($cell->row, $cell->column);
                }
                $cell->open();
                
            }
        }
        function show_board(){
            echo '<main>';
            foreach($this->board as $line){
                foreach($line as $cell){
                        $cell->show();    
                    }
                }
            echo'</main><form action="index.php" method="post">
        <input type="submit" id="restart" value="restart">
        <input type="hidden" name="restart" value="true">
    </form>';
            }
        private function lose(){
            echo '<main>';
            foreach($this->board as $line){
                foreach($line as $cell){
                        $cell->open();
                        $cell->show();    
                    }
                }
            session_unset();
            echo'</main>';
            echo'<form action="index.php" method="post">
                <input type="submit" id="restart" value="try again">
                <input type="hidden" name="restart" value="true">
                </form>';
            }
        
    }
new Minesweeper();
    ?>
</body>
</html>