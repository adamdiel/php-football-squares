<?php

class football_squares{
	
		public $rows = 10;
		public $cols = 10;
		public $data = 'data.txt';
		public $password = 'password';
		public $team_one = 'Team One';
		public $team_two = 'Team Two';
		public $price = '5.00';
		public $currency_symbol = '$';

		function __construct(){
			//error_report(E_ALL);
			//ini_set('display_errors', '1');

			session_start();

			if($_GET['logout'] ==1){
				$exp = time() + (86400 * 30);
				setcookie('auth', '', -$exp);
				header("Location: index.php");
			}
		}
	
		function write($id,$value){

			$data = $this->data();
			$data[$id] = $value;
			
			$write = serialize($data);
			file_put_contents($this->data, $write);

		}

		function data(){

			$file = $this->data;
					
			if (file_exists( $file)) {
				$data = file_get_contents( $file);
			} else {
				file_put_contents($file, serialize(array()));
				$data = file_get_contents( $file);
			}

			$array = unserialize($data);
		 
			return $array;
			
		}
		
		function form($id,$entry = ''){
			
			if($entry != ''){
				$value = $entry ;
			}else{
				$value = $_SESSION['name'] ;
			}
			
			$h .=
				'<div style="display:none"><div class="register_square_'.$id.'"><form action="index.php" method="post">
				<input type="hidden" name="id" value="'.$id.'">
				Name: <input type="text" name="name" value="'.$value.'" style="width:200px"> <input type="submit" name="save" value="Save it!">
				</form></div></div>';
				  
			return $h;
		}

		function build(){
				
				
				if($_POST['save'] != ''){
					
					$this->write($_POST['id'],$this->sanitize($_POST['name']));
					$_SESSION['name'] = $this->sanitize($_POST['name']);
				}
				
				
				$columns = 100 /  ($this->cols+1);
				$data = $this->data();
				
				
				$h .=
				'<style type="text/css">
				.square_col{width:'.$columns.'%}
				</style>
				<div id="team_one">'.$this->team_one.'</div>
				<div id="team_two">'.$this->team_two.'</div>
				<div id="squares_container">';
				$abs = 0;
					
					
				for($i=0; $i< $this->rows+1; $i++){
					if($i != 0){
						$abs += $this->cols+1;
					}
					$cols =0;
					$h .= '<div class="square_row">';
					for($c=0; $c< $this->cols+1; $c++){
						$cols++;
						$num = $cols + $abs;
						if($this->auth() == true){
							$picked = 'javascript:squares_popup(\'.register_square_'.$num.'\')';
								 
							if($data[$num] != ''){
								$entry  = $data[$num];
							}
						}else{
							$picked = '#';	
							$entry  = '';
						}
								
						if($data[$num] == ''){
							if($i==0 or $c==0){
								if($i==0 and $c==0){
									$link = '<a href="#" class="chosen">SCORES</a>';	
								}else{
									$link = '<a href="'.$picked.'">TBD</a>';	
								}
							}else{
								$square_num = $num - 10 - $i - 1;
								$link = '<a href="javascript:squares_popup(\'.register_square_'.$num.'\')" >Register <br>#'.$square_num.'</a>';	
							}
						}else{
							$link = '<a href="'.$picked.'" class="chosen">'.$data[$num].'</a>';	
						}
								
						$h .= 
								'<div class="square_col">
								<div class="square_col_inside">
								'.$link .'
								'.$this->form($num,$entry).'
								</div> 
								</div>';
					}
						
					$h .='<div style="clear:both"></div></div>';
						
				}
				$h .= '</div>';
					
				$h .= $this->admin();
				$h .= $this->stat_data();

				return $h;			
		}
		
		function stat_data(){
			
			$data = $this->data();

			for($i=0; $i< $this->cols+2; $i++){
				unset($data[$i]);
			}

			$index = 1;
			for($i=0; $i< $this->rows+2; $i++){
				if($i == 0){
					unset($data[$i]);
				}else{
					$index = $index + 11;
					unset($data[$index]);
				}
			}

			if(is_array($data)){
				$total = count($data);
			}else{
				$total = 0;
			}

			$totals = array_count_values($data);
			$left = ($this->rows * $this->cols) - $total;
			$total_squares .='<ul>';
			foreach($totals as $name => $times){
					
						$total_price = $times * $this->price;
						$total_squares .= '<li>'.$name.' has  '.$times.' squares '.$this->currency_symbol.''.$total_price.'</li>';
				
			}
			$total_squares .='</ul>';
			
			$h .=
			'<div class="noPrint">
			<script>
  				$(function() {
    				$( "#progressbar" ).progressbar({
      					value: '.$total .'
    				});
  				});
			</script> 
			<h2>Stats</h2>
			<div id="progressbar"><div class="progress-label">'.$total .'% Complete, only '.$left.' squares left. </div></div>	
			'.$total_squares.'
			</div>';
			
			return $h;
		}
		
		function auth(){
		
				if($_COOKIE['auth'] != '' && $_COOKIE['auth'] == md5($this->password)){
					
				return true;
				
				}else{
					
				return false;	
				}
			
		}

		function admin(){
			$password = $this->sanitize($_POST['password']);
			if($_POST['password'] != ''){
			
				if($_POST['password']  == $this->password){
					$exp = time() + (86400 * 30);
					setcookie('auth', md5($_POST['password']), $exp);
					header("Location: index.php");
				}else{
					$h .= 'Inccorect Login';
				}
				
			}
			
		
		
			$h .= '<div class="squares_login noPrint">';
			
			if($this->auth() == false){
					$h .='<a href="javascript:squares_popup(\'.admin_login\')" class="button">Login</a>';
			}else{
				$h .='<a href="?logout=1" class="button">Logout</a>';
			}
					
			$h .=
			'<div style="display:none"><div class="admin_login"><form action="index.php" method="post">
			Password: <input type="text" name="password"  style="width:200px"> <input  type="submit" name="login" value="Login">
			</form></div></div>
			</div>';
					
			return $h;
			
		}
			
		function sanitize($input) {
			$output = addslashes(htmlspecialchars(strip_tags(trim($input))));
			return $output;
		}
}
?>