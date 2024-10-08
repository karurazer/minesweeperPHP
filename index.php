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
    <!-- <style></style> сделать чтобы php сетку создовал как надо и кнопок много -->
</head>
<body>
    <main>
        
        
        <?php
    class Cell{
        public $is_mine = false;
        public $is_open = false;
        public $row;
        public $column;
        public $mines_around = 0;
        private $max_mines_around = 5;
        function __construct($column, $row){
            $this->column=$column;
            $this->row=$row;
        }
        function can_be_mine(){
            if(!$this->is_mine && $this->mines_around <= $this->max_mines_around){
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
                if($this->mines_around == 0){
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
            echo "<input type='hidden' name='cell' value='[$this->row, $this->column]'>";
            echo '</form>';
        }
            
          
        
    }
     
    class Minesweeper{
        private $columns;
        private $rows;
        private $size;
        private $bombs;
        private $board = [];
        function __construct($columns = 8, $rows = 8, $bombs=20){
            if (isset($_POST['restart'])){
                session_unset();
                
            }
            $this->columns = $columns;
            $this->rows = $rows;
            $this->size = $this->rows * $this->columns;
            $this->bombs = $bombs;

            if(isset($_SESSION['board'])){
                $this->board = $_SESSION['board'];
            }else{
                for ($j=0;$j<$this->rows;$j++){
                        $line=[];
                    for ($i=0; $i < $this->columns; $i++) { 
                        $line[] = new Cell($i, $j);
                    }
                    $this->board[] = $line;
                    }
                        $this->place_mines();
            }
            $this->update_board();
            
            
        }
        function update_board(){
            $_SESSION['board'] = $this->board;
            $this->show_board();
        }
        private function place_mines(){
            for ($i=0; $i < $this->bombs; $i++) { 
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
        function open_empty_cells(){
            foreach($this->board as $line){
                foreach($line as $cell){
                    if ($cell->mines_around == 0 && !$cell->is_mine){
                        $cell->is_open = true;
                        foreach($this->get_neighbors($cell->row, $cell->column) as $elem){
                            $elem->is_open = true;
                        }   
                    }   
                }
            }
        }
        function show_board(){
            $this->open_empty_cells();
            foreach($this->board as $line){
                foreach($line as $cell){
                        $cell->show();    
                    }
                }
            }
    }
new Minesweeper();
    ?>
        

    </main>
    <form action="index.php" method="post">
        <input type="submit" id="restart" value="restart">
        <input type="hidden" name="restart" value="true">
    </form>
    
</body>
</html>