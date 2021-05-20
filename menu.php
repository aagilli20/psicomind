<?php
/*
getMenu()
Esta clase genera el código html correspondiente al menú principal del sistema. De esta forma, en lugar de repetir el menú principal en todas las plantillas, solamente invocamos esta función y, la misma, generará el código necesario para cargar el menú.

Autor: Andrés Gilli
*/
function getMenu(){
$html = "<div id='menu' class='menu'>
        	<ul id='tiny'>
				<li><a href='#'>profesional</a>
					<ul>
			            <li><a href='elegir_persona_profesional.php'>nuevo profesional</a></li>
                        <li><a href='listado_profesionales.php'>profesionales</a></li>
                        <li><a href='reactivar_profesional.php'>Reactivar Profesional</a></li>
                        <li><a href='elegir_profesional_horario_atencion.php'>asignar horario de atencion</a></li>
                        <li><a href='elegir_profesional_especialidad.php'>asignar especialidad</a></li>
			        </ul>
				</li>
				<li><a href='#'>horario de atencion</a>
					<ul>
			            <li><a href='nuevo_horario_atencion.php'>nuevo horario de atencion</a></li>
			            <li><a href='listado_horarios_atencion.php'>horarios de atencion</a></li>
						<li><a href='horarios_atencion_asignados.php'>horarios de atencion asignados</a></li>
                        <li><a href='nuevo_dia_no_laboral.php'>nuevo dia no laboral</a></li>
                        <li><a href='listado_dias_no_laborales.php'>dias no laborales</a></li>
			        </ul>
				</li>
				<li><a href='#'>turno</a>
                	<ul>
                    	<li><a href='elegir_paciente_consignar_turno.php'>consignar turno</a></li>
                        <li><a href='elegir_paciente_turno_optimo.php'>buscar turno optimo</a></li>
			            <li><a href='elegir_profesional_turnos.php'>turnos por dia</a></li>
			            <li><a href='elegir_profesional_turnos_mes.php'>turnos por mes</a></li>
			        </ul>
                </li>
                <li><a href='#'>paciente</a>
                	<ul>
			            <li><a href='elegir_persona_paciente.php'>nuevo paciente</a></li>
                        <li><a href='elegir_paciente_minusvalia.php'>registrar minusvalia</a></li>
						<li><a href='elegir_paciente_obra_social.php'>asociar obra social</a></li>
			            <li><a href='listado_pacientes.php'>pacientes</a></li>
                        <li><a href='reactivar_paciente.php'>reactivar paciente</a></li>
                        <li><a href='elegir_paciente_historia_clinica.php'>historia clinica</a></li>
                        <li><a href='elegir_persona_nueva_sesion.php'>nueva sesion</a></li>
                        <li><a href='elegir_persona_realizar_test.php'>realizar test</a></li>
                        <li><a href='crear_nota_rapida.php'>crear nota rapida</a></li>
                        <li><a href='elegir_persona_buscar_nota_rapida.php'>buscar nota rapida</a></li>
                        <li><a href='elegir_persona_crear_alerta.php'>crear alerta</a></li>
                        <li><a href='elegir_persona_buscar_alerta.php'>buscar alerta</a></li>
			        </ul>
                </li>
                <li><a href='#'>obra social</a>
                	<ul>
                        <li><a href='nueva_obra_social.php'>nueva obra social</a></li>
						<li><a href='nuevo_plan_obra_social.php'>nuevo plan</a></li>
						<li><a href='listado_obras_sociales.php'>obras sociales</a></li>
			        </ul>
                </li>
                 <li><a href='#'>test</a>
                	<ul>
			            <li><a href='nuevo_test.php'>nuevo test</a></li>
			            <li><a href='listado_test.php'>buscar test</a></li>
			        </ul>
                </li>
                <li><a href='#'>administracion</a>
                	<ul>
			            <li><a href='configuracion.php'>configuracion</a></li>
                        <li><a href='nueva_persona.php'>nueva persona</a></li>
                        <li><a href='listado_personas.php'>personas</a></li>
                        <li><a href='nuevo_usuario.php'>nuevo usuario</a></li>
                        <li><a href='listado_usuarios.php'>usuarios</a></li>
                        <li><a href='reactivar_usuario.php'>reactivar usuario</a></li>
			            <li><a href='nuevo_ticket.php'>nuevo ticket de consulta</a></li>
                        <li><a href='listado_tickets.php'>tickets de consulta</a></li>
			        </ul>
                </li>
			</ul>
		</div>";
return utf8_encode($html);
}
?>