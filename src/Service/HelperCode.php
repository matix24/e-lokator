<?php 

namespace App\Service;

/**
 * klasa helper
 * @package App\Service
 */
class HelperCode
{

    /**
     * Słownik z którego powstanie hasło dla użytkownika 
     * @var string
     */
    const CHAR_DICTIONARY = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Ładnie formatuje datę dla tabel DataTable
     * @param \DateTime $date
     * @return string
     */
    public static function prettyDateForDataTable(\DateTime $date){
        return $date->format('Y-m-d').'<br /><span class="small">('.$date->format('H:i:s').')</span>';
    }// end prettyDateForDataTable

    /**
     * Generuje losowy ciąg znaków z podanego słownika
     * Domyślnie jest to 16 znaków
     * Wykorzystuje to dla wymyślenia hasła dla użytkownika systemu, który jest wprowadzany ręcznie 
     * 
     * @param int $lengthPassword
     * @return string
     */
    public static function generatePassword(int $lengthPassword = 16){
        
        if($lengthPassword > 255){
            $lengthPassword = 255; // max dla bazy danych
        }

        $dictionaryLength = strlen(static::CHAR_DICTIONARY);
        $randomPassword = '';
        for ($i = 0; $i < $lengthPassword; $i++) {
            $randomPassword .= static::CHAR_DICTIONARY[rand(0, $dictionaryLength - 1)];
        }
        return $randomPassword;        
    }// end generatePassword

}// end class
