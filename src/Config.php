<?php
class Config{
	protected $file = NULL;
	protected $config = NULL;
	protected $basedir = NULL;
	
	
	/**
	 *	creates the config object
	 *
	 *	sets the basedir
	 *	sets the configfile
	 *	
	 *  if file does not exists it will create a configfile in the basedir
	 *	
	 *	@return void
	 *
	 *	@throws ConfigException
	 *
	 */
	public function __construct(){
		$this->basedir = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;
		$this->file = $this->basedir.'config.txt';
		if(!file_exists($this->file )){
			if(!is_writable ($this->file)){
				throw new ConfigException(sprintf('file '.$this->file.' is not writable by the webserver'));
			}
			$result = file_put_contents($this->file,serialize(array()));
			if($result === false){
				throw new ConfigException(sprintf('file '.$this->file.' could not be written'));
			}
		}
	}
	
	
	
	/**
	 *	returns the basedir path
	 *	
	 *	@return string basedir path
	 *
	 */
	public function getBasedir(){
		return $this->basedir;
	}
	
	
	/**
	 *	reads the main config file
	 *
	 *  @var bool $update - update the config from file
	 *	
	 *	@return bool success-state
	 *
	 *	@throws ConfigException
	 *
	 */
	public function read($update = FALSE){
		if(($update === FALSE ) && ($this->config !== NULL)){
			return $this->config;
		}
		
		$this->config = array();
		
		if(!is_readable($this->file)){
			throw new ConfigException(sprintf('file '.$this->file.' is not readable by the webserver'));
		}
	
		$config = file_get_contents($this->file);
		if($config === false){
			throw new ConfigException(sprintf('file '.$this->file.' could not be read'));
		}
		
		if(!empty($config)){
			$this->config = unserialize($config);
			if($this->config === FALSE){
				throw new ConfigException(sprintf('file '.$this->file.' is malformated'));
			}
		}
		return $this->config;
	}
	
	
	/**
	 *	writes the main config file
	 *
	 *  @var string $config - the new config
	 *	
	 *	@return bool success-state
	 *
	 *	@throws ConfigException
	 *
	 */
	public function write($config){
		if(!is_writable ($this->file)){
			throw new ConfigException(sprintf('file '.$this->file.' is not writable by the webserver'));
		}
		
		$this->config = $config;
	
		$result = file_put_contents($this->file,serialize($this->config));
		if($result === false){
			throw new ConfigException(sprintf('file '.$this->file.' could not be written'));
		}
		return true;
	}
	
	
	/**
	 *	deletes a config line
	 *
	 *  @var string $index - index of the configline
	 *	
	 *	@return bool success-state
	 *
	 *	@throws ConfigException
	 *
	 */
	public function remove($index){
		$config = $this->read();
		if(!isset($config[$index])){
			throw new ConfigException(sprintf('index '.$index.' does not exist'));
		}
		unset($config[$index]);
		return $this->write($config);
	}
	
	
	/**
	 *	adds a config line
	 *
	 *  @var string $entry - the new config
	 *	
	 *	@return bool success-state
	 *
	 *	@throws ConfigException
	 *
	 */
	public function add($entry){
		$config = $this->read();
		$config[] = $entry;
		return $this->write($config);
	}
	
	
	/**
	 *	replaces a config line
	 *
	 *  @var string $index - index of the old configline
	 *
	 *  @var string $entry - the new configline
	 *	
	 *	@return bool success-state
	 *
	 *	@throws ConfigException
	 *
	 */
	public function replace($index,$entry){
		$config = $this->read();
		if(!isset($config[$index])){
			throw new ConfigException(sprintf('index '.$index.' does not exist'));
		}
		$config[$index] = $entry;
		return $this->write($config);
	}
	
	
	
	/**
	 *	activates a config line
	 *
	 *  @var string $index - index of the old configline
	 *	
	 *	@return bool success-state
	 *
	 *	@throws ConfigException
	 *
	 */
	public function activate($index){
		$config = $this->read();
		if(!isset($config[$index])){
			throw new ConfigException(sprintf('index '.$index.' does not exist'));
		}
		foreach($config as $device => $settings){
			unset($config[$index]['active']);
			if($device == $index){
				$config[$device]['active'] = true;
			}
		}
		return $this->write($config);
	}
	
}

?>