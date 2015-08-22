<?php
/**
This Class for Url convertion
It will convert flexiload.view to default/flexiload/view.html
*/


class u {
	public $file;
	public  function __construct($file){
		$files =THEME.DIRECTORY_SEPARATOR.'backend'.DIRECTORY_SEPARATOR.str_replace('.',DIRECTORY_SEPARATOR,$file).'.html';
		$this->file =$files;
	}
 public function __toString() {
        return $this->file;
    }

}

class uf {
	public $file;
	public  function __construct($file){
		$files =THEME.DIRECTORY_SEPARATOR.'frontend'.DIRECTORY_SEPARATOR.str_replace('.',DIRECTORY_SEPARATOR,$file).'.html';
		$this->file =$files;
	}
 public function __toString() {
        return $this->file;
    }

}



class addJs {
	public $file;
	public  function __construct($file){
		$files =THEME.DIRECTORY_SEPARATOR.'frontend'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.str_replace('.',DIRECTORY_SEPARATOR,$file).'.js';
		$this->file =$files;
	}
 public function __toString() {
        return $this->file;
    }

}


?>