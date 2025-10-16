<?php

namespace app\helpers;

class DateHelper
{
    public static function convertirAFormatoMySQL(string $fecha, string $formato = 'd/m/Y'): string
    {
        $dateTime = \DateTime::createFromFormat($formato, $fecha);
        return $dateTime->format('Y-m-d H:i:s');
    }

    public static function toAmPmTime(string $hora24): string
    {
        $dateTime = \DateTime::createFromFormat('H:i:s', $hora24);
        if ($dateTime === false) {
            return "Formato de hora inválido"; // Manejo de error si la hora de entrada no es válida
        }
        return $dateTime->format('h:i a');
    }

    public static function toDate(string $fecha, string $coutry = 'COL'): string
    {
        $dateTime = new \DateTime($fecha);
        $formatoFecha = 'Y-m-d H:i:s'; // Formato por defecto (por si acaso)

        // Convertir el código de país a mayúsculas para comparar
        $coutryUpper = strtoupper($coutry);

        if ($coutryUpper === 'COL' || $coutryUpper === 'COLOMBIA' || $coutryUpper === 'ES' || $coutryUpper === 'ESPAÑA' || $coutryUpper === 'MX' || $coutryUpper === 'MEXICO') {
            // Formato de fecha descriptivo en español: "día de la semana, día de mes de mes de año"

            // **Usando IntlDateFormatter para formato localizado**
            $fmt = new \IntlDateFormatter(
                'es_CO', // Locale para Colombia (español de Colombia). Puedes ajustar el locale si es necesario.
                \IntlDateFormatter::FULL, // Formato de fecha completo (no necesitamos la hora aquí, pero FULL es necesario para obtener nombres de día y mes)
                \IntlDateFormatter::NONE,   // No necesitamos formato de hora
                'America/Bogota',        // Zona horaria (opcional, pero recomendable si es relevante la zona horaria)
                \IntlDateFormatter::GREGORIAN // Calendario Gregoriano (el más común)
            );
            $fmt->setPattern('EEEE, d \'de\' MMMM \'de\' yyyy'); // Define el patrón de formato deseado

            return $fmt->format($dateTime);
        } else {
            // Para otros países, volvemos al formato por defecto Y-m-d H:i:s
            return $dateTime->format($formatoFecha);
        }
    }

    /**
     * Calcula los años transcurridos desde una fecha dada hasta la fecha actual.
     *
     * @param string $fecha Fecha en formato legible (d/m/Y o cualquier otro formato)
     * @param string $formato Formato de la fecha de entrada (por defecto 'd/m/Y')
     * @return int Años transcurridos
     */
    public static function calcularAniosTranscurridos(string $fecha, string $formato = 'd/m/Y'): int
    {
        $fechaInicial = \DateTime::createFromFormat($formato, $fecha);
        $fechaActual = new \DateTime();
        $diferencia = $fechaInicial->diff($fechaActual);

        return $diferencia->y; // Retorna los años de diferencia
    }

    public static function calcularMesesTranscurridos(string $fecha, string $formato = 'd/m/Y'): int
    {
        $fechaInicial = \DateTime::createFromFormat($formato, $fecha);
        // var_dump($fechaInicial);
        $fechaActual = new \DateTime();
        $diferencia = $fechaInicial->diff($fechaActual);

        // Calculamos los meses totales (incluyendo años)
        $mesesTotales = $diferencia->y * 12 + $diferencia->m;

        return $mesesTotales;
    }

    /**
     * Obtiene la fecha actual en formato Y-m-d H:i:s.
     *
     * @return string Fecha actual en formato MySQL
     */
    public static function obtenerFechaActual(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }

    public static function sumarDias(string $fecha, int $dias): string
    {
        $fecha_inicial = new \DateTime($fecha);
        $fecha_inicial->add(new \DateInterval('P' . $dias . 'D'));
        return $fecha_inicial->format('Y-m-d H:i:s');
    }

    public static function convertirHoraAFormatoMySQL(string $hora, string $formato = 'h:i A'): string
    {
        // Crea un objeto DateTime a partir de la hora dada y el formato
        $dateTime = \DateTime::createFromFormat($formato, $hora);

        // Si no se pudo crear el objeto DateTime (por formato incorrecto), lanzar una excepción
        if (!$dateTime) {
            throw new \Exception("Formato de hora inválido o no reconocido.");
        }

        // Devuelve la hora en el formato MySQL (H:i:s)
        return $dateTime->format('H:i:s');
    }
}
