<?php

	class Usuario{

		private $strNombre;
		private $strEmail;
		private $strTipo;
		private $strClave;
		protected $strFechaRegistro;
		static $strEstado = "Activo";

		function __construct(string $nombre, string $email, string $tipo)
		{
			$this->strNombre = $nombre;
			$this->strEmail = $email;
			$this->strTipo = $tipo;
			$this->strClave = rand();
			$this->strFechaRegistro = date('Y-m-d H:m:s');
		}

		public function getNombre():string
		{
			return $this->strNombre;
		}

		public function getEmail():string
		{
			return $this->strEmail;
		}

		public function gerPerfil() {
			echo "<div style='border: 1px solid #ccc; padding: 10px; font-family: sans-serif;'>"; // Contenedor con estilos
			echo "<h2 style='color: #333;'>Datos del usuario</h2>"; // Encabezado
		
			echo "<p><strong>Nombre:</strong> " . $this->strNombre . "</p>";
			echo "<p><strong>Email:</strong> " . $this->strEmail . "</p>";
		
			// ¡Nunca muestres la clave real!
			echo "<p><strong>Clave:</strong> " . $this->strClave . "</p>"; 
		
			echo "<p><strong>Fecha de registro:</strong> " . $this->strFechaRegistro . "</p>";
			echo "<p><strong>Estado:</strong> " . self::$strEstado . "</p>";
		
			echo "</div>"; // Cierre del contenedor
		}

		public function setCambiarClave(string $pass){
			$this->strClave = $pass;
		}

	}//End class usuario

 ?>