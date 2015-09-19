<?php

class Vophper_Format_Date 
{
	static public function format_database($date, $format='DD-MM-YYYY')
	{					
		$valid = self::validate($date);
		
		if ($valid) return $date;
		
		$valid = self::validate($date, $format);
		
		if (!$valid) return '0000-00-00';
		
		$date = self::transform($date, $format);
		
		return $date;
	}
	
	static public function validate( $date, $format='YYYY-MM-DD')
    {    	
        switch( $format )
        {
            case 'YYYY/MM/DD':
            case 'YYYY-MM-DD':
            list( $y, $m, $d ) = array_pad(preg_split( '/[-\.\/ ]/', $date), 3, 0);
            break;

            case 'YYYY/DD/MM':
            case 'YYYY-DD-MM':
            list( $y, $d, $m ) = array_pad(preg_split( '/[-\.\/ ]/', $date), 3, 0);
            break;

            case 'DD-MM-YYYY':
            case 'DD/MM/YYYY':
            list( $d, $m, $y ) = array_pad(preg_split( '/[-\.\/ ]/', $date), 3, 0);
            break;

            case 'MM-DD-YYYY':
            case 'MM/DD/YYYY':
            list( $m, $d, $y ) = array_pad(preg_split( '/[-\.\/ ]/', $date), 3, 0);
            break;

            case 'YYYYMMDD':
            $y = substr( $date, 0, 4 );
            $m = substr( $date, 4, 2 );
            $d = substr( $date, 6, 2 );
            break;

            case 'YYYYDDMM':
            $y = substr( $date, 0, 4 );
            $d = substr( $date, 4, 2 );
            $m = substr( $date, 6, 2 );
            break;

            default:
            throw new Exception( "Formato de data inválido" );
        }
        
        if (!(is_numeric($m) && is_numeric($d) && is_numeric($y))) return false;
        
        return checkdate( $m, $d, $y );
    }
	
	static public function transform( $date, $format='DD-MM-YYYY', $out='Y-m-d')
    {
        switch( $format )
        {
            case 'YYYY/MM/DD':
            case 'YYYY-MM-DD':
            list( $y, $m, $d ) = preg_split( '/[-\.\/ ]/', $date );
            break;

            case 'YYYY/DD/MM':
            case 'YYYY-DD-MM':
            list( $y, $d, $m ) = preg_split( '/[-\.\/ ]/', $date );
            break;

            case 'DD-MM-YYYY':
            case 'DD/MM/YYYY':
            list( $d, $m, $y ) = preg_split( '/[-\.\/ ]/', $date );
            break;

            case 'MM-DD-YYYY':
            case 'MM/DD/YYYY':
            list( $m, $d, $y ) = preg_split( '/[-\.\/ ]/', $date );
            break;

            case 'YYYYMMDD':
            $y = substr( $date, 0, 4 );
            $m = substr( $date, 4, 2 );
            $d = substr( $date, 6, 2 );
            break;

            case 'YYYYDDMM':
            $y = substr( $date, 0, 4 );
            $d = substr( $date, 4, 2 );
            $m = substr( $date, 6, 2 );
            break;

            default:
            throw new Exception( "Formato de data inválido" );
        }
        
        $date = date($out, mktime(0, 0, 0, $m, $d, $y));
        
        return $date;
    }	
}