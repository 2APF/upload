<?php

/**
 * [Upload] => Classe responsável pelos uploads dos arquivos.
 * Este sistema continuará a ser modificado (github), recordando que foi feito em PHP 7 OOP.
 * 
 * @author    Artur Francisco "2APF" <arturabiliopf@gmail.com> | whatsApp: +244946428275
 * @package   Upload.
 * @copyright 2020 - 2APF.
 * @version   1.2.0.
 */

class Upload {

  	/**
    * Atributos da classe.
    */

	private $directorio;
	private $arquivo;
	private $tamanho;
    private $nome;
    private $pasta;

	private $result;
	private $error;


	/**
     * [getResult] => Função que retornará a mensagem do resutado.
     * @param string.
     * @return string mensagem. 
     */

	public function getResult() : string {
		return $this->result;
	}

	/**
     * [getError] => Função que retornará a mensagem em caso de erro.
     * @param string.
     * @return string mensagem de erro. 
     */

	public function getError() : string {
		return $this->error;
	}


	/**
     * [__construct] => Construtor que recebe parâmetro string "directorio" que pode ser nulo.
	 * Tem uma condição em que, se não for dito o directório, reconhecerá "2APF" como directório de armazenamento do arquivo.
     * @param string.
     * @return string 
     */

	public function __construct(string $directorio = null){

		$this->directorio = $directorio ?? '2APF';

		if (!file_exists($this->directorio) && !is_dir($this->directorio)):
			mkdir($this->directorio, 0777);
		endif;

	}

	/**
     * [imagem] => Método público que recebe os detalhes da imagem como parâmetros, faz as validações e em caso
	 * de certeza do arquivo, chava os métodos (criarPasta, renomearArquivo e moveArquivo).
     * @param string|int
     * @return bool true ou false. 
     */

	public function imagem(array $imagem, string $nome = null, string $pasta = null, int $tamanho = null) {
		$this->arquivo = $imagem;
		$this->nome = $nome ?? pathinfo($this->arquivo['name'], PATHINFO_FILENAME);
		$this->pasta = $pasta ?? 'imagens';
		$this->tamanho = $tamanho ?? 1;

		$extensao = pathinfo($this->arquivo['name'], PATHINFO_EXTENSION);

		$extensoesValidas = [
			'png', 
			'jpg'
		];

		$tiposValidos     = [
			'image/png', 
			'image/jpeg',
			'image/jpg'
		];

		$dimensao         = [
			'dimension',
		];

		if (!in_array($extensao, $extensoesValidas)):
			$this->error = "A extensão não e permitida";
			$this->result = false;
		elseif (!in_array($this->arquivo['type'], $tiposValidos)):
			$this->error =  "Tipo invalido";
			$this->result = false;
		elseif ($this->arquivo['size'] > $this->tamanho * (1024 * 1024)):
			$this->error = "Arquivo muito grande";
			$this->getResult = false;
		else:
			$this->criarPasta();
			$this->renomearArquivo();
			$this->moveArquivo();
		endif;

	}


	/**
     * [renomearArquivo] => Método privado responsável pela renomeação do arquivo em caso de corresponência do nome
	 * com um já existente.
     * @return string nome do arquivo
     */

	private function renomearArquivo () {
		$arquivo = $this->nome . strrchr($this->arquivo['name'], '.');
		if (file_exists($this->directorio . DIRECTORY_SEPARATOR . $this->pasta . DIRECTORY_SEPARATOR . $arquivo)):
			$arquivo = $this->nome . '-'. uniqid() . strrchr($this->arquivo['name'], '.'); 
		endif;

		$this->nome = $arquivo;
	}

	/**
     * [criarPasta] => Método responsável pela criação do directório, caso for especificado.
     * @return path.
     */

	private function criarPasta () {
		if (!file_exists($this->directorio).DIRECTORY_SEPARATOR.$this->pasta && !is_dir($this->directorio.DIRECTORY_SEPARATOR.$this->pasta)):
			mkdir($this->directorio . DIRECTORY_SEPARATOR . $this->pasta, 0777);
		endif;

	}

	/**
     * [moveArquivo] => Método responsável pelo armazenamento do arquivo.
	 * Se o arquivo for movido, pega o nome do arquvi, caso não, retorna false.
     * @return string|bool 
     */
	private function moveArquivo () {
		if (move_uploaded_file($this->arquivo['tmp_name'], $this->directorio.DIRECTORY_SEPARATOR.$this->pasta.DIRECTORY_SEPARATOR.$this->nome)) :
			$this->result = $this->nome;
 		else:
			$this->error  = "Erro ao mover o arquivo";
			$this->result = false;
	    endif;
	}

}