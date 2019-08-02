<?php

/**
 * Archivo principar de la clase..
 *
 * Manejo de archivos del sistema solitram
 *
 * @author lquiroga <@> lquiroga@gmail.com
 * @todo FechaC 28/02/2019 - Lenguaje PHP
 *
 * @name class_formularios.php
 *
 */
// require_once ("DerechosVarios.php");
// require_once ("Carreras.php");
// require_once ("Alumnos.php");
require_once ("/web/html/classesUSAL/class_Personas.php");
require_once ("/web/html/classes/class_derechos_varios.php");
require_once ("/web/html/classes/class_alumnos.php");
require_once ("/web/html/classes/class_carreras.php");

class Formularios
{
	protected $db;
	protected $fecha_crecion;
	protected $STUDENT;
	protected $tipo_form;
	protected $estado;
	protected $PERSON;
	protected $PERSON_aprobo;
	protected $html_template;
	protected $nombre_form;
	protected $IDDERECHOVARIO;

	public function __construct($db, $tipo = null, $id = null)
	{
		$this->db = $db;
		// Si no hay id o y si tipo devolvemos el html del form
		if ($tipo != null && $tipo != '' && $id == null && $id == '')
		{

			$this->set_tipo_form ($tipo);

			$this->template_html ($tipo);

			/* Obtengo el nombre del form basado en la tabla interfaz.tipo_alumno */
			$parametros = array (
					$tipo
			);

			$query = "select DESCRIPCION from interfaz.tipo_alumno where TIPO_ALUMNO = LPAD(:tipo, 2, '0')";

			$result = $this->db->query ($query, true, $parametros);

			if ($result)
			{

				$arr_asoc = $this->db->fetch_array ($result);

				$this->set_nombre_form ($arr_asoc['DESCRIPCION']);
			}
		}

		// Si tipo es null pero id no , devolvemos los datos del form
		if (($tipo == null || $tipo == '') && ($id != null || $id != ''))
		{

			$parametros = array (
					$id
			);

			$query = "select FORMULARIO.* , tipo_alumno.DESCRIPCION
                        FROM FORMULARIO
                        JOIN interfaz.tipo_alumno ON
                        FORMULARIO.IDTIPOFORM = tipo_alumno.TIPO_ALUMNO
                        WHERE FORMULARIO.id = :id ";

			$result = $this->db->query ($query, true, $parametros);

			if ($result)
			{

				$arr_asoc = $db->fetch_array ($result);

				$this->loadData ($arr_asoc);
			}
		}
	}

	/**
	 * Salvar formulario en la tabla TESORERIA.FORMULARIO.
	 *
	 * @param array $datos
	 *        	datos para insertar en la tabla.
	 *        	El array de datos se maneja con indices y valores ej:$datos['PERSON'] ='alumno'
	 *
	 * @return bool retorna tru o false de acuerdo a si se hizo la insercion
	 *
	 *         Campos de la tabla :
	 *
	 *         ID - PERSON - STUDENT - FECHAC - IDTIPOFORM - IDESTADO
	 *         COMENTARIO - FA - ES - CA - PLAN - CURSOHASTA - ANIONOMATRICULA - ULTIMAASISTENCIA
	 *         ANIOCURSAREGULAR
	 *
	 */
	public function saveTesoreriaForm($datos)
	{

		// $db = Conexion::openConnection();
		$datos['ID'] = 'TESORERIA.FORMULARIO_SEQ.nextval';

		// $insercion = $this->db->realizarInsert ($datos, 'FORMULARIO');
		$this->db->realizarInsert ($datos, 'FORMULARIO');

		$id_insertado = $this->db->insert_id ('ID', 'FORMULARIO');

		if ($id_insertado)
		{

			$tabla = 'FORMULARIOHIST';

			$data_historial = array ();
			$data_historial['ID'] = 'TESORERIA.FORMULARIOHIST_SEQ.nextval';
			$data_historial['IDFORMULARIO'] = $id_insertado;
			$data_historial['FECHAM'] = "SYSDATE";
			$data_historial['IDESTADO'] = $datos['IDESTADO'];
			$data_historial['COMENTARIO'] = $datos['COMENTARIO'];
			$data_historial['PERSON'] = $datos['PERSON'];

			$this->insertHistory ($data_historial, $tabla);
		}

		return $id_insertado;
	}

	/**
	 * Salva datos exclusivos de formularios de tesoreria de solitram
	 * saveTesoreriaExclusivoForm
	 *
	 * tabla :
	 * ID - IDFORMULARIO - FECHAVENC - STUDENT - IMPORTE - CONCEPTO - IMPORTEFT
	 * IMPORTER - CODCOBOL - NRO
	 *
	 * @param array $datos
	 * @return bool
	 *
	 */
	public function saveTesoreriaExclusivoForm($datos)
	{

		// $db = Conexion::openConnection();
		$datos['ID'] = 'TESORERIA.FORMULARIOTESORERIA_SEQ.nextval';

		$insercion = $this->db->realizarInsert ($datos, 'FORMULARIOTESORERIA');

		return $insercion;
	}

	/**
	 * saveMateriasForm
	 *
	 * @param array $datos
	 *        	DE LA TABLA MATERIAS_FORMULARIO
	 *
	 *        	Datos tabla:
	 *        	ID -IDFORMULARIO-SUBJECT-PLAN
	 *
	 * @return BOOL
	 */
	public function saveMateriasForm($datos)
	{
		$datos['IDFORMULARIO'] = $this->db->insert_id ('ID', 'FORMULARIO');

		$insercion = $this->db->realizarInsert ($datos, 'FORMULARIOMATERIAS');

		return $insercion;
	}

	/**
	 * Hace un inserta de los campos que pasemos y la tabla
	 *
	 * @param
	 *        	array data_update -->El array de datos se maneja con indices y valores ej:$datos['PERSON'] ='alumno'
	 * @param
	 *        	strin tabla -->tabla a actualizar
	 * @param
	 *        	string where -->Condicion para actualizar
	 *
	 * @return bool
	 */
	public function updateTesoreriaForm($data_update, $tabla, $where)
	{

		// $db = Conexion::openConnection();
		$insercion = $this->db->realizarUpdate ($data_update, $tabla, $where);

		/* SI SE REALIZO LA CORRECTAMENTE UPDATE DE HISTORIAL */
		if ($insercion)
		{

			$f = date ('d/m/y H-m-s');

			// $formato_hora = '';

			$tabla = 'FORMULARIOHIST';

			$data_historial = array ();

			$data_historial['ID'] = 'TESORERIA.FORMULARIOHIST_SEQ.nextval';

			$data_historial['IDFORMULARIO'] = $where['ID'];

			$data_historial['FECHAM'] = "TO_DATE('" . $f . "','dd/mm/yy hh24-mi-ss')";

			if (isset ($data_update['IDESTADO']))
			{
				$data_historial['IDESTADO'] = $data_update['IDESTADO'];
			}

			if (isset ($data_update['COMENTARIO']))
			{
				$data_historial['COMENTARIO'] = $data_update['COMENTARIO'];
			}

			$data_historial['PERSON'] = Session::get ('person');

			try
			{

				$this->insertHistory ($data_historial, $tabla);
			}
			catch (Exception $e)
			{

				echo 'Excepci&oacute;n capturada: ', $e->getMessage (), "\n";
			}
		}
	}

	/**
	 * Obtiene los formularios por unidad
	 *
	 *
	 * @param int $unidades
	 * @return array con datos de los forms
	 */
	public function getFormByUnidad($unidades, $estado_omitir = null)
	{
		$query = "SELECT
		    formulario.*,
		    formulariotesoreria.concepto,
		    formulariotesoreria.fechavenc,
		    formulariotesoreria.importe,
		    person.lname,
		    person.fname,
		    perdoc.typdoc,
		    perdoc.docno,
		    facu.sdesc,
		    career.descrip,
		    (
		        SELECT
		            person.lname
		             || ' '
		             || person.fname
		        FROM
		            appgral.person
		        WHERE
		            person = formulario.person
		    ) creador
		FROM
		    formulario
		    JOIN appgral.person ON person.person = formulario.student
		    JOIN appgral.perdoc ON person.person = perdoc.person
		    JOIN studentc.facu ON formulario.fa = facu.code
		    FULL JOIN formulariotesoreria ON formulario.id = formulariotesoreria.idformulario
		    JOIN studentc.career ON formulario.fa || lpad(
		        formulario.ca,
		        2,
		        '0'
		    ) = career.code
		WHERE
		    1 = 1";

		if ($unidades != -1 && $unidades != '')
		{
			$query .= "AND LPAD(formulario.fa, 2, '0') IN ( $unidades ) ";
		}

		if ($estado_omitir != null)
		{
			$query .= " AND idestado != $estado_omitir ";
		}

		$query .= "ORDER BY formulario.id DESC";

		$result = $this->db->query ($query);

		while ($fila = $this->db->fetch_array ($result))
		{

			$fila['nombre_form'] = $this->obtenerNombreForm ($fila['IDTIPOFORM']);

			$fila['materias'] = $this->get_materias ($fila['ID']);

			$salida[] = $fila;
		}

		return $salida;
	}

	/**
	 * Obtiene los formularios por unidad
	 *
	 *
	 * @param INT $unidades
	 * @return array con datos de los forms
	 */
	public function getFormsByAlumno($STUDENT, $estado_omitir = null)
	{
		$query = "SELECT FORMULARIO.* ,FORMULARIOTESORERIA.CONCEPTO , FORMULARIOTESORERIA.FECHAVENC
        ,FORMULARIOTESORERIA.IMPORTE ,person.LNAME , person.FNAME ,  perdoc.typdoc,
        perdoc.docno ,facu.SDESC ,CAREER.DESCRIP,
        (SELECT person.LNAME ||' '|| person.FNAME FROM appgral.person WHERE PERSON = FORMULARIO.PERSON)  creador
        from FORMULARIO
        JOIN appgral.person ON person.person = FORMULARIO.STUDENT
        JOIN appgral.perdoc ON person.person = perdoc.person
        JOIN studentc.facu ON FORMULARIO.fa= facu.code
        FULL JOIN FORMULARIOTESORERIA ON  FORMULARIO.ID = FORMULARIOTESORERIA.IDFORMULARIO
        JOIN studentc.CAREER ON FORMULARIO.fa || LPAD(FORMULARIO.CA, 2, '0')= CAREER.code
        WHERE FORMULARIO.STUDENT = $STUDENT";

		// Si exite $estado_omitir pedimos las que no estan en ese estado (por ejemplo pedimos todos los no aprobados)
		if ($estado_omitir != null)
		{
			$query .= " and IDESTADO != $estado_omitir ";
		}

		$query .= " order by FORMULARIO.ID desc";

		$result = $this->db->query ($query);

		while ($fila = $this->db->fetch_array ($result))
		{

			$fila['nombre_form'] = $this->obtenerNombreForm ($fila['IDTIPOFORM']);

			$salida[] = $fila;
		}

		return $salida;
	}

	/**
	 *
	 * Inserta una linea en la tabla que le pasamos para historial
	 *
	 * @param
	 *        	array data_update->datos a insertar en la tabla
	 * @param
	 *        	string tabla->nombre de tabla
	 *
	 * @return bool campos tabla historial formularios :
	 *         ID IDFORMULARIO FECHAC IDTIPOFORM IDESTADO COMENTARIO PERSON
	 *         CURSOHASTA - ANIONOMATRICULA - ULTIMAASISTENCIA - ANIOCURSAREGULAR
	 *
	 */
	public function insertHistory($data_update, $tabla)
	{

		// $db = Conexion::openConnection();
		$insercion = $this->db->realizarInsert ($data_update, $tabla);

		return $insercion;
	}

	/**
	 * GetFormById
	 * En base al id de un formulario obtenemos sus datos
	 *
	 * @param int $id
	 * @return array
	 */
	public function getFormById($id)
	{
		$parametros = array (
				$id
		);

		// $this->db = Conexion::openConnection();

		$query = " SELECT FORMULARIO.* ,FORMULARIOTESORERIA.CONCEPTO ,
            FORMULARIOTESORERIA.FECHAVENC
            ,FORMULARIOTESORERIA.IMPORTE from FORMULARIO
            FULL JOIN FORMULARIOTESORERIA ON FORMULARIO.ID = FORMULARIOTESORERIA.IDFORMULARIO
            WHERE FORMULARIO.ID = :id";

		$result = $this->db->query ($query, true, $parametros);

		$form = $this->db->fetch_array ($result);

		$form['materias'] = $this->get_materias ($form['ID']);

		if (!$form['IDDERECHOVARIO'])
		{

			/* Obtengo el nombre del form basado en la tabla interfaz.tipo_alumno */
			$parametros_nombre = array (
					$form['IDTIPOFORM']
			);

			$query_nombre = "select DESCRIPCION from interfaz.tipo_alumno where TIPO_ALUMNO = LPAD(:tipo, 2, '0')";

			$result_nombre = $this->db->query ($query_nombre, true, $parametros_nombre);

			$arr_asoc = $this->db->fetch_array ($result_nombre);

			$form['NOMBRE_FORM'] = $arr_asoc['DESCRIPCION'];
		}
		else
		{

			$dvario = new DerechosVarios ($this->db, $form['IDDERECHOVARIO']);

			$form['NOMBRE_FORM'] = $dvario->get_descripcion ();
		}

		return ($form);
	}

	/**
	 *
	 * En base al id recibimos el nombre del form
	 *
	 * @param INT $id
	 * @return string
	 *
	 */
	public function obtenerNombreForm($id)
	{
		$parametros = array (
				$id
		);

		$query = " select DESCRIPCION from interfaz.tipo_alumno WHERE LPAD(TIPO_ALUMNO, 2, '0') =LPAD(:tipo_alumno, 2, '0') ";

		$result = $this->db->query ($query, true, $parametros);

		$form = $this->db->fetch_array ($result);

		return ($form[0]);
	}

	/**
	 *
	 * En base al tipo de form que recibimos , mostramos
	 * el template correspondiente
	 *
	 * @param string $tipo
	 *        	-->id de tipo formulario
	 * @return string html
	 *
	 */
	public function template_html($tipo, $data = null, $lectura = 0)
	{
		// $fecha_actual = date ("d/m/Y");
		$template = '';

		// Id tipos form , menosres de 100 son tipos de alumnos, formularios de cobranza
		// de 100 a 200 son formularios de secretaria general
		if ($tipo <= '100')
		{

			/* * ******SI NO HAY DATA MUESTRO LOS FORMS LIMPIOS********** */
			if (!$data)
			{

				switch ($tipo)
				{

					case '43' :
						$template .= '<label>' . '<b>Fecha de &uacute;ltima asistencia</b>' . '</label>' . '<input type="hidden" value="43" name="tipoform" id="tipoform">' . '<input type="date" name="fecha_1" id="fecha_1" class="fecha" value="' . date ("Y-m-d") . '" required>' . '<label for="fecha_1_error" id="fecha_1_error" ></label>' . '<p class="recordatorio_ayuda">La &uacute;ltima fecha registrada en el sistema es 21/12/2018</p><br/>' . '<label>En el presente periodo lectivo, no rendir&aacute; ni cursar&aacute; asignatura alguna, ' . 'correspondiente a la carrera mencionada,' . ' dejando constancia de la fecha en la que' . ' ha dejado de hacerlo.</label>';
						break;

					case '86' :

						$template .= '<input type="hidden" value="86" name="tipoform">' . '<label><b>Curso hasta el: </b></label>' . '<input type="date" style="width: 100% !important;" value="' . date ("Y-m-d") . '" name="fecha_1" id="fecha_1" class="valid fecha" aria-invalid="true">' . '<label>Del corriente a&ntilde;o, rendir&aacute; ex&aacute;menes finales.</label>' . '<label for="fecha_1_error" id="fecha_1_error" ></label>';

						break;

					case '01' :

						$min = date ("Y") - 5;
						$max = date ("Y") + 5;

						$template .= '<input type="hidden" value="01" name="tipoform">' . '<label><b>Cursar&aacute; regularmente durante el a&ntilde;o:</b>' . '<input type="number" value="' . date ("Y") . '" name="anio_cursa" id="anio_cursa" class="fecha" ' . 'min="' . $min . '" max="' . $max . '" >' . '<label for="anio_cursa_error" id="anio_cursa_error" ></label>';

						break;

					case '07' :

						$template .= '<label>Los alumnos regulares que cursen obligaciones acad&eacute;micas por un total de ' . '162 horas reales totales, anuales, abonaran el 50% del arancel vigente correspondiente al ' . 'alumno de curso completo. La constancia correspondiente debe ser presentada hasta el 30 de abril,' . ' caso contrario no tendr&aacute; efectos retroactivos.</label><input type="hidden" value="07" name="tipoform">' . '<br/><br/><label><b>Cursar&aacute; la/s siguiente/s materia/s: </b></label>';

						break;

					case '05' :

						$template .= '<input type="hidden" value="05" name="tipoform">' . '<label> Los alumnos regulares que cursen obligaciones acad&eacute;micas ' . 'por un total inferior a 90 horas reales totales cuatrimestrales, abonaran el 50% del' . ' arancel correspondiente al alumno que cursa �nicamente primero o segundo cuatrimestre.' . ' La constancia correspondiente debe ser presentada el 30 de abril por el primer cuatrimestre ' . 'y hasta el 31 de agosto, por el segundo cuatrimestre, caso contrario no tendr&aacute; efectos retroactivos.' . ' No pueden combinarse los planes (cuota completa y media cuota) entre primero y segundo cuatrimestre.<br/><br/></label>' . '<label><b>Cursar&aacute; la/s siguiente/s materia/s: </b></label>';

						break;

					case '06' :

						$template .= '<input type="hidden" value="06" name="tipoform">' . '<label> Los alumnos regulares que cursen obligaciones acad&eacute;micas' . ' por un total inferior a 90 horas reales totales cuatrimestrales, abonaran el 50% del arancel ' . 'correspondiente al alumno que cursa �nicamente primero o segundo cuatrimestre. ' . 'La constancia correspondiente debe ser presentada el 30 de abril por el primer cuatrimestre y hasta ' . 'el 31 de agosto, por el segundo cuatrimestre, caso contrario no tendr&aacute; efectos retroactivos.' . ' No pueden combinarse los planes (cuota completa y media cuota) entre primero y segundo cuatrimestre.<br/><br/></label>' . '<label><b>Cursar&aacute; la/s siguiente/s materia/s: </label></b>';

						break;

					case '49' :

						$anios_posibles = array (
								date ('Y'),
								date ('Y') + 1
						);

						$template .= '<input type="hidden" value="49" name="tipoform">' . '<label> Alumnos que no cursan un ciclo lectivo,' . ' o han finalizado la cursada de la carrera y deben rendir materias en el turno de ' . 'febrero y marzo sin abonar matricula.</label>' . '<br/><br/><label>Solicito rendir en el turno de Febrero/Marzo sin matricularse en el a&ntilde;o</label>' . '<select id="select_anio" name="select_anio" >';

						foreach ($anios_posibles as $row)
						{

							if ($data['ANIONOMATRICULA'] == $row)
							{

								$template .= "<option selected value='$row'> $row</option>";
							}
							else
							{

								$template .= "<option  value='$row'> $row</option>";
							}
						}

						break;

					// FROM GENERICO QUE PUEDE CREAR TESORERIA
					case '58' :
						$template .= '<input type="hidden" value="58" name="tipoform">' . '<label>Concepto</label><br/>' . '<select name="concepto">' . '<option value="05">05 - Arancel a&ntilde;o ant.  </option>' . '<option value="05">05 - Transporte a&ntilde;o ant</option>' . '<option value="02">02 - Arancel           </option>' . '<option value="02">02 - Curso de verano   </option>' . '<option value="02">02 - Transporte        </option>' . '<option value="02">02 - Practicas         </option>' . '<option value="09">09 - Matricula a&ntilde;o ant.</option>' . '<option value="01">01 - Matricula         </option>' . '<option value="03">03 - Total matricula   </option>' . '<option value="04">04 - Cuota plan        </option>' . '<option value="04">04 - Moratoria arancel </option>' . '<option value="06">06 - A.cuenta          </option>' . '<option value="07">07 - Plan pago a&ntilde;o ant.</option>' . '<option value="07">07 - Morat.ant.aran.   </option>' . '<option value="08">08 - Cuota plan. matr. </option>' . '<option value="08">08 - Morat. matricula  </option>' . '<option value="89">89 - Cta.pla.ma.egre   </option>' . '<option value="68">68 - Cta.pl.mat.egr.ant</option>' . '<option value="63">63 - Mat.nuevo a&ntilde;o     </option>' . '<option value="32">32 - Cuot.adic.inter   </option>' . '<option value="67">67 - Dev.prestamo      </option>' . '<option value="68">68 - Plan.matr.ant.    </option>' . '<option value="68">68 - Morat.ant.matr.   </option>' . '<option value="91">91 - Curso ingreso     </option>' . '<option value="80">80 - Curso de ingles   </option>' . '<option value="81">81 - Curso ingles p.t. </option>' . '<option value="92">92 - Cuota ingreso     </option>' . '<option value="90">90 - Matr. a egresar   </option>' . '<option value="97">97 - Comision cheques  </option>' . '<option value="98">98 - Pago en sede      </option>' . '<option value="61">61 - Anticipo mutuos   </option>' . '<option value="62">62 - Mutuo serie a     </option>' . '<option value="64">64 - Mutuo serie b     </option>' . '<option value="66">66 - Mutuo serie 1     </option>' . '<option value="60">60 - Interes claus.4�  </option>' . '<option value="65">65 - Actualizacion     </option>' . '<option value="40">40 - Mutuo serie c     </option>' . '<option value="41">41 - Anticip.mutuo c   </option>' . '<option value="42">42 - Cuo.mat.prox. a&ntilde;o </option>' . '<option value="43">43 - Arancel prox. a&ntilde;o </option>' . '<option value="44">44 - Devoluc.mutuos 1  </option>' . '<option value="36">36 - Alojamiento       </option>' . '<option value="35">35 - Derecho especif.  </option>' . '<option value="45">45 - Cursos extraordin.</option>' . '<option value="39">39 - Materia           </option>' . '</select>' . '<label for"importe">Importe</label><input type="number" name="importe" step="0.01"/><br/>' . '<label for"importeFT">Fuera de t&eacute;rmino</label><input type="number" name="importeFT" step="0.01" value=0/><br/>' . '<label for"importeR">Recargo</label><input type="number" name="importeR" step="0.01" value=0/><br/>' . '<label>Fecha de vencimiento</label><br/>' . '<input type="date" style="width: 100% !important;" value="' . date ("Y-m-d") . '" name="fecha_1" id="fecha_1" class="valid fecha" aria-invalid="true">' . '<br/>';

						break;

					default :

						break;
				}
			}
			else
			{
				/* * ******SI HAY DATA MUESTRO LOS FORMS CON LOS DATOS********** */
				switch ($tipo)
				{

					case '43' :

						$newDate = date ("Y-m-d", strtotime ($data["ULTIMAASISTENCIA"]));

						$template .= '<label>' . '<b>Fecha de &uacute;ltima asistencia</b>' . '</label>' . '<input  type="hidden" value="43" name="tipoform" id="tipoform">';

						if ($lectura)
						{

							$template .= '<label>' . $newDate . '</label><BR/>';
						}
						else
						{

							$template .= '<input  type="date" name="fecha_1" id="fecha_1" class="fecha" value="' . $newDate . '" required>';
						}

						$template .= '<label for="fecha_1_error" id="fecha_1_error" ></label>';

						break;

					case '86' :

						$template .= '<input type="hidden" value="86" name="tipoform">' . '<label><b>Curso hasta el: </b></label>' . '<input disabled type="date" style="width: 100% !important;" value="' . date ("Y-m-d", strtotime ($data["CURSOHASTA"])) . '" name="fecha_1" id="fecha_1" class="valid fecha" aria-invalid="true">' . '<label>Del corriente a&ntilde;o, rendir&aacute; ex&aacute;menes finales.</label>' . '<label for="fecha_1_error" id="fecha_1_error" ></label>';

						break;

					case '01' :

						$min = date ("Y") - 5;
						$max = date ("Y") + 5;

						$template .= '' . '<input  type="hidden" value="01" name="tipoform">' . '<input  type="hidden" value="' . $data["ANIOCURSAREGULAR"] . '" name="anio_cursa" name="tipoform">' . '<label><b>Cursar&aacute; regularmente durante el a&ntilde;o:</b>' . '<input disabled type="number" value="' . $data["ANIOCURSAREGULAR"] . '" name="anio_cursa2" id="anio_cursa" class="fecha" ' . 'min="' . $min . '" max="' . $max . '" >' . '<label for="anio_cursa_error" id="anio_cursa_error" ></label>';

						break;

					case '07' :

						$template .= '<label>Los alumnos regulares que cursen obligaciones acad&eacute;micas por un total de ' . '162 horas reales totales, anuales, abonaran el 50% del arancel vigente correspondiente al ' . 'alumno de curso completo. La constancia correspondiente debe ser presentada hasta el 30 de abril,' . ' caso contrario no tendr&aacute; efectos retroactivos.</label><input type="hidden" value="07" name="tipoform">' . '<br/><br/><label>' . '<input type="hidden" value="07" name="tipoform">' . '<label><b>Cursar&aacute; la/s siguiente/s materia/s: </b></label>';

						break;

					case '05' :

						$template .= '<label> Los alumnos regulares que cursen obligaciones acad&eacute;micas ' . 'por un total inferior a 90 horas reales totales cuatrimestrales, abonaran el 50% del' . ' arancel correspondiente al alumno que cursa �nicamente primero o segundo cuatrimestre.' . ' La constancia correspondiente debe ser presentada el 30 de abril por el primer cuatrimestre ' . 'y hasta el 31 de agosto, por el segundo cuatrimestre, caso contrario no tendr&aacute; efectos retroactivos.' . ' No pueden combinarse los planes (cuota completa y media cuota) entre primero y segundo cuatrimestre.<br/><br/></label>' . '<label>' . '<input type="hidden" value="05" name="tipoform">' . '<label><b>Cursar&aacute; la/s siguiente/s materia/s: </b></label>';

						break;

					case '06' :

						$template .= '<input type="hidden" value="06" name="tipoform">' . '<label> Los alumnos regulares que cursen obligaciones acad&eacute;micas' . ' por un total inferior a 90 horas reales totales cuatrimestrales, abonaran el 50% del arancel ' . 'correspondiente al alumno que cursa �nicamente primero o segundo cuatrimestre. ' . 'La constancia correspondiente debe ser presentada el 30 de abril por el primer cuatrimestre y hasta ' . 'el 31 de agosto, por el segundo cuatrimestre, caso contrario no tendr&aacute; efectos retroactivos.' . ' No pueden combinarse los planes (cuota completa y media cuota) entre primero y segundo cuatrimestre.<br/><br/></label>' . '<label><b>Cursar&aacute; la/s siguiente/s materia/s: </label></b>';

						break;
					// FROM GENERICO QUE PUEDE CREAR TESORERIA
					case '58' :

						$template .= '<input type="hidden" value="58" name="tipoform">' . '<label>Concepto</label><br/>' . '<input type="text" name="concepto"  value="' . $data["CONCEPTO"] . '"/><br/>' . '<label>Importe</label><br/>' . '<input type="number" name="importe"  value="' . $data["IMPORTE"] . '" /><br/>' . '<label>Fecha de vencimiento</label><br/>' . '<input type="date" style="width: 100% !important;" value="' . date ("Y-m-d") . '" name="fecha_1" id="fecha_1" class="valid fecha" aria-invalid="true">' . '<br/>';

						break;

					case '49' :

						$anios_posibles = array (
								date ('Y'),
								date ('Y') + 1
						);

						$template .= '<input type="hidden" value="49" name="tipoform">' . '<label> Alumnos que no cursan un ciclo lectivo,' . ' o han finalizado la cursada de la carrera y deben rendir materias en el turno de ' . 'febrero y marzo sin abonar matricula.</label>' . '<br/><br/><label>Solicito rendir en el turno de Febrero/Marzo sin matricularse en el a&ntilde;o</label>' . '<select id="select_anio" name="select_anio" >';

						foreach ($anios_posibles as $row)
						{

							if ($data['ANIONOMATRICULA'] == $row)
							{

								$template .= "<option selected value='$row'> $row</option>";
							}
							else
							{

								$template .= "<option  value='$row'> $row</option>";
							}
						}

						$template .= '</select> <label> y se compromete a no cursar ninguna materia.</label>';

						break;

					default :

						break;
				}
			}
		}

		// Rango de tipo para forms de secretaria general
		// Estos forms son los que necesitan listas de materias , si entra por aca , devuelve un select
		// con las materias , si hay datos devuelve las materias seleccionadas en un div aparte , las demas en
		// select
		if ($tipo == '90' || $tipo == '07' || $tipo == '05' || $tipo == '06' || $tipo == '111' || $tipo == '112')
		{

			$html_mat_sel = '';

			if (!$data)
			{

				$alumno = new Alumnos ($this->db, Session::get ('personSelect'), Session::get ('solitramcentrodecosto'));
			}
			else
			{

				$alumno = new Alumnos ($this->db, $data['STUDENT'], $data['idcentrodecosto']);
			}

			$carrera = new Carreras ($this->db);

			$estados = '';

			if ($tipo == '90' || $tipo == '07')
			{

				$estados = '1';

				$aprobadas = $alumno->MateriasAprxPlanCarrera ($alumno->get_person (), $alumno->get_carrera (), $alumno->get_plan (), $estados);
			}
			else if ($tipo == '05')
			{

				// cursadas del 1 cuat--->materias del primer o segundo cuatri !no anuales
				$estados = '0';

				$aprobadas = $alumno->MateriasAprxPlanCarrera ($alumno->get_person (), $alumno->get_carrera (), $alumno->get_plan (), $estados, 0);
			}
			else if ($tipo == '06')
			{

				// cursadas del 2 cuat--->materias del primer o segundo cuatri !no anuales
				$estados = '0';

				$aprobadas = $alumno->MateriasAprxPlanCarrera ($alumno->get_person (), $alumno->get_carrera (), $alumno->get_plan (), $estados, 1);
			}
			else
			{

				$estados = '2,3';

				$aprobadas = $alumno->MateriasAprxPlanCarrera ($alumno->get_person (), $alumno->get_carrera (), $alumno->get_plan (), $estados);
			}

			$materias = $carrera->getMateriasPorPlan ($alumno->get_carrera (), $alumno->get_plan (), $aprobadas);

			// Si no hay data devuelve el select de materias
			if ($aprobadas != "")
			{

				if (!$data)
				{

					$materias = $carrera->getMateriasPorPlan ($alumno->get_carrera (), $alumno->get_plan (), $aprobadas);

					$template .= '<input type="hidden" value="90" name="tipoform">' . '<label for="check"> Se matricula como alumno/a a egresar o complementario, debiendo rendir ' . 'solo ex&aacute;menes finales, correspondientes a las siguientes asignaturas: </label> <br/>' . '<ul id="listado_materias">';

					if ($materias != '')
					{

						$template .= "<select id='select_materias'>";

						foreach ($materias as $row)
						{

							$template .= "<option  id='sel_" . $row["SUBJECT"] . "' value='" . $row["SUBJECT"] . "'> " . $row["SUBJECT"] . " - A&ntilde;o: " . $row["YR"] . " - " . $row["SDESC"] . " - " . $row["CARGA_HORARIA"] . " Hs</option>";
						}

						$template .= "</select>";

						$template .= "<input type='button' value='Agregar' id='agregar_mat' onclick='agregar_materia()'><br/>";

						$template .= "<br/><label>Materias seleccionadas: </label><div id='materiasseleccionadas'><br/></div>";
					}
					else
					{

						$template .= "<label>El alumno no posee materias para seleccionar. </label><br/>";
					}
				}
				else
				{

					// Si hay data devuelve select con materias que no esten seleccionadas y las seleccionadas aparte
					$total_horas = 0;

					$mat_cargadas = array ();

					if (isset ($data['materias']))
					{

						foreach ($data['materias'] as $row)
						{

							$mat_cargadas[] = $row['SUBJECT'];
						}

						if ($materias)
						{

							foreach ($materias as $row)
							{

								if (in_array ($row["SUBJECT"], $mat_cargadas))
								{

									$total_horas += $row["CARGA_HORARIA"];

									$html_mat_sel .= '<br/>

                                <p  class="mat_seleccionada mat_seleccionada_' . $row["SUBJECT"] . '"> ' . '' . $row["SUBJECT"] . ' - A&ntilde;o: ' . $row["YR"] . ' - ' . $row["SDESC"] . '  ' . '<span title="' . $row["SDESC"] . '"  class="quitar_materia">
                                </span></p>';

									// $html_mat_sel.='<input id="hidde_'.$row["SUBJECT"].'" type="hidden" name="materias[]" value="'.$row["SUBJECT"].'" />';
								}
							}
						}
					}

					// $template.="<input type='button' value='Agregar' id='agregar_mat' onclick='agregar_materia()'><br/>";

					if ($html_mat_sel == '')
					{

						$template .= "<label>Materias seleccionadas: </label><div id='materiasseleccionadas'></div>";
					}
					else
					{

						$template .= "<div id='materiasseleccionadas' style='display:flex!important;'>";
						$template .= $html_mat_sel;
						$template .= "</div>";
					}

					$template .= '<p class="recordatorio_ayuda">Con un total de ' . $total_horas . ' horas reales anuales.</p>';
				}
			}
			else
			{

				$template .= '<p class="">No posee materias que apliquen a este formulario.</p>';
			}
		}

		if (!$data)
		{

			$template .= '<br/><p>Comentario</p>' . '<textarea name="mensaje" id="mensaje" ></textarea>';
		}
		else
		{

			if ($data["IDESTADO"] == 1)
			{

				$template .= '<br/><p>Comentario</p>' . '<textarea name="mensaje" id="mensaje" ></textarea>';
			}
			else
			{

				/*
				 * $template.= '<br/><p>Comentario</p>'
				 * . '<textarea disabled name="mensaje" id="mensaje" >' . trim($data["COMENTARIO"]) . '</textarea>';
				 */
			}
		}

		$template .= '<div id="loader" class="loader" style="display:none;"> <img src="/images/loading2.gif"> </div>';

		$this->set_html_template ($template);

		return $template;
	}

	/**
	 *
	 * Obtiene el historial de cada Formulario
	 *
	 * @param number $IDFORMULARIO
	 * @param bool $html
	 *        	si es tru nos devuelve el html para ponerlo directamente en panralla , si no devuelve solo datos
	 * @return array datos
	 *
	 */
	public function get_historial($IDFORMULARIO, $html = null)
	{
		$parametros = array (
				$IDFORMULARIO
		);

		// seleccino la hora con * por que la libreria de consulta toma las : como parametros
		// luego reemplzao los * por :
		$query = "select FORMULARIOHIST.ID,
                FORMULARIOHIST.IDFORMULARIO,
                to_char(FECHAM,'DD-MM-YYYY hh24*mi*ss') FECHAM,
                FORMULARIOHIST.IDESTADO,
                FORMULARIOHIST.COMENTARIO,
                FORMULARIOHIST.PERSON ,person.LNAME , person.FNAME from FORMULARIOHIST " . "JOIN appgral.person  " . "on FORMULARIOHIST.PERSON=person.person " . "WHERE FORMULARIOHIST.IDFORMULARIO = :idform order by id desc";

		$result = $this->db->query ($query, true, $parametros);

		while ($fila = $this->db->fetch_array ($result))
		{

			$fila['FECHAM'] = str_replace ('*', ':', $fila['FECHAM']);

			$salida[] = $fila;
		}

		return $salida;
	}

	/**
	 *
	 * Obtiene las materias que tiene cargadas un form
	 *
	 * @param int $IDFORMULARIO
	 * @return array
	 *
	 */
	public function get_materias($IDFORMULARIO)
	{
		$salida = '';

		$parametros = array (
				$IDFORMULARIO
		);

		$query = " select * from FORMULARIOMATERIAS " . "WHERE IDFORMULARIO = :idform order by id desc";
		$fila = '';
		$result = $this->db->query ($query, true, $parametros);

		while ($fila = $this->db->fetch_array ($result))
		{

			$salida[] = $fila;
		}

		return $salida;
	}

	/**
	 *
	 * loadData
	 * Carga propiedades del objeta que vienen desde la DB
	 *
	 * @param array $fila
	 *        	return objet form
	 *
	 */
	public function loadData($fila)
	{
		if (isset ($fila['FECHAC']))
		{
			$this->set_fecha_crecion ($fila['FECHAC']);
		}

		if (isset ($fila['PERSON']))
		{
			$this->set_STUDENT ($fila['PERSON']);
		}

		$this->set_tipo_form ($fila['IDTIPOFORM']);

		if (isset ($fila['ESTADO']))
		{
			$this->set_estado ($fila['ESTADO']);
		}

		if (isset ($fila['PERSONCREO']))
		{
			$this->set_PERSON ($fila['PERSONCREO']);
		}

		if (isset ($fila['PERSONAPROBO']))
		{
			$this->set_PERSON_aprobo ($fila['PERSONAPROBO']);
		}

		if (isset ($fila['TYPOFORM']))
		{
			$this->set_html_template ($this->template_html ($fila['TYPOFORM']));
		}

		if (isset ($fila['DESCRIPCION']))
		{
			$this->set_nombre_form ($fila['DESCRIPCION']);
		}

		if (isset ($fila['IDESTADO']))
		{
			$this->set_estado ($fila['IDESTADO']);
		}

		if (isset ($fila['IDDERECHOVARIO']))
		{
			$this->set_IDDERECHOVARIO ($fila['IDDERECHOVARIO']);
		}
	}

	/**
	 * *******SETERS*********
	 */
	function set_fecha_crecion($fecha)
	{
		$this->fecha_crecion = $fecha;
	}

	function set_STUDENT($STUDENT)
	{
		$this->STUDENT = $STUDENT;
	}

	function set_tipo_form($tipo_form)
	{
		$this->tipo_form = $tipo_form;
	}

	function set_estado($estado)
	{
		$this->estado = $estado;
	}

	function set_person($person)
	{
		$this->person = $person;
	}

	function set_person_aprobo($person_aprobo)
	{
		$this->person_aprobo = $person_aprobo;
	}

	function set_html_template($html_template)
	{
		$this->html_template = $html_template;
	}

	function set_nombre_form($nombre_form)
	{
		$this->nombre_form = $nombre_form;
	}

	function set_IDDERECHOVARIO($IDDERECHOVARIO)
	{
		$this->IDDERECHOVARIO = $IDDERECHOVARIO;
	}

	/**
	 * ******GETTERS*******
	 */
	function get_idderechovario()
	{
		return $this->IDDERECHOVARIO;
	}

	function get_fecha_crecion()
	{
		return $this->fecha_crecion;
	}

	function get_STUDENT()
	{
		return $this->STUDENT;
	}

	function get_tipo_form()
	{
		return $this->tipo_form;
	}

	function get_estado()
	{
		return $this->estado;
	}

	function get_PERSON()
	{
		return $this->PERSON;
	}

	function get_person_aprobo()
	{
		return $this->PERSON_aprobo;
	}

	function get_html_template()
	{
		return $this->html_template;
	}

	function get_nombre_form()
	{
		return $this->nombre_form;
	}
}
